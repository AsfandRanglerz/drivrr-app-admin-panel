<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\admin;
use App\Models\Document;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\WithdrawalRequest;
use App\Notifications\NewUser;
use App\Models\Notification;
use Carbon\Carbon;

use App\Notifications\TestingNotification;

class AdminController extends Controller
{
    //
    public function getdashboard()
    {
        $data['users'] = User::all()->count();
        $data['subadmin']  = RoleUser::where('role_id', 1)->count();
        $data['owners']  = RoleUser::where('role_id', 2)->count();
        $data['drivers']  = RoleUser::where('role_id', 3)->count();
        $data['jobs']  = Job::where('is_active', 0)->count();
        $data['requests'] = WithdrawalRequest::where('status', 0)->count();
        // $jobs_count=Job::where('is_active', 1)->get();
        // dd($jobs_count);
        // dd($data);
        // return [$users,$owners,$drivers,$admins];
        return view('admin.index', compact('data'));
    }

    // public function notify()
    // {
    //     $user = User::find(); // Replace 1 with the actual user ID you want to notify
    //     auth()->guard('admin')->user()->notify(new NewUser($user));
    // }
    public function getProfile()
    {
        if (auth()->guard('web')->check()) {
            $user = User::find(auth()->guard('web')->id());
        } elseif (auth()->guard('admin')->check()) {
            $user = Admin::find(auth()->guard('admin')->id());
        } else {
            return redirect()->route('/admin');
        }
        return view('admin.auth.profile', compact('user'));
        // dd($data);
    }
    public function update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);
        $data = $request->only(['name', 'email', 'phone']);
        if (auth()->guard('web')->check()) {
            $user = User::find(auth()->guard('web')->id());
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('admin/assets/images/users/'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }
            $user->update($data);
        } elseif (auth()->guard('admin')->check()) {
            $admin = Admin::find(auth()->guard('admin')->id());
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('admin/assets/images/users/'), $filename);
                $data['image'] = 'public/admin/assets/images/users/' . $filename;
            }
            $admin->update($data);
        } else {
            return redirect()->route('admin.login')->with(['status' => false, 'message' => 'Unauthorized']);
        }

        return back()->with(['status' => true, 'message' => 'Profile Updated Successfully']);
    }

    public function forgetPassword()
    {
        return view('admin.auth.forgetPassword');
    }
    public function adminResetPasswordLink(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:admins,email',
        ]);
        $exists = DB::table('password_resets')->where('email', $request->email)->first();
        if ($exists) {
            return back()->with('message', 'Reset Password link has been already sent');
        } else {
            $token = Str::random(30);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);

            $data['url'] = url('change_password', $token);
            Mail::to($request->email)->send(new ResetPasswordMail($data));
            return back()->with('message', 'Reset Password Link Send Successfully');
        }
    }
    public function change_password($id)
    {

        $user = DB::table('password_resets')->where('token', $id)->first();

        if (isset($user)) {
            return view('admin.auth.chnagePassword', compact('user'));
        }
    }

    public function resetPassword(Request $request)
    {

        $request->validate([
            'password' => 'required|min:8',
            'confirmed' => 'required',

        ]);
        if ($request->password != $request->confirmed) {

            return back()->with(['error_message' => 'Password not matched']);
        }
        $password = bcrypt($request->password);
        $tags_data = [
            'password' => bcrypt($request->password)
        ];
        if (Admin::where('email', $request->email)->update($tags_data)) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('admin');
        }
    }
    public function logout()
    {
        if (auth()->guard('web')->check()) {
            Auth::guard('web')->logout();
            return redirect('/admin');
        } elseif (auth()->guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect('/admin');
        } else {
            return redirect('/admin');
        }
    }

    public function seen_notification()
    {
        // return Carbon::now('Asia/Karachi');
        $notifications = Notification::where('read_at', NULL)->get();
        foreach ($notifications as $notification) {
            $notification->update([
                'read_at' => Carbon::now(),
            ]);
        }
        return redirect()->back();
    }
}
