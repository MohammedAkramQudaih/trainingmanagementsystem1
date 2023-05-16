<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advisor;
use Illuminate\Http\Request;

class AdvisorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $advisors = Advisor::withoutTrashed()->get();
        return $advisors;
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
            'email' => 'required|email|unique:advisors,email',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|in:male,female',
            'discipline_id' => 'required|exists:disciplines,id'
        ]);
        $advisor = new Advisor();
        $advisor->name = $request->name;
        $advisor->email = $request->email;
        $advisor->phone_number = $request->phone_number;
        $advisor->address = $request->address;
        $advisor->gender = $request->gender;
        $advisor->discipline_id = $request->discipline_id;
        $advisor->save();
        return response()->json([
            'advisor' => $advisor,
            'message' => 'The Advisor created Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        // we can specify specific col to return from relation
        //return Advisor::with('Discipline:id,name')->find($id);
        return Advisor::with('Discipline')->find($id);
    }

    /*
     //we can use this method inseated of the above
    //we pass the model and the laravel specify the id,
    // and use load method to pass several relationships

    public function show(Advisor $advisor)
    {
        //
        //return Advisor::with('Discipline')->find($id);

        return $advisor->load(['Discipline']);
    }
*/
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $advisor = Advisor::withoutTrashed()->find($id);
        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:advisors,email',
            'phone_number' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'gender' => 'sometimes|required|in:male,female',
            'discipline_id' => 'sometimes|required|exists:disciplines,id'
        ]);
        $advisor->update($request->all());
        return response()->json([
            'advisor' => $advisor,
            'message' => 'The Advisor updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advisor = Advisor::withoutTrashed()->find($id);
        $advisor->delete();
        return response()->json([
            'message' => 'The Advisor deleted Successfully'
        ]);
    }
}
