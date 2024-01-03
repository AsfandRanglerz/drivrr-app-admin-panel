<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    public function notificationIndex()
    {
        return view('admin.notifications.index');
    }
    public function notificationCreate()
    {
        return view('admin.notifications.create');
    }
    public function notificationStore(Request $request)
    {

        $request->validate([
            'title' => 'required|string',
            'user_name' => 'required|array',
            'user_name.*' => 'exists:role_user,role_id',
            'description' => 'required|string',
        ]);
        $userRoles = $request->input('user_name');
        $users = RoleUser::whereHas('role', function ($query) use ($userRoles) {
            $query->whereIn('role_id', $userRoles);
        })->get();
        return $users;
        foreach ($users as $userRole) {
            $user = $userRole->user;
            $notification = new AdminNotification($request->input('title'), $request->input('description'));
            $user->notify($notification);
        }
        return redirect()->route('notifications.index')->with('success', 'Notification sent successfully');
    }
}
