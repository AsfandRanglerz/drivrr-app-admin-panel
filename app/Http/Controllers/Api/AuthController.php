<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\DriverWallet;
use App\Models\RoleUser;
use App\Models\UserLoginWithOtp;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use App\Mail\LoginUserWithOtp;
use App\Mail\ActiveUserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Notification;
use App\Notifications\TestingNotification;
use App\Models\Admin;

class AuthController extends Controller
{
    public function register(Request $request, $id)
    {

        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('admin/assets/images/users/'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // } else {
        //     $image = 'public/admin/assets/images/users/1675332882.jpg';
        // }
        // $user = User::create([
        //     'fname' => $request->fname,
        //     'lname' => $request->lname,
        //     'phone' => $request->phone,
        //     'email' => $request->email,
        //     // 'password' => Hash::make($request->password),
        //     // 'image' => $image,
        // ]);

        if ($id == 3) {
            $validator = Validator::make(
                $request->all(),
                [
                    'fname' => 'required',
                    'lname' => 'required',
                    'phone' => 'required',
                    'email' => 'required|email|unique:users',
                    // 'password' => 'required|confirmed',
                ]
            );

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $image = 'public/admin/assets/images/users/owner.jpg';

            $user = User::create([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'phone' => $request->phone,
                'email' => $request->email,
                'image' => $image,
                'role_id' => 3,
            ]);
            $user->roles()->sync(3);
            Mail::to($user->email)->send(new ActiveUserStatus($id));
            // $admin = Admin::where('email', 'admin@gmail.com')->first();
            // $admin->notify(new TestingNotification($user));
            $wallet = DriverWallet::create([
                'driver_id'=> $user->id,
                'total_earning'=> 0,
            ]);
            // return  $wallet;
            // $otp = random_int(0000,9999);
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'message' => "Added successfully.",
                'status' => "success",
                'token' => $token,
                'data' =>  $user,
                'driver_wallet' =>  $wallet,
            ], 200);
        }
        else {
            $validator = Validator::make(
                $request->all(),
                [
                    'fname' => 'required',
                    'lname' => 'required',
                    'phone' => 'required',
                    'email' => 'required|email|unique:users',
                    'company_name' => 'required',
                    'company_info' => 'required',
                    // 'password' => 'required|confirmed',
                ]
            );
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $image = 'public/admin/assets/images/users/owner.jpg';
            $user = User::create([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'phone' => $request->phone,
                'email' => $request->email,
                'image' => $image,
                'company_name' => $request->company_name,
                'company_info' => $request->company_info,
                'role_id' => 2,
            ]);
            $user->roles()->sync(2);
            Mail::to($user->email)->send(new ActiveUserStatus($id));
            // $admin = Admin::where('email', 'admin@gmail.com')->first();
            // $admin->notify(new TestingNotification($user));
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'message' => "Added successfully.",
                'status' => "success",
                'token' => $token,
                'data' =>  $user,
                // 'data' => $user,
            ], 200);
        }

    }
    public function checkEmailExists(Request $request)
    {
        $email = $request->input('email');

        // Check if the email already exists in the users table
        $exists = User::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }


    ############OTP CODE END ###########################
    public function user_otp_login_send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 'failed'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $userId = $user->id;
        // return $userId;

        if (!$user) {
            return response()->json(['message' => 'The user is not registered.', 'status' => 'failed'], 401);
        }

        // Check the user's roleId and if the email matches for roleId 2
        if ($user->roles->where('id', 2)->count() > 0 && $user->email === $request->email) {
            // Continue with OTP generation and sending for roleId 2
            DB::table('user_login_with_otps')->where('email', $request->email)->delete();
            $login_otp = random_int(1000, 9999);
            $token = Str::random(30);
            DB::table('user_login_with_otps')->insert([
                'email' => $request->email,
                'token' => $token,
                'otp' => $login_otp,
                'user_id' => $userId,
            ]);

            Mail::to($request->email)->send(new LoginUserWithOtp($login_otp));
            return response()->json([
                'message' => 'Login OTP sent to your email successfully for roleId 2.',
                'status' => 'success',
                'roleId' => 2, // Include roleId in the response
                'id' => $userId,
            ], 200);
        } elseif ($user->roles->where('id', 3)->count() > 0 && $user->email === $request->email) {
            // Continue with OTP generation and sending for roleId 3
            DB::table('user_login_with_otps')->where('email', $request->email)->delete();
            $login_otp = random_int(1000, 9999);
            $token = Str::random(30);
            DB::table('user_login_with_otps')->insert([
                'email' => $request->email,
                'token' => $token,
                'otp' => $login_otp,
                'user_id' => $userId,
            ]);

            Mail::to($request->email)->send(new LoginUserWithOtp($login_otp));

            return response()->json([
                'message' => 'Login OTP sent to your email successfully for roleId 3.',
                'status' => 'success',
                'roleId' => 3, // Include roleId in the response
                'id' => $userId,
            ], 200);
        } else {
            return response()->json(['message' => 'You are not allowed to send OTP.', 'status' => 'failed'], 401);
        }
    }
    public function user_otp_login_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user_otp = DB::table('user_login_with_otps')->where('otp', $request->otp)->first();

        if (!$user_otp) {
            return response()->json([
                'message' => 'OTP verification failed.',
                'status' => 'Failed',
            ], 400);
        }

        $user_id = $user_otp->user_id;
        $user_data = User::with('roles')->find($user_id);

        if ($user_data) {
            $token = Str::random(30);
            $role_id = $user_data->roles->first()->pivot->role_id;
            $user_data['role_id'] = $role_id;

            return response()->json([
                'message' => 'OTP verify successfully.',
                'status' => 'success',
                'token' => $token,
                'data' => $user_data,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }
    }




    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success',
            'status' => 'success'
        ], 200);
    }

}
