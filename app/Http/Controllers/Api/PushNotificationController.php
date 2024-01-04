<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;

class PushNotificationController extends Controller
{
    public function userRecevied(Request $request, PushNotification $notification)
    {
        $notification->update(['seen_by' => 1]);
        return response()->json([
            'message' => $notification->message,
            'newNotifications' => PushNotification::where('seen_by', 0)->orderBy('created_at', 'desc')->get(),
        ], 200);
    }
}
