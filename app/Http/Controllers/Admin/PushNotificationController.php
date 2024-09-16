<?php

namespace App\Http\Controllers\admin;

use App\Helpers\FcmNotificationHelper;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class PushNotificationController extends Controller
{
    public function notificationIndex()
    {
        $notifications = PushNotification::with('user')->orderBy('created_at', 'desc')
            ->get();
        return view('admin.notifications.index', compact('notifications'));
    }
    public function notificationCreate()
    {
        return view('admin.notifications.create');
    }
    public function notificationStore(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        $userRoles = $request->input('user_name');
        $users = User::whereIn('role_id', $userRoles)->get();
        foreach ($users as $user) {
            $fcmToken = $user->fcm_token;
            if (!is_null($fcmToken)) {
                FcmNotificationHelper::sendFcmNotification($fcmToken, $request->input('title'), $request->input('description'));
                PushNotification::create([
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'user_name' => $user->fname . ' ' . $user->lname,
                    'user_id' => $user->id,
                    'admin' => 'Admin', // Static value for admin
                ]);
            }
        }

        // Return response after successful operation
        return back()->with(['status' => true, 'message' => 'Notification Sent Successfully']);
    }
}
