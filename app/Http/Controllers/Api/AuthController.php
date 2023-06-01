<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advisor;
use App\Models\Manager;
use Exception;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use \App\Models\User;

class AuthController extends Controller
{
    public function registerManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = null;
        DB::beginTransaction();
        try {

            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'manager'
            ]);
            $user->save();
            $user = User::where('email', $request->email)->first();
            $user_id = $user->getAttribute('id');
            //create the manager row
            $manager = new Manager([
                'name' => $request->name,
                'email' => $request->email,
                'user_id' => $user_id,
            ]);
            $manager->save();
            DB::commit();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function registerAdvisor(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        DB::beginTransaction();
        try {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'advisor'
            ]);
            $user->save();
            $user = User::where('email', $request->email)->first();
            $user_id = $user->getAttribute('id');
            //create the advisor row
            $advisor = new Advisor([
                'name' => $request->name,
                'email' => $request->email,
                'user_id' => $user_id,
                'discipline_id' => $request->discipline_id
            ]);
            $advisor->save();
            DB::commit();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return  response()->json(["Error",401]);
        }

    }

    /*
    public function advisorManagerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        return $request;

        if (Auth::guard('advisor_manager')->attempt($credentials)) {
            $user = Auth::guard('advisor_manager')->user();
            $token = $user->createToken('advisor_manager')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password',
            ]);
        }
        return  response()->json("Hellloo");
    }
    */
    public function traineeLogin(Request $request)
    {
//        return $request;
        $trainee = User::where('trainee_id', $request->trainee_id)->first();

        if (!$trainee || !Hash::check($request->password, $trainee->password) || $trainee->role !== 'trainee') {
            throw ValidationException::withMessages([
                'trainee_id' => ['The provided credentials are incorrect.'],
            ]);
        }
        $traineeObj = Advisor::where('user_id',$trainee->id)->first();
        $traineeId = $traineeObj->id;
        return response()->json([
            'trainee' => $trainee,
            'trainee_id' => $traineeId,
            'token' => $trainee->createToken('mobile', ['role:trainee'])->plainTextToken
        ]);
    }

    public function advisorLogin(Request $request)
    {
        $advisor = User::where('email', $request->email)->first();
        if (!$advisor || !Hash::check($request->password, $advisor->password) || $advisor->role !== 'advisor') {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $advisorObj = Advisor::where('user_id',$advisor->id)->first();
        $advisorId = $advisorObj->id;
        return response()->json([
            'advisor' => $advisor,
            'advisor_id' => $advisorId,
            'token' => $advisor->createToken('mobile', ['role:advisor'])->plainTextToken
        ]);
    }
/*
 * "message": "Property [id] does not exist on the Eloquent builder instance.",
    "exception":"Exception",
 */
    public function managerLogin(Request $request)
    {
        $manager = User::where('email', $request->email)->first();
        if (!$manager || !Hash::check($request->password, $manager->password) || $manager->role !== 'manager') {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return response()->json([
            'manager' => $manager,
            'token' => $manager->createToken('mobile', ['role:manager'])->plainTextToken
        ]);
    }


    //to test it in postman
    /*
     * Send a POST request to your logout route or URL (e.g., /api/logout).
Make sure you include the necessary authentication headers, such as the Authorization header with the user's token.
Send the request and check the response. It should return a JSON response with the message "User logged out successfully."
     */
    public function logout($id)
    {
        $user = Auth::guard('sanctum')->user();
        $user->tokens()->findOrFail($id)->delete();
        return response()->json(['message' => 'User logged out successfully']);
    }
}
