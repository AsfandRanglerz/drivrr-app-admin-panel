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
            if ($user->role_id == 1) {
                return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully!']);
            } else {
                Auth::logout();
                return redirect('/admin/login')->with(['status' => false, 'error' => 'Only Sub Admins Can Log In.']);
            }
        }

        return redirect('/admin/login')->with(['status' => false, 'error' => 'Invalid Email and Password!']);
    }
}
