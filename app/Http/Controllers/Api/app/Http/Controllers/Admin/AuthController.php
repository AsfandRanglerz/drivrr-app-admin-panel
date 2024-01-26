<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getLoginPage()
    {
        return view('admin.auth.login');
    }
    public function Login(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $remember_me = ($request->remember_me) ? true : false;
        if (!auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
            return back()->with('err_message', 'Invalid email or password');
        }
        return redirect('admin/dashboard');
    }
}
