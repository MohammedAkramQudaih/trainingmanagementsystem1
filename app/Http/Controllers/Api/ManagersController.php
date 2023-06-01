<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advisor;
use App\Models\Manager;
use App\Models\Trainee;
use App\Models\TrainingRequest;
use App\Models\User;
use App\Notifications\AcceptedTraineeNotification;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

use Illuminate\Mail\MailMessage;

use App\Mail\TraineeAccepted;



class ManagersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $managers = Manager::withoutTrashed()->get();
        return $managers;
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:managers,email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);
        $managers = new Manager();
        $managers->name = $request->name;
        $managers->email = $request->email;
        $managers->phone_number = $request->phone_number;
        $managers->address = $request->address;
        $managers->save();
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
        return Manager::withoutTrashed()->find($id);
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
        $manager = Manager::withoutTrashed()->find($id);
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:managers,email|max:255',
            'phone_number' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:255',
        ]);
        $manager->update($request->all());
//        return $manager;
        return response()->json([
            'manager' => $manager,
            'message' => 'The Manager Successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manager = Manager::withoutTrashed()->find($id);
        $manager->delete();
        return response()->json(['message' => 'The Manager Successfully deleted']);
    }


/************************************************************************************************ MOHAMMED QUDAIH CODE */
public function acceptTrainee($id)
{
    $trainee = Trainee::withoutTrashed()->find($id);

    do {
        $number = random_int(1000000, 9999999);
    } while (Trainee::where("trainee_id", "=", $number)->first());
    $trainee->update(['trainee_id' => $number]);
    $trainee->update(['status' => 'Accepted']);

    $acceptedTrainee = new User();
    $acceptedTrainee->name = $trainee->name;
    $acceptedTrainee->email = $trainee->email;
    $acceptedTrainee->trainee_id = $number;
    //default password for new accepted trainee it's from 1 to 8
    $acceptedTrainee->password = '$2y$10$S0aoiOgiKM1wD2BuAoqKcenF8aWc.Vu3EwLdKsaVD3s13NLg8Yvi.';
    $acceptedTrainee->role = 'trainee';
    $acceptedTrainee->save();
    $traineeEmail = $trainee->email;

    $body = 'Dear Trainee,' . PHP_EOL . PHP_EOL .
        'Congratulations! You have been accepted in the Training management system.' . PHP_EOL .
        PHP_EOL . 'You can now log in and start using the system.' . PHP_EOL .
        PHP_EOL . 'You can use your Trainee ID to Log in: ' .
        PHP_EOL . 'Trainee ID to is: ' . $trainee->trainee_id .
//        PHP_EOL . 'and Default Password is: 12345678, please change it after first login.' . PHP_EOL .
        PHP_EOL . 'Thank you for using our system!';

    $emailContent = [
        'title' => 'Welcome to Training Management System',
        'body' => $body,
    ];

    // Send email
    Mail::raw($emailContent['body'], function ($message) use ($trainee) {
        $message->to($trainee->email)
            ->subject('Welcome to Training Management System');
    });


    $user_id = $acceptedTrainee->getAttribute('id');
    $trainee->update(['user_id' => $user_id]);
    return $acceptedTrainee;
}
    /****************************************************************************************** MOHAMMED QUDAIH CODE  */

    public function listAllTrainees()
    {
        $trainees = Trainee::withoutTrashed()->get();
        return $trainees;
    }

    public function listAllAdvisors()
    {
        $advisros = Advisor::withoutTrashed()->get();
        return $advisros;
    }

    public function getManagerInfo()
    {
        $user = auth()->user();
        if ($user->manager) {
            $manager = $user->manager;
            return response()->json($manager, 200);
        } else {
            return response()->json(['message' => 'User is not an Trainee'], 403);
        }
    }

    public function acceptTrainingRequest($trainee_id, $program_id, Request $request)
    {

//        $trainingRequest = TrainingRequest::find([$program_id,$trainee_id]);
        $trainingRequest = DB::table('training_requests')
            ->where('trainee_id', '=', $trainee_id)
            ->where('program_id', '=', $program_id)
            ->get();
//        return $trainingRequest;
        $status = $request->status;
//        return $status;
        if ($status == 'Rejected') {
            DB::table('training_requests')
                ->where('trainee_id', '=', $trainee_id)
                ->where('program_id', '=', $program_id)
                ->update(['status' => 'Rejected']);
        } else if ($status == 'Accepted') {
            DB::table('training_requests')
                ->where('trainee_id', '=', $trainee_id)
                ->where('program_id', '=', $program_id)
                ->update(['status' => 'Accepted']);
            $trainee = Trainee::find($trainee_id);
            $trainee->update(['program_id' => $program_id]);
        }
        return response()->json([
            'message' => $status . " Successfully"
        ]);
    }

    function getAdvisorsByDisciplinesId($discipline_id)
    {
        $advisors = Advisor::withoutTrashed()->where('discipline_id', $discipline_id)->get();
        return response()->json([
            'advisors' => $advisors
        ]);
    }
}

