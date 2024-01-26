<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\DeleteAccount;
use App\Mail\Registration;
use App\Mail\ResetPasswordUser;
use App\Models\Admin;
use App\Models\Venue;
use App\Models\Event;
use App\Models\EntertainerDetail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        if ($request->role === 'recruiter') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email|email',
                // 'phone' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $recruter_data = $request->only(['name', 'email', 'phone', 'password', 'role', 'company', 'designation']);
            $recruter_data['password'] = Hash::make($request->password);
            $user = User::create($recruter_data);
            $user['token'] = $user->createToken('znjToken')->plainTextToken;
            Mail::to($request->email)->send(new Registration($user));
            return $this->sendSuccess('Recruter Register Successfully', $user);
        } elseif ($request->role === 'entertainer') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'role' => 'required',
                'email' => 'required|unique:users,email|email',
                // 'phone' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                // 'nationality' => 'required',
                // 'gender' => 'required',
                // 'city' => 'required',
                // 'country' => 'required',
                // 'dob' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $entertainer_data = [
                'name' => $request->name,
                'role' => $request->role,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'nationality' => $request->nationality,
                'gender' => $request->gender,
                'city' => $request->city,
                'country' => $request->country,
                'dob' => $request->dob,
            ];
            // $entertainer_data = $request->only(['name', 'email', 'role', 'phone','nationality','gender','city','country','dob','password']);
            // $entertainer_data['password'] = Hash::make($request->password);
            $user = User::create($entertainer_data);
            $user['token'] = $user->createToken('znjToken')->plainTextToken;
            Mail::to($request->email)->send(new Registration($user));
            return $this->sendSuccess('Entertainer Register Successfully', $user);
        } elseif ($request->role === 'venue_provider') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email|email',
                // 'phone' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $venue_data = $request->only(['name', 'email', 'phone', 'password', 'role', 'venue_provider']);
            $venue_data['password'] = Hash::make($request->password);
            $user = User::create($venue_data);
            $user['token'] = $user->createToken('znjToken')->plainTextToken;
            Mail::to($request->email)->send(new Registration($user));
            return $this->sendSuccess('Venue Register Successfully', $user);
        } else {
            return $this->sendError('Role Is Invalid');
        }
    }
    // Login Users
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password, 'role' => $request->role])) {
            return $this->sendError('Invalid email or password');
        }
        if (isset($request->fcm_token)) {
            User::find(auth()->id())->update([
                'fcm_token' => $request->fcm_token,
            ]);
        }
        $user = User::find(auth()->id());
        $user['token'] = $user->createToken('znjToken')->plainTextToken;
        return $this->sendSuccess('Login Successfully', $user);
    }
    // Logout users
    public function logout(Request $request)
    {
        $data = User::find(Auth::id());
        $data->fcm_token = null;
        $data->save();
        DB::table('personal_access_tokens')->where(['tokenable_id' => Auth::id()])->delete();
        return $this->sendSuccess('User Logout Successfully');
    }
    // forget Password
    public function forgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = User::where('email', $request->email)->where('role', $request->role)->first();
        if (isset($user)) {
            $email = DB::table('password_resets')->where('email', $request->email)->delete();
            $email = DB::table('password_resets')->where('email', $request->email)->first();
            if ($email) {
                return back()->with('message', 'Otp  has been already sent');
            } else {
                $token = random_int(100000, 999999);
                $token = Str::random(30);
                $otp = random_int(1000, 9999);
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'otp' => $otp,
                    'created_at' => Carbon::now(),
                ]);
                $data['otp'] = $otp;
                Mail::to($request->email)->send(new ResetPasswordUser($data));
                return $this->sendSuccess('Email Sent Successfully', ['email' => $request->email]);
            }
        }
        return $this->sendError('Email does not exist');
    }
    // Confirm Token
    public function confirmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        // $second = Carbon::now()->subSecond(30);
        // DB::table('password_resets')->where('created_at', '<', $second)->delete();
        $token_data = DB::table('password_resets')->where('otp', $request->otp)->where('email', $request->email)->first();
        if (isset($token_data)) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return $this->sendSuccess('Otp Confirmed Successfully', ['email' => $token_data->email]);
        } else {
            return $this->sendError('Otp Invalid');
        }
    }
    // Submit Reset Password
    public function submitResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        if (isset($user)) {
            // $second = Carbon::now()->subSecond(30);
            // DB::table('password_resets')->where('created_at', '<', $second)->delete();

            return $this->sendSuccess('Reset Password Updated Successfully');
        } else {
            return $this->sendError('Email not exist');
        }
    }
    // User Social Login
    public function userSocialLogin(Request $request)
    {
        $apple_id = User::where('apple_social_id', $request->social_id)->first();
        if (isset($apple_id)) {
            $validator = Validator::make($request->all(), [
                'social_id' => 'required',
                'login_type' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'social_id' => 'required',
                'login_type' => 'required',
                'name' => 'required',
                'email' => 'required',
                // 'fcm_token' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $login_type = $request->login_type;
        if ($request->has('email') && !empty($request->email)) {
            $find_user = User::where('email', $request->email)->where('role', $request->role)->first();
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
                if ($login_type == "apple") {
                    $find_user->apple_social_id = $request->social_id;
                    if ($request->has('image')) {
                        $find_user->image = $request->image;
                    }
                }
                $find_user->save();
                $user = User::where('id', $find_user->id)->first();
                $user['token'] = $find_user->createToken('znjToken')->plainTextToken;
                $this->updateFcmToken($user->id, $request->fcm_token);
                return $this->sendSuccess('Login Successfully', $user);
            } else {
                $validator = Validator::make($request->all(), [
                    'role' => 'required',
                    'email' => 'required|unique:users,email|email',
                ]);
                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first());
                }
                $user = new User();
                $user->name = $request->name;
                $user->role = $request->role;
                // $user->login_type = $request->login_type;
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
                    $user['token'] = $user->createToken('znjToken')->plainTextToken;
                    $this->updateFcmToken($user->id, $request->fcm_token);
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
                    // $accessToken = $user->createToken('znjToken')->accessToken;
                    // $user['accessToken'] = $accessToken;
                    $user['token'] = $user->createToken('znjToken')->plainTextToken;
                    $this->updateFcmToken($user->id, $request->fcm_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
                if ($login_type == "apple") {
                    if ($request->has('email') && !empty($request->email)) {
                        $user->email = $request->email;
                    }
                    if ($request->has('phone') && !empty($request->phone)) {
                        $user->phone = $request->phone;
                    }
                    if ($request->has('image')) {
                        $user->image = $request->image;
                    }
                    $user->apple_social_id = $request->social_id;
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $user['token'] = $user->createToken('znjToken')->plainTextToken;
                    $this->updateFcmToken($user->id, $request->fcm_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
            }
        } else {
            $user = User::where('facebook_social_id', $request->social_id)->orwhere('google_social_id', $request->social_id)->orwhere('apple_social_id', $request->social_id)->first();
            if ($user) {
                if ($user->role == $request->role) {
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $user['token'] = $user->createToken('znjToken')->plainTextToken;
                    $this->updateFcmToken($user->id, $request->fcm_token);
                    return $this->sendSuccess('Login Successfully', $user);
                } else {
                    return $this->sendError('Email has already been taken');
                }

                // $user->save();
                // $user = User::where('id', $user->id)->first();
                // $user['token'] = $user->createToken('znjToken')->plainTextToken;
                // $this->updateFcmToken($user->id, $request->fcm_token);
                // return $this->sendSuccess('Login Successfully', $user);
            } else {
                $validator = Validator::make($request->all(), [
                    'role' => 'required',
                    'email' => 'required|unique:users,email|email',
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
                    $user['token'] = $user->createToken('znjToken')->plainTextToken;
                    $this->updateFcmToken($user->id, $request->fcm_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
                if ($login_type == "google") {
                    if ($request->has('image')) {
                        $user->image = $request->image;
                    }
                    $user->google_social_id = $request->social_id;
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $user['token'] = $user->createToken('znjToken')->plainTextToken;
                    $this->updateFcmToken($user->id, $request->fcm_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
                if ($login_type == "apple") {
                    if ($request->has('image')) {
                        $user->image = $request->image;
                    }
                    $user->apple_social_id = $request->social_id;
                    $user->save();
                    $user = User::where('id', $user->id)->first();
                    $user['token'] = $user->createToken('znjToken')->plainTextToken;
                    $this->updateFcmToken($user->id, $request->fcm_token);
                    return $this->sendSuccess('Login Successfully', $user);
                }
            }
        }
    }
    // User Location
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
    // Update Fcm Token
    protected function updateFcmToken($userId, $token)
    {
        return User::where('id', $userId)->update(['fcm_token' => $token]);
    }
    // Edit Profile
    public function editProfile()
    {
        $user = User::find(auth()->id());
        return $this->sendSuccess('User data sent  successfully', compact('user'));
    }
    // update Profile
    public function updateProfile(Request $request)
    {
        $data = User::find(Auth::id());
        if ($data->role == 'entertainer') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                // 'phone' => 'required',
                // 'nationality' => 'required',
                // 'gender' => 'required|in:male,female',
                // 'city' => 'required',
                // 'country' => 'required',
                // 'dob' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $entertainer_data = $request->only(['name', 'phone', 'nationality', 'gender', 'city', 'country', 'dob']);
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename = time() . '.' . $extension;
                $file->move(public_path('images'), $filename);
                $entertainer_data['image'] = 'public/images/' . $filename;
            }
            $user = User::find(Auth::id())->update($entertainer_data);
            $data = User::find(Auth::id());
            return $this->sendSuccess('Entertainer updated Successfully', compact('data'));
        } elseif ($data->role === 'venue_provider') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                // 'phone' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $venue_data = $request->only(['name', 'email', 'phone', 'venue_provider']);
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename = time() . '.' . $extension;
                $file->move(public_path('images'), $filename);
                $venue_data['image'] = 'public/images/' . $filename;
            }
            $user = User::find(Auth::id())->update($venue_data);
            $data = User::find(Auth::id());
            return $this->sendSuccess('Venue updated Successfully', compact('data'));
        } elseif ($data->role === 'recruiter') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                // 'phone' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $recruter_data = $request->only(['name', 'email', 'phone', 'company', 'designation']);
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename = time() . '.' . $extension;
                $file->move(public_path('images'), $filename);
                $recruter_data['image'] = 'public/images/' . $filename;
            }
            $user = User::find(Auth::id())->update($recruter_data);
            $data = User::find(Auth::id());
            return $this->sendSuccess('Recruiter updated Successfully', compact('data'));
        }
    }
    // Update Password
    public function updatePassword(Request $request)
    {
        $id = Auth::user()->id;
        $password = Auth::user()->password;
        if ((Hash::check($request->old_password, Auth::user()->password))) {
            $user = User::find($id)
                ->update(['password' => Hash::make($request->new_password)]);
            return $this->sendSuccess('Password updated successfully');
        } else {
            return $this->sendError('Incorrect old password');
        }
    }
    // location
    public function location(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data = User::find(Auth::id());
        $data->address = $request->address;
        $data->longitude = $request->longitude;
        $data->latitude = $request->latitude;
        $data->save();
        return $this->sendSuccess('location updated successfully', compact('data'));
    }
    public function deleteAccountRequest()
    {

        $data = User::find(Auth::id());
        $data->delete_request = 1;
        $data->save();
        Mail::to($data->email)->send(new DeleteAccount($data));
        return $this->sendSuccess('Account deletion request successfully');
    }
    public function is_verify()
    {
        $data = Auth::user();
        return $this->sendSuccess('verify data', compact('data'));
    }
    //Event Delete Account
    public function eventDeleteAccountRequest(Request $request, $id)
    {
        $admin = Admin::first();
        $data = User::find(Auth::id());
        $event = Event::where('user_id', $data->id)->find($id);
        $venue_notify = Venue::with('User', 'venueCategory')->find($request->venue_id);
        $event->delete_request = 1;
        $event->save();
        if ($venue_notify->User->role == 'venue_provider') {

                $notification = new Notification();
                $notification->user_id = $venue_notify->User->id;
                $notification->title = 'Event Canceled';
                $notification->type = 'request';
                $notification->body = 'This event ' . $event->title . ' has been canceled. You can contact the admin at ' . $admin->email . 'thank you';
                $notification->event_id = $id;
                $notification->venue_id = $venue_notify->id;
                $notification->save();
                $body = array(
                    'id' => $notification->id,
                    'event_id' => $id,
                    'venue_id' => $venue_notify->id,
                    'user_id'  => $venue_notify->User->id,
                    'type'     => 'request',
                );
                $data = [
                    'to' => $venue_notify->User->fcm_token,
                    'notification' => [
                        'title' => "Event Canceled",
                        'body' => 'This event ' . $event->title . ' has been canceled. You can contact the admin at ' . $admin->email . ' thank you',
                    ],
                    'data' => [
                        'RequestData' => $body,
                    ],
                    'content_available' => true,
                ];
                // $SERVER_API_KEY = 'AAAAGAYvVyg:APA91bHn703e-8w6gHludk4Wd8Uj1HjFXYp6933n-ZQx-a8qM_Hu86nJh-XlVv7CBUXikcOICEN1TW4sswuAjjeD7RWaCwttgE3R26ZvLGdwkIgHR9HigoxyZusqQucp-i5vdjyqWww8';
                $SERVER_API_KEY = 'AAAA1U9GgKM:APA91bE_zPJEiZ6IBj_RcFwN_xzOSB7v2osZC9DcWSoYi7nPaDUdPOZRndvEnBiq8U4RgcXaNTIQUUl6-jr5FsHRCWTXUEmbjkbk5myWI_7YYif7Rj9uCqdAwPUoAEmExXkeRVeemhNo';

                $dataString = json_encode($data);
                $headers = [
                    'Authorization: key=' . $SERVER_API_KEY,
                    'Content-Type: application/json',
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                $response = curl_exec($ch);
                // dd($response);
        }
        if (isset($request->entertainer_details_id)) {
            $entertainers = EntertainerDetail::with('User', 'talentCategory')->whereIn('id', $request->entertainer_details_id)->get();
            foreach ($entertainers as $user) {

                    if ($user->User->role == 'entertainer') {
                        $notification = new Notification();
                        $notification->user_id = $user->User->id;
                        $notification->title = 'Event Canceled';
                        $notification->type = 'request';
                        $notification->body = 'This event ' . $event->title . ' has been canceled. You can contact the admin at ' . $admin->email . ' thank you';
                        $notification->event_id = $id;
                        $notification->entertainer_details_id = $user->id;
                        $notification->save();
                        $body = array(
                            'id' => $notification->id,
                            'event_id' => $id,
                            'entertainer_details_id' => $user->id,
                            'user_id' => $user->User->id,
                            'type'     => 'request',
                        );
                        $data = [
                            'to' => $user->User->fcm_token,
                            'notification' => [
                                'title' => "Event Canceled",
                                'body' =>  'This event ' . $event->title . ' has been canceled. You can contact the admin at ' . $admin->email . ' thank you',
                            ],
                            'data' => [
                                'RequestData' => $body,
                            ],
                            'content_available' => true,
                        ];
                    }

                    // $SERVER_API_KEY = 'AAAAGAYvVyg:APA91bHn703e-8w6gHludk4Wd8Uj1HjFXYp6933n-ZQx-a8qM_Hu86nJh-XlVv7CBUXikcOICEN1TW4sswuAjjeD7RWaCwttgE3R26ZvLGdwkIgHR9HigoxyZusqQucp-i5vdjyqWww8';
                    $SERVER_API_KEY = 'AAAA1U9GgKM:APA91bE_zPJEiZ6IBj_RcFwN_xzOSB7v2osZC9DcWSoYi7nPaDUdPOZRndvEnBiq8U4RgcXaNTIQUUl6-jr5FsHRCWTXUEmbjkbk5myWI_7YYif7Rj9uCqdAwPUoAEmExXkeRVeemhNo';


                    $dataString = json_encode($data);
                    $headers = [
                        'Authorization: key=' . $SERVER_API_KEY,
                        'Content-Type: application/json',
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                    $response = curl_exec($ch);
                    // dd($response);
            }
        }
        // Mail::to($data->email)->send(new DeleteAccount($data));
        return $this->sendSuccess('Event Account deletion request successfully');
    }
    public function getEventDeleteAccount()
    {
        $data = User::find(Auth::id());
        $event = Event::with('user')->where('user_id', $data->id)->where('delete_request', '1')->get();
        return $this->sendSuccess('deleted Event', compact('event'));
    }

}
