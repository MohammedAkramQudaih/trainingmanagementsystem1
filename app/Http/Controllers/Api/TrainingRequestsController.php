<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
//            'trainee_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
            'trainee_qualifications' => 'required|string'
        ]);

        $checkTrainingRequest = TrainingRequest::find($trainee_id)->where('program_id',$request->program_id);
        if($checkTrainingRequest) {
            return response()->json([
                'message' => 'The Training Request to this Program Already Sent to the manager...',
                'Trainging Request' => $checkTrainingRequest

            ]);
        }
        $trainingRequest = new TrainingRequest();
        $trainingRequest->trainee_id = $trainee_id;
        $trainingRequest->program_id = $request->program_id;
        $trainingRequest->save();
        return response()->json([
            'message' => 'The Training Request send to the manager...'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $trainee_id)
    {
        $trainingRequests = TrainingRequest::with('program')->where('trainee_id',$trainee_id)->get();
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
