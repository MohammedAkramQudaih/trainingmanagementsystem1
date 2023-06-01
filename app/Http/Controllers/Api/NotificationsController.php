<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    //
    public function store(Request $request)
    {
        $validedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);
        // Validation passed, create and save the notification
        $notification = new Notification();
        $notification->title = $validedData['title'];
        $notification->content = $validedData['content'];
        $notification->user_id = $validedData['user_id'];
        $notification->save();
        return response()->json(['message' => 'Notification Sent successfully'], 201);
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        return response()->json(['Notifications' => $notifications], 201);
    }

}
