<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advisor;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdvisorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $advisors = Advisor::withoutTrashed()->with('discipline')->get();
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

    public function getAdvisorInfo()
    {
        $user = auth()->user();
        if ($user->advisor) {
            $advisor = $user->advisor;
            return response()->json($advisor, 200);
        } else {
            return response()->json(['message' => 'User is not an advisor'], 403);
        }
    }

    public function acceptMeeting(Request $request, $meeting_id)
    {
        $notification = new Notification();

        $notification->title = "Meeting Request Reply";
        $meeting = Meeting::find($meeting_id);

        $notification->user_id = $meeting->trainee_id;

        $advisor = Advisor::find($meeting->trainee_id);
        $advisorName = $advisor->name;

        $status = $request->status;

        if ($status == 'Accepted') {
            $meeting->status = 'Accepted';
            $notification->content = "The Meeting start in " . $meeting->start_time . ', with the supervisor: ' . $advisorName;

        } else if ($status == 'Rejected') {
            $meeting->status = 'Rejected';
            $notification->content = 'Your Meeting Request has been Rejected in the ' .  $meeting->start_time;
        }
        $meeting->save();
        $notification->save();
        return response()->json(['message' => 'Meeting updated successfully', 'meeting' => $meeting], 200);

    }

    public function getMeetingsRequests($advisor_id)
    {
        $advisor = Advisor::find($advisor_id);

        if ($advisor) {
            $meetings = $advisor->meetings;
            return response()->json([
                'meetings' => $meetings],
                200);
        } else {
            return response()->json([
                'message' => 'No Advisor has an id = ' . $advisor_id],
                200);
        }


    }

    public function getAllPrograms()
    {
        $advisor_id = Auth::user()->advisor->id;
        $advisor = Advisor::find($advisor_id);
        $programs = $advisor->programs;
        return $programs;
    }

    public function getAllTraineesByProgram($program_id)
    {
        $program = Program::withoutTrashed()->find($program_id);
        $trainees = $program->trainees;
        return $trainees;
    }

    public function getAllTraineesByAvisorPograms()
    {
        /*
        $programs = Program::withoutTrashed()->get();
        for($programs as $program) {
            $trainees = $program->trainees;
            return $trainees;
        }
        $trainees = $program->trainees;
        return $trainees;
        */
        $advisor_id = Auth::user()->advisor->id;
        $trainees = DB::table('trainees')
            ->join('programs', 'trainees.program_id', '=', 'programs.id')
            ->where('programs.advisor_id', $advisor_id)
            ->select('trainees.*')
            ->get();
        return $trainees;

    }
}
