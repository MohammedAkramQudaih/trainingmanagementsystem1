<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\Notification;
use App\Models\Program;
use App\Models\TrainingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainingRequests = TrainingRequest::with('trainee', 'program')->get();
        return response()->json($trainingRequests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $trainee_id = Auth::user()->trainee->id;
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'trainee_qualifications' => 'required|string'
        ]);
        $program_field = Auth::user()->trainee->program_id;
        if($program_field) {
            return response()->json([
                'message' => 'You Are Already Joined to Program',
            ]);
        }

        //to Check if the Trainee Send request for the same
        $checkTrainingRequest = TrainingRequest::where('trainee_id', $trainee_id)
            ->where('program_id', $request->program_id)
            ->first();

        if ($checkTrainingRequest) {
            return response()->json([
                'message' => 'The Training Request to this Program Already Sent to the manager...',
                'Training Request' => $checkTrainingRequest
            ]);
        }

        $trainingRequest = new TrainingRequest();
        $trainingRequest->trainee_id = $trainee_id;
        $trainingRequest->trainee_qualifications = $request->trainee_qualifications;
        $trainingRequest->program_id = $request->program_id;
        $trainingRequest->save();

        $notification = new Notification();
        $notification->title = "New Training Request";

        $program = Program::find($request->program_id);
        $programName = $program->name;

        $notification->content = "New Training Request to the program: " . $programName;
        $managers = Manager::where('role','manager')->get();

        foreach ($managers as $manager) {
            $notification->user_id = $manager->id;
            $notification->save();
        }

        return response()->json([
            'message' => 'The Training Request send to the manager...'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $trainee_id)
    {
        $trainingRequests = TrainingRequest::with('program')->where('trainee_id', $trainee_id)->get();
        return response()->json($trainingRequests);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
