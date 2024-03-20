<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        if (auth()->guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
        }

        if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
        }

        return back()->with('err_message', 'Invalid email or password');
    }
}




    // $remember_me = ($request->remember_me) ? true : false;
    // if (!auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
    //     return back()->with('err_message', 'Invalid email or password');
    // }
