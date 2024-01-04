<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    public function notificationIndex()
    {
        $notifications = PushNotification::with('user')->get();
        return view('admin.notifications.index', compact('notifications'));
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
        foreach ($users as $user) {
            $notification = new AdminNotification($request->input('title'), $request->input('description'));
            $user->user->notify($notification);
            PushNotification::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'user_name' => implode(', ', $userRoles),
                'user_id' => $user->user->id,
            ]);
        }
        return redirect()->route('notifications.index')->with('success', 'Notifications sent and data stored successfully');
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
