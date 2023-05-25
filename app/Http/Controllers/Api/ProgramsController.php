<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //
        $programs = Program::withoutTrashed()->get();
        return $programs;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'discipline_id' => 'required|exists:disciplines,id',
            'logo' => 'nullable|string',
            ' ' => 'required|exists:advisors,id',
            'capacity' => 'required|integer',
            'company' => 'required|string',
        ]);
        $program = Program::create($request->all());
//        return $program;
        return response()->json($program, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Program::withoutTrashed()->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $program = Program::withoutTrashed()->find($id);
        $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'discipline_id' => 'sometimes|required|exists:disciplines,id',
            'logo' => 'nullable|string',
            'advisor_id' => 'sometimes|required|exists:advisors,id',
            'capacity' => 'sometimes|required|integer',
            'company' => 'sometimes|required|string',
        ]);

        $program->update($request->all());
        return response()->json(
            ['program' => $program, 'message' => 'The Program Updated Successfully']
            , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $program = Program::withoutTrashed()->find($id);
        $program->delete();
        return response()->json(['message' => 'The Program Successfully deleted']);
    }
}
