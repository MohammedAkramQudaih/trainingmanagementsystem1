<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthTokensController extends Controller
{
    //
//    public  function store(Request $request) {
//        $request->validate([
//            'email' => 'sometimes|required',
//            'trainee_id' => 'sometimes|required',
//            'password' => 'required'
//        ]);
////        Auth::validate($request->only('trainee_id','email','password'));
//        $user = null;
//        if($request->email) {
//            $user = User::where('email','=',$request->email)->first();
//        }else if($request->trainee_id) {
//            $user = User::where('trainee_id','=',$request->trainee_id)->first();
//        }
//        if($user &&  Hash::check($request->password,$user->password)) {
//            //take name of token: every token created for the user take a name,
//            //the goal: 1. allow to one user to take multiple tokens(used when the user login from multiple devices),
//            //2.
//            //can send permession of token not user
//            $user->createToken($request->);
//        }
//    }
}
