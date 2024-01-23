<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;

class PushNotificationController extends Controller
{
    public function getNotificationCount($userId)
    {
        try {
            $notificationCount = PushNotification::where('user_id', $userId)
                ->where('seen_by', 0)
                ->count();

            return response()->json([
                'status' => 'success',
                'notificationCount' => $notificationCount,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch notification count',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function userRecevied(Request $request, $userId)
    {
        try {
            // Update notifications
            PushNotification::where('user_id', $userId)
                ->where('seen_by', 0)
                ->update(['seen_by' => 1]);

            $updatedNotifications = PushNotification::where('user_id', $userId)
                ->with(['user.user_name'])
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Notifications marked as seen',
                'notifications' => $updatedNotifications,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update or retrieve notifications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
