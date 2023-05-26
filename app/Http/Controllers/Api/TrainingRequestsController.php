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
        //
        $trainingRequests = TrainingRequest::all();
        return response()->json([
                'Training Requests' =>
                    $trainingRequests,200
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
//        return $request;
        $request->validate([
//            'trainee_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
        ]);
        $trainingRequest = new TrainingRequest();
        $trainingRequest->trainee_id = Auth::user()->trainee->id;
        $trainingRequest->program_id = $request->program_id;
        $trainingRequest->save();
        return response()->json([
            'message' => 'The Training Request send to the manager...'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
