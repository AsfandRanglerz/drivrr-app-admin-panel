<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;

class PushNotificationController extends Controller
{
    // public function userRecevied(Request $request, PushNotification $notification)
    // {
    //     $notification->update(['seen_by' => 1]);
    //     return response()->json([
    //         'message' => $notification->message,
    //         'newNotifications' => PushNotification::where('seen_by', 0)->orderBy('created_at', 'desc')->get(),
    //     ], 200);
    // }
    // NotificationController.php
    public function getNotification(Request $request, $userId)
    {
        try {
            $notifications = PushNotification::where('user_id', $userId)->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Notifications retrieved successfully',
                'notifications' => $notifications,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function userRecevied(Request $request, $userId)
    {
        try {
            PushNotification::where('user_id', $userId)->update(['seen_by' => 1]);
            return response()->json([
                'status' => 'success',
                'message' => 'Notifications marked as seen',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
