<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLoginPage()
    {

        return view('admin.auth.login');
    }
    // public function Login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     if (auth()->guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
    //         return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //     }

    //     if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
    //         return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //     }

    //     return back()->with('err_message', 'Invalid email or password');
    // }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully!']);
        }

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();

            if ($user->role_id !== 1) {
                Auth::logout();
                return redirect('/admin-login')->with(['status' => false, 'message' => 'Only Subadmins Can LogIn.']);
            }

            return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully!']);
        }

        return redirect('/admin/login')->with(['status' => false, 'message' => 'Invalid Email and Password!']);
    }
}
