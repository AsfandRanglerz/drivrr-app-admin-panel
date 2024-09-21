<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\User;
use App\Models\admin;
use App\Models\Document;
use App\Models\RoleUser;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Notifications\NewUser;
use App\Mail\PasswordResetMail;
use App\Mail\ResetPasswordMail;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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
        return view('admin.index', compact('data'));
    }

    public function getProfile()
    {
        if (auth()->guard('web')->check()) {
            $user = User::find(auth()->guard('web')->id());
        } elseif (auth()->guard('admin')->check()) {
            $user = Admin::find(auth()->guard('admin')->id());
        } else {
            return redirect()->route('/admin-login');
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
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'exists_in_users_or_admins' // Custom validation rule
            ],
        ], [
            'email.exists_in_users_or_admins' => 'This email does not exist.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // If validation passes, continue with sending the reset link
        $token = Str::random(30);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
        ]);
        if ($token) {
            $data['url'] = url('change_password', $token);
            Mail::to($request->email)->send(new PasswordResetMail($data));
            return back()->with(['status' => true, 'message' => 'Password Reset Link Set Succcessfully!']);
        } else {
            return back()->with(['status' => false,  'error' => 'Reset Password Link Not Sent']);
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
            'confirmed' => 'required|same:password',
        ]);

        $password = bcrypt($request->password);

        $user = User::where('email', $request->email)->first();
        $admin = Admin::where('email', $request->email)->first();

        if ($user) {
            $user->update(['password' => $password]);
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('admin-login')->with(['message' => 'Password reset successfully']);
        } elseif ($admin) {
            $admin->update(['password' => $password]);
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('admin-login')->with(['message' => 'Password reset successfully']);
        }

        return back()->with(['error' => 'Invalid email or user not found']);
    }
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin-login')->with(['status' => true, 'message' => 'Log Out Successfully']);
    }
}
