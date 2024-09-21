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
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Check if admin guard login
        if (Auth::guard('admin')->attempt($credentials)) {
            // Regenerate session for security
            $request->session()->regenerate();
            return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Admin Login Successfully!']);
        }

        // Check if web guard login (sub-admins)
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();

            // Check if user has sub-admin role (role_id 1)
            if ($user->role_id == 1) {
                // Regenerate session for security
                $request->session()->regenerate();
                return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Sub Admin Login Successfully!']);
            } else {
                // Logout if user is not a sub-admin
                Auth::logout();
                return redirect('/admin-login')->with(['status' => false, 'error' => 'Only Sub Admins Can Log In.']);
            }
        }

        // Invalid credentials
        return redirect('/admin-login')->with(['status' => false, 'error' => 'Invalid Email and Password!']);
    }
}
