<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailNotificationsController extends Controller
{
    //
    public function send($Email)
    {
        Mail::to($Email)->send(new SendEmailNotification());
    }
}
