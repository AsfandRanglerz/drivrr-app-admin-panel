<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;

class PushNotificationController extends Controller
{
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
            $notificationsWithStatusZero = PushNotification::where('user_id', $userId)
                ->where('seen_by', 0)
                ->get();
            PushNotification::where('user_id', $userId)
                ->where('seen_by', 0)
                ->update(['seen_by' => 1]);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifications marked as seen',
                'notificationsWithStatusZero' => $notificationsWithStatusZero,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve or update notifications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
