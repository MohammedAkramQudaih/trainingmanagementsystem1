<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingsController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'advisor_id' => 'required|exists:advisors,id',
        ]);
        $conflictingMeetings = Meeting::where('advisor_id', $validatedData['advisor_id'])
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('start_time', [$validatedData['start_time'], $validatedData['end_time']])
                    ->orWhereBetween('end_time', [$validatedData['start_time'], $validatedData['end_time']])
                    ->orWhere(function ($query) use ($validatedData) {
                        $query->where('start_time', '<=', $validatedData['start_time'])
                            ->where('end_time', '>=', $validatedData['end_time']);
                    });
            })
            ->count();

        if ($conflictingMeetings > 0) {
            return response()->json(['error' => 'There is a scheduling conflict for the requested meeting'], 409);
        }
        $trainee_id = Auth::user()->trainee->id;
        $meeting = new Meeting();
        $meeting->subject = $validatedData['subject'];
        $meeting->start_time = $validatedData['start_time'];
        $meeting->end_time = $validatedData['end_time'];
        $meeting->advisor_id = $validatedData['advisor_id'];
        $meeting->trainee_id = $trainee_id;
        $meeting->save($validatedData);
        return response()->json(['message' => 'Meeting created successfully', 'meeting' => $meeting], 201);
    }
}
