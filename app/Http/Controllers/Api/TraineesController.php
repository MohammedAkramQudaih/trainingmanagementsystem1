<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainee;
use Illuminate\Http\Request;

class TraineesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainees = Trainee::withoutTrashed()->get();
        return $trainees;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //GET /api/projects -> index
//POST /api/projects -> store
//GET /api/projects/{project_id} -> show
//PUT|PATCH /api/projects/{project_id} -> update
//DELETE /api/projects/{project_id} -> destroy


    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['store']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:trainees,email',
            'phone_number' => 'sometimes|string',
            'password' => 'required|string',
            'address' => 'sometimes|string',
            'university_name' => 'sometimes|string',
            'university_id' => 'sometimes|string|unique:trainees,university_id',
            'gender' => 'required|in:male,female',
            'trainee_id' => 'nullable|string|unique:trainees,trainee_id',
            'bio' => 'sometimes|string',
        ]);
        $trainee = new Trainee;
        $trainee->name = $request->name;
        $trainee->email = $request->email;
        $trainee->phone_number = $request->phone_number;
        $trainee->address = $request->address;
        $trainee->university_name = $request->university_name;
        $trainee->university_id = $request->university_id;
        $trainee->gender = $request->gender;
        $trainee->status = $request->status;
        $trainee->trainee_id = null;
        $trainee->bio = $request->bio;
        $trainee->save();

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Trainee::withoutTrashed()->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //the update request in API is differing from HTTP, we will make the fields required only when send it in API
        // we can use 'sometimes rule
        $trainee = Trainee::withoutTrashed()->find($id);
        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:trainees,email',
            'phone_number' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'password' => 'sometimes|string',
            'university_name' => 'sometimes|required|string',
            'university_id' => 'sometimes|required|string|unique:trainees,university_id',
            'gender' => 'sometimes|required|in:male,female',
            'status' => 'sometimes|required|in:Suspended,Accepted',
            'trainee_id' => 'nullable|string|unique:trainees,trainee_id',
            'bio' => 'sometimes|required|string',
        ]);

        $trainee->update($request->all());
        return $trainee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $trainee = Trainee::withoutTrashed()->find($id);
        $trainee->delete();
    }

    public function getTraineeInfo()
    {
        $user = auth()->user();
        if ($user->trainee) {
            $trainee = $user->trainee;
            return response()->json($trainee, 200);
        } else {
            return response()->json(['message' => 'User is not an Trainee'], 403);
        }
    }
}
