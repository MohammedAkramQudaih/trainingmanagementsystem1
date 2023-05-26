<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TraineeAttendance;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraineeAttendanceController extends Controller
{

    public function store(Request $request)
    {
        $trainee_id = Auth::user()->trainee->id;
        $validatedData = $request->validate([
            'date' => 'required|date',
            'attendance_status' => 'required|in:Present,Absent',
            'comments' => 'nullable|string',
        ]);
        $date = $validatedData['date'];
        $existingAttendance = TraineeAttendance::where('trainee_id', $trainee_id)
            ->whereDate('date', $date)
            ->first();

        if ($existingAttendance) {
            return response()->json(['message' => 'Attendance already submitted for today'], 422);
        }
        $attendance = new TraineeAttendance();
        $attendance->date = $date;
        $attendance->attendance_status = $validatedData['attendance_status'];
        $attendance->comments = $validatedData['comments'];
        $attendance->trainee_id = $trainee_id;
        $attendance->save();
        return response()->json([
            'message' => 'Filled Attendance Success'
        ]);
    }
}
