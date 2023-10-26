<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fname' => 'required',
                'lname' => 'required',
                'phone' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/users/1675332882.jpg';
        }
        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $image,
        ]);

        if ($request->role == 'driver') {

            $user->roles()->sync(3);
        } else {
            $user->roles()->sync(2);
        }
        $token = $user->createToken($request->email)->plainTextToken;
        return response()->json([
            'message' => "Added successfully.",
            'status' => "success.",
            'token' => $token,
            'data' => $user,
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = User::where('email', $request->email)->first();
        if ($user->is_active == 0) {
            return response()->json(['message' => 'Your user is not active'], 401);
        }
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'message' => 'Login successfully.',
                'status' => 'Success',
                'token' => $token,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email or password does`t match.',
                'status' => 'failed',
            ], 401);
        }
    }

    public function reset_password(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            $otp = random_int(1000, 9999);
            $token = Str::random(30);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'otp' => $otp,
            ]);
            Mail::to($request->email)->send(new ForgotPassword($otp));
            return response()->json([
                'message' => "OTP send in your Email.",
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                "message" => "You enter wrong email",
                "status" => "failed",
            ], 400);
        }
    }

    public function verify_code(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'otp' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $otp = $request->input('otp');
        $otpCode = DB::table('password_resets')->value('otp');
        if ($otpCode == $otp) {
            return response()->json([
                'message' => 'OTP match successfully.',
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid OTP.',
                'status' => 'failed',
            ], 401);
        }
    }


    public function change_password(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|confirmed',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $email = $request->input('email');
        $password = $request->input('password');

        $userEmail = User::where('email', $request->email)->first();
        if (!$userEmail) {
            return response()->json([
                'message' => 'Invalid Email.',
                'status' => 'failed',
            ], 401);
        }

        $otpEmail = DB::table('password_resets')->where('email', $request->email)->first();
        if ($otpEmail) {
            $userEmail->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where('email', $request->email)->delete();
            return response()->json([
                'message' => 'password reset successfully.',
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Wrong OTP',
                'status' => 'failed',
            ], 401);
        }
    }


    public function logout()
    {
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
            return response()->json([
                "message" => "Logout successfully.",
                "status" => "success",
            ], 200);
        } else {
            return response()->json([
                "message" => "Invalid token.",
                "status" => "failed",
            ], 400);
        }
    }
}
