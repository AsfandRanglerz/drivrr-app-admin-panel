<?php

namespace App\Http\Controllers\Api;

use Notification;
use App\Models\User;
use App\Models\Admin;
use App\Models\RoleUser;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use App\Models\DriverWallet;
use Illuminate\Http\Request;
use App\Mail\ActiveUserStatus;
use App\Mail\LoginUserWithOtp;
use App\Models\UserLoginWithOtp;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TestingNotification;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    ##### Registration code And Social Login Code ########
    // public function socialLogin(Request $request, $id)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|email|unique:users,email',
    //             'phone' => 'required|string|unique:users,phone',
    //         ]);
    //         $login_type = $request->login_type;
    //         $find_user = User::where('email', $request->email)->first();

    //         if ($find_user) {
    //             if (empty($find_user->fname)) {
    //                 $find_user->fname = $request->fname;
    //             }
    //             if (empty($find_user->lname)) {
    //                 $find_user->lname = $request->lname;
    //             }
    //             switch ($login_type) {
    //                 case "facebook":
    //                     $find_user->facebook_social_id = $request->facebook_social_id;
    //                     break;
    //                 case "google":
    //                     $find_user->google_social_id = $request->google_social_id;
    //                     break;
    //                 case "apple":
    //                     $find_user->apple_social_id = $request->apple_social_id;
    //                     break;
    //             }
    //             if ($request->has('image')) {
    //                 $find_user->image = $request->image;
    //             }
    //             if ($request->has('fcm_token')) {
    //                 $find_user->fcm_token = $request->fcm_token;
    //             }
    //             $find_user->save();
    //             $find_user->roles()->sync([$request->role_id]);
    //             auth()->login($find_user);
    //             if ($find_user->role_id == 2 || $find_user->role_id == 3) {
    //                 if ($find_user->role_id == 3) {
    //                     Mail::to($find_user->email)->send(new ActiveUserStatus($find_user->id));
    //                     $wallet = DriverWallet::create([
    //                         'driver_id' => $find_user->id,
    //                         'total_earning' => 0,
    //                     ]);
    //                 }
    //                 $token = auth()->user()->createToken($request->email)->plainTextToken;
    //                 return response()->json([
    //                     'status' => 'success',
    //                     'message' => 'User Logged In Successfully',
    //                     'token' => $token,
    //                     'data' => $find_user,
    //                     'driver_wallet' => isset($wallet) ? $wallet : null,
    //                 ], 200);
    //             } else {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Invalid role_id',
    //                 ], 400);
    //             }
    //         } else {
    //             $user = new User();
    //             $user->fname = $request->fname;
    //             $user->lname = $request->lname;
    //             $user->email = $request->email;
    //             $user->phone = $request->phone;
    //             $user->role_id = $request->role_id;
    //             $user->company_info = $request->company_info;
    //             $user->company_name = $request->company_name;
    //             switch ($login_type) {
    //                 case "facebook":
    //                     $user->facebook_social_id = $request->facebook_social_id;
    //                     break;
    //                 case "google":
    //                     $user->google_social_id = $request->google_social_id;
    //                     break;
    //                 case "apple":
    //                     $user->apple_social_id = $request->apple_social_id;
    //                     break;
    //             }
    //             if ($request->has('image')) {
    //                 $user->image = $request->image;
    //             }
    //             if ($request->has('fcm_token')) {
    //                 $user->fcm_token = $request->fcm_token;
    //             }
    //             $user->save();
    //             $user->roles()->sync([$request->role_id]);
    //             auth()->login($user);
    //             if ($user->role_id == 3) {
    //                 Mail::to($user->email)->send(new ActiveUserStatus($user->id));
    //                 $wallet = DriverWallet::create([
    //                     'driver_id' => $user->id,
    //                     'total_earning' => 0,
    //                 ]);
    //                 $token = auth()->user()->createToken($request->email)->plainTextToken;
    //                 return response()->json([
    //                     'status' => 'success',
    //                     'message' => 'User Logged In Successfully',
    //                     'token' => $token,
    //                     'data' => $user,
    //                     'driver_wallet' => isset($wallet) ? $wallet : null,
    //                 ], 200);
    //             } else if ($user->role_id == 2) {
    //                 $token = auth()->user()->createToken($request->email)->plainTextToken;
    //                 Mail::to($user->email)->send(new ActiveUserStatus($id));
    //                 $token = $user->createToken($request->email)->plainTextToken;
    //                 return response()->json([
    //                     'message' => "Added successfully.",
    //                     'status' => "success",
    //                     'token' => $token,
    //                     'data' =>  $user,
    //                 ], 200);
    //             }
    //         }
    //     } catch (ValidationException $e) {
    //         // Validation failed, return validation error messages
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     }
    // }


    public function socialLogin(Request $request, $id)
    {
        try {
            $login_type = $request->login_type;
            $find_user = User::firstOrNew(['email' => $request->email]);
            if ($find_user->exists) {
                // Update user information
                if (empty($find_user->fname)) {
                    $find_user->fname = $request->fname;
                }
                if (empty($find_user->lname)) {
                    $find_user->lname = $request->lname;
                }
                switch ($login_type) {
                    case "facebook":
                        $find_user->facebook_social_id = $request->facebook_social_id;
                        break;
                    case "google":
                        $find_user->google_social_id = $request->google_social_id;
                        break;
                    case "apple":
                        $find_user->apple_social_id = $request->apple_social_id;
                        break;
                }
                if ($request->has('image')) {
                    $find_user->image = $request->image;
                }

                if ($request->has('fcm_token')) {
                    $find_user->fcm_token = $request->fcm_token;
                }

                $find_user->save();
                $find_user->roles()->sync([$request->role_id]);
                auth()->login($find_user);

                if ($find_user->role_id == 2 || $find_user->role_id == 3) {
                    $wallet = null;
                    if ($find_user->role_id == 3) {
                        // Mail::to($find_user->email)->send(new ActiveUserStatus($find_user->role_id));
                        $wallet = DriverWallet::firstOrNew(['driver_id' => $find_user->id]);
                        $wallet->total_earning = $wallet->total_earning ?? 0;
                        $wallet->save();
                    }
                    $token = auth()->user()->createToken($request->email)->plainTextToken;

                    return response()->json([
                        'status' => 'success',
                        'message' => 'User Logged In Successfully',
                        'token' => $token,
                        'data' => $find_user,
                        'driver_wallet' => isset($wallet) ? $wallet : null,
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid role_id',
                    ], 400);
                }
            } else {
                // Create a new user
                $user = new User();
                $user->fname = $request->fname;
                $user->lname = $request->lname;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->role_id = $request->role_id;
                $user->company_info = $request->company_info;
                $user->company_name = $request->company_name;

                switch ($login_type) {
                    case "facebook":
                        $user->facebook_social_id = $request->facebook_social_id;
                        break;
                    case "google":
                        $user->google_social_id = $request->google_social_id;
                        break;
                    case "apple":
                        $user->apple_social_id = $request->apple_social_id;
                        break;
                }

                if ($request->has('image')) {
                    $user->image = $request->image;
                }

                if ($request->has('fcm_token')) {
                    $user->fcm_token = $request->fcm_token;
                }

                $user->save();
                $user->roles()->sync([$request->role_id]);
                auth()->login($user);

                if ($user->role_id == 3) {
                    Mail::to($user->email)->send(new ActiveUserStatus($user->role_id));
                    $wallet = DriverWallet::firstOrNew(['driver_id' => $user->id]);
                    $wallet->total_earning = $wallet->total_earning ?? 0;
                    $wallet->save();

                    $token = auth()->user()->createToken($request->email)->plainTextToken;

                    return response()->json([
                        'status' => 'success',
                        'message' => 'User Logged In Successfully',
                        'token' => $token,
                        'data' => $user,
                        'driver_wallet' => isset($wallet) ? $wallet : null,
                    ], 200);
                } else if ($user->role_id == 2) {
                    $token = auth()->user()->createToken($request->email)->plainTextToken;
                    Mail::to($user->email)->send(new ActiveUserStatus($user->role_id));

                    return response()->json([
                        'message' => "Added successfully.",
                        'status' => "success",
                        'token' => $token,
                        'data' =>  $user,
                    ], 200);
                }
            }
        } catch (ValidationException $e) {
            // Validation failed, return validation error messages
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    ##### Registration code And Social Login Code End ########
    public function checkEmailExists(Request $request)
    {
        $email = $request->input('email');

        // Check if the email already exists in the users table
        $exists = User::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }
    ############ OTP CODE ###########################
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
            'fcm_token' => 'required'
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
        User::where('id', $user_id)->update(['fcm_token' => $request->fcm_token]);

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
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'Failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }
        $newOtp = rand(1000, 9999);
        DB::table('user_login_with_otps')->where('user_id', $user->id)->update(['otp' => $newOtp]);
        Mail::to($request->email)->send(new LoginUserWithOtp($newOtp));

        return response()->json([
            'message' => 'OTP resent successfully.',
            'status' => 'success',
        ], 200);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success',
            'status' => 'success'
        ], 200);
    }

    public function getLocation(Request $request, $id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        return response()->json([
            'message' => 'Location updated successfully',
            'driverLocation' => $user,
        ]);
    }
    public function getLocations($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $latitude = $user->latitude;
        $longitude = $user->longitude;

        return response()->json([
            'userId' => $user->id,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
    // public function appleLogin(Request $request, $id)
    // {
    //     try {
    //         $appleSocialId = $request->apple_social_id;
    //         $user = User::where('apple_social_id', $appleSocialId)->first();

    //         if ($user) {
    //             $user->fill($request->only(['fname', 'lname', 'email', 'phone', 'image', 'fcm_token']));
    //             $user->save();
    //             $user->roles()->sync([$request->role_id]);
    //         } else {
    //             $user = new User();
    //             $user->fill($request->only(['fname', 'lname', 'email', 'phone', 'role_id', 'company_info', 'company_name']));
    //             $user->apple_social_id = $appleSocialId;
    //             $user->save();
    //             $user->roles()->sync([$request->role_id]);
    //         }
    //         if ($user->role_id == 2 || $user->role_id == 3) {
    //             $wallet = null;
    //             if ($user->role_id == 3) {
    //                 Mail::to($user->email)->send(new ActiveUserStatus($user->id));
    //                 $wallet = DriverWallet::firstOrNew(['driver_id' => $user->id]);
    //                 $wallet->total_earning = 0;
    //                 $wallet->save();
    //             }

    //             $token = $user->createToken($request->email)->plainTextToken;
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'User Logged In Successfully',
    //                 'token' => $token,
    //                 'data' => $user,
    //                 'driver_wallet' => $wallet,
    //             ], 200);
    //         } elseif ($user->role_id == 2) {
    //             $token = $user->createToken($request->email)->plainTextToken;
    //             Mail::to($user->email)->send(new ActiveUserStatus($id));
    //             return response()->json([
    //                 'message' => "Added successfully.",
    //                 'status' => "success",
    //                 'token' => $token,
    //                 'data' =>  $user,
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid role_id',
    //             ], 400);
    //         }
    //     } catch (ValidationException $e) {
    //         // Validation failed, return validation error messages
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     }
    // }
}
    ############ OTP CODE End ###########################
