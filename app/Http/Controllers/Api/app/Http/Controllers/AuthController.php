<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->role === 'recruiter') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'company' => 'required',
                'designation' => 'required',
                'email' => 'required|unique:users,email|email',
                'phone' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $recruter_data = $request->only(['name', 'email', 'phone', 'password', 'role', 'company', 'designation']);
            $recruter_data['password'] = Hash::make($request->password);
            $user = User::create($recruter_data);
            $user['token'] = $user->createToken('znjToken')->plainTextToken;
            return $this->sendSuccess('Recruter Register Successfully', $user);
        } elseif ($request->role === 'entertainer') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email|email',
                'phone' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $entertainer_data = [
                'name' => $request->name,
                'role' => $request->role,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ];
            $entertainer_data = $request->only(['name', 'email', 'role', 'phone', 'password']);
            $entertainer_data['password'] = Hash::make($request->password);
            $user = User::create($entertainer_data);
            $user['token'] = $user->createToken('znjToken')->plainTextToken;
            return $this->sendSuccess('Entertainer Register Successfully', $user);
        } elseif ($request->role === 'venue') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email|email',
                'venue' => 'required',
                'phone' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $venue_data = $request->only(['name', 'email', 'phone', 'password', 'role', 'venue']);
            $venue_data['password'] = Hash::make($request->password);
            $user = User::create($venue_data);
            $user['token'] = $user->createToken('authToken')->plainTextToken;
            return $this->sendSuccess('Venue Register Successfully', $user);
        } else {
            return $this->sendError('Role Is Invalid');
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return  $this->sendError('Invalid email or password');
        }
        $user = User::find(auth()->id());
        $user['token'] = $user->createToken('authToken')->plainTextToken;
        return $this->sendSuccess('Login Successfully', $user);
    }
    // public function logout()
    // {
    //     Auth::logout();


    //     DB::table('personal_access_tokens')->where(['tokenable_id' => Auth::id()])->delete();

    //     return $this->sendSuccess('User Logout Successfully', Auth::id());
    // }
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        } else if (!$user) {
            return $this->sendError('Email Does Not Exist');
        } else {
            try {
                $token = random_int(1000, 9999);
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                Mail::to('usmanarshad03316@gmail.com')->send(new ResetPasswordUser($token));
                return $this->sendSuccess('Email Sent Successfully Successfully', ['email' => $user->email]);
            } catch (\Throwable $th) {
                return $this->sendError('Something Went Wrong');
            }
        }
    }
    public function confirmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $token_data = DB::table('password_resets')->where('token', $request->token)->where('email', $request->email)->first();
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        } else if (!$token_data) {
            return $this->sendError('Token Invalid or Expired or Email not exist');
        } else {
            DB::table('password_resets')->where('token', $request->token)->where('email', $request->email)->delete();
            return $this->sendSuccess('Token Confirmed Successfully', ['email' => $token_data->email]);
        }
    }
    public function submitResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        } else if (!$user) {
            return $this->sendError('Email not exist');
        } else {
            return $this->sendSuccess('Reset Password Updated Successfully');
        }
    }
    public function userSocialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_id' => 'required',
            'login_type' => 'required',
            'name' => 'required',
            'notification_token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $login_type = $request->login_type;
        if ($request->has('email') && !empty($request->email)) {
            $find_user = User::where('email', $request->email)->first();
            if ($find_user) {
                if (empty($find_user->name)) {
                    $find_user->name = $request->name;
                }
                if ($login_type == "facebook") {
                    $find_user->facebook_social_id = $request->social_id;
                    if ($request->has('image')) {
                        $find_user->image = $request->image;
                    }
                }
                if ($login_type == "google") {
                    $find_user->google_social_id = $request->social_id;
                    if ($request->has('image')) {
                        $find_user->image = $request->image;
                    }
                }
                $find_user->save();
                $user = User::where('id', $find_user->id)->first();
                $accessToken = $find_user->createToken('authToken')->accessToken;
                $user['accessToken'] = $accessToken;
                $this->updateFcmToken($user->id, $request->notification_token);
                return $this->sendSuccess('Login Successfully', $user);
            } else {
                $validator = Validator::make($request->all(), [
                    'role' => 'required',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first());
                }
                $user = new User();
                $user->name = $request->name;
                $user->type = $request->type;
                if ($login_type == "facebook") {
                    if ($request->has('email') && !empty($request->email)) {
                        $user->email = $request->email;
                    }
                    if ($request->has('phone') && !empty($request->phone)) {
                        $user->phone = $request->phone;
                    }
                    if ($request->has('image')) {
                        $user->image = $request->image;
                    }
                    $user->facebook_social_id = $request->social_id;
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $user['accessToken'] = $accessToken;
                    $this->updateFcmToken($user->id, $request->notification_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
                if ($login_type == "google") {
                    if ($request->has('email') && !empty($request->email)) {
                        $user->email = $request->email;
                    }
                    if ($request->has('phone') && !empty($request->phone)) {
                        $user->phone = $request->phone;
                    }
                    if ($request->has('image')) {
                        $user->image = $request->image;
                    }
                    $user->google_social_id = $request->social_id;
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $user['accessToken'] = $accessToken;
                    $this->updateFcmToken($user->id, $request->notification_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
            }
        } else {
            $user = User::where('facebook_social_id', $request->social_id)->orwhere('google_social_id', $request->social_id)->first();
            if ($user) {
                $user->save();
                $user = User::where('id', $user->id)->first();
                $accessToken = $user->createToken('authToken')->accessToken;
                $user['accessToken'] = $accessToken;
                $this->updateFcmToken($user->id, $request->notification_token);
                return $this->sendSuccess('Login Successfully', $user);
            } else {
                $validator = Validator::make($request->all(), [
                    'role' => 'required',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first());
                }
                $user = new User();
                $user->name = $request->name;
                $user->role = $request->role;
                if ($request->has('phone')) {
                    $user->phone = $request->phone;
                }
                if ($login_type == "facebook") {
                    if ($request->has('image')) {
                        $user->image = $request->image;
                    }
                    $user->facebook_social_id = $request->social_id;
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $user['accessToken'] = $accessToken;
                    $this->updateFcmToken($user->id, $request->notification_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
                if ($login_type == "google") {
                    if ($request->has('image')) {
                        $user->image = $request->image;
                    }
                    $user->google_social_id = $request->social_id;
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $user['accessToken'] = $accessToken;
                    $this->updateFcmToken($user->id, $request->notification_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
            }
        }
    }
    public function userLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data = $request->only(['longitude', 'latitude']);
        User::find(auth()->id())->update($data);
        return $this->sendSuccess('Location Saved Successfully');
    }
    protected function updateFcmToken($userId, $token)
    {
        return User::where('id', $userId)->update(['notification_token' => $token]);
    }
    public function editProfile()
    {
        $user = User::find(auth()->id());
        return $this->sendSuccess('User data sent  successfully', compact('user'));
    }
    public  function updateProfile(Request $request)
    {
        $data = User::where($request->id, Auth::id())->first();
        if ($data->role === 'entertainer') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $entertainer_data = $request->only(['name', 'email', 'phone']);
            $user = User::find($request->id)->update($entertainer_data);
            $data = User::find($request->id);
            return $this->sendSuccess('Entertainer updated Successfully', compact('data'));
        } elseif ($data->role === 'venue') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'venue' => 'required',
                'phone' => 'required',

            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $venue_data = $request->only(['name', 'email', 'phone', 'venue']);
            // $user = User::create($venue_data);
            $user = User::find($request->id)->update($venue_data);
            $data = User::find($request->id);
            return $this->sendSuccess('Venue updated Successfully', $data);
        } elseif ($data->role === 'recruiter') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'company' => 'required',
                'designation' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $recruter_data = $request->only(['name', 'email', 'phone', 'company', 'designation']);
            $user = User::find($request->id)->update($recruter_data);
            $data = User::find($request->id);
            return $this->sendSuccess('Recruiter updated Successfully', $data);
        }
    }
}
