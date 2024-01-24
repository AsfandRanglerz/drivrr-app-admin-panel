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
    // public function notificationStore(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string',
    //         'user_name' => 'required|array',
    //         'user_name.*' => 'exists:roles,id',
    //         'description' => 'required|string',
    //     ]);
    //     $userRoles = $request->input('user_name');
    //     $users = RoleUser::whereIn('role_id', $userRoles)->get();
    //     foreach ($users as $user) {
    //         Notification::send($user->user, new AdminNotification($request->input('title'), $request->input('description')));
    //         PushNotification::create([
    //             'title' => $request->input('title'),
    //             'description' => $request->input('description'),
    //             'user_name' => $user->role->id,
    //             'user_id' => $user->user->id,
    //         ]);
    //     }
    //     return redirect()->back()->with('message', 'Notification Send Successfully');
    // }
    public function notificationStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'user_name' => 'required|array',
            'user_name.*' => 'exists:roles,id',
            'description' => 'required|string',
        ]);

        $userRoles = $request->input('user_name');
        $users = RoleUser::whereIn('role_id', $userRoles)->get();
        foreach ($users as $user) {
            $fcmToken = $user->user->fcm_token;
            FcmNotificationHelper::sendFcmNotification($fcmToken, $request->input('title'), $request->input('description'));
            PushNotification::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'user_name' => $user->role->id,
                'user_id' => $user->user->id,
                'admin' => 'Admin'
            ]);
        }
        return redirect()->back()->with('message', 'Notification Sent Successfully');
    }

    // public function notificationStore(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required',
    //         'select' => 'required|array',
    //         'description' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    //     $data = $request->select;
    //     $roles = [
    //         1 => ['SubAdmin', 1],
    //         2 => ['Business Owner', 2],
    //         3 => ['Driver', 3],
    //     ];
    // $notification = new AdminNotification($request->input('title'), $request->input('description'));
    // $user->user->notify($notification);
    //     foreach ($data as $selectedRole) {
    //         if (array_key_exists($selectedRole, $roles)) {
    //             list($userName, $roleId) = $roles[$selectedRole];
    //             $users = RoleUser::where('role_id', $roleId)->get();
    //             PushNotification::create([
    //                 'title' => $request->title,
    //                 'description' => $request->description,
    //                 'role_id' => $roleId,
    //                 'user_name' => $userName,
    //             ]);
    //         } else {
    //             return redirect()->back()->with(['status' => false, 'message' => 'You entered wrong data.']);
    //         }
    //     }

    //     return redirect()->route('notifications.index')->with('success', 'Sent Successfully.');
    // }
}
