<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DisciplinesController extends Controller
{
    public function index()
    {
        //
        $disciplines = Discipline::withTrashed()->get();
        return $disciplines;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $discipline = new Discipline();
        $discipline->name = $request->name;
        $discipline->slug = Str::slug($discipline->name);
        $discipline->description = $request->description;
        $discipline->save();
        return response()->json(['disciplines' => $discipline]);

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
        return Discipline::withoutTrashed()->find($id);
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
        $discipline = Discipline::withoutTrashed()->find($id);
        $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
        ]);

        if ($request->filled('name')) {
            $discipline->name = $request->input('name');
            $discipline->slug = Str::slug($discipline->name);
        }
        $discipline->save();
        return response()->json(['message'=>'Successfully discipline updated']);
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
        $discipline = Discipline::withTrashed()->find($id);;
        $discipline->delete();
        return response()->json(['message' => 'The Discipline deleted successfully']);
    }
}
