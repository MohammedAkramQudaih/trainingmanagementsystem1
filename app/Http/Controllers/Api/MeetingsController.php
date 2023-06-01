<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Notification;
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

        $notification = new Notification();
        $notification->title = "New Meeting Request";

        $trainee_id = Auth::user()->trainee->id;
        $trainee_name = Auth::user()->trainee->name;

        $notification->content = "New Meeting Request from Trainee: " . $trainee_name .
            "from " . $request->start_time . " to " . $request->end_time;

        $notification->user_id = $request->advisor_id;


        $meeting = new Meeting();
        $meeting->subject = $validatedData['subject'];
        $meeting->start_time = $validatedData['start_time'];
        $meeting->end_time = $validatedData['end_time'];
        $meeting->advisor_id = $validatedData['advisor_id'];
        $meeting->trainee_id = $trainee_id;
        $meeting->save($validatedData);
        $notification->save();
        return response()->json(['message' => 'Meeting created successfully', 'meeting' => $meeting], 201);
    }

    //    public function getMeetingsAdvisor() {
    //        $advisor_id = Auth::user()->advisor->id;
    //        $meetings = Meeting::where('advisor_id',$advisor_id)->get();
    //        return response()->json($meetings);
    //    }
    public function getMeetingsTrainee()
    {
        $trainee_id = Auth::user()->trainee->id;
        $meetings = Meeting::with('advisor')->where('trainee_id', $trainee_id)->get();

        return response()->json($meetings);
    }

    public function getMeetingsManager()
    {
        $meetings = Meeting::with(['trainee', 'advisor'])->get();
        return response()->json($meetings);
    }
}
