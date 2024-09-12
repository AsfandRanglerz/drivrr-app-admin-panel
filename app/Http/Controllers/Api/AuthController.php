<?php

namespace App\Http\Controllers\Api;


use App\Models\User;

use Illuminate\Support\Str;

use App\Models\DriverWallet;
use Illuminate\Http\Request;
use App\Mail\ActiveUserStatus;
use App\Mail\LoginUserWithOtp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    ##### Registration code And Social Login Code ########

    public function socialLogin(Request $request)
    {
        try {
            $loginType = $request->login_type;
            $user = User::firstOrNew(['email' => $request->email]);

            if (!$user->exists) {
                $user->fname = $request->fname;
                $user->lname = $request->lname;
                $user->phone = $request->phone;
                $user->role_id = $request->role_id;
                $user->company_info = $request->company_info;
                $user->company_name = $request->company_name;
            } else {
                if (empty($user->fname)) $user->fname = $request->fname;
                if (empty($user->lname)) $user->lname = $request->lname;
            }

            if (!empty($loginType)) {
                $socialIdField = "{$loginType}_social_id";
                if ($request->has($socialIdField)) {
                    $user->$socialIdField = $request->$socialIdField;
                }
            }

            if ($request->has('image')) $user->image = $request->image;
            if ($request->has('fcm_token')) $user->fcm_token = $request->fcm_token;

            $user->save();
            $user->roles()->sync([$request->role_id]);
            $token = $user->createToken($request->email)->plainTextToken;
            auth()->login($user);
            $response = [
                'status' => 'success',
                'message' => 'User Logged In Successfully',
                'token' => $token,
                'data' => $user,
            ];

            if (empty($loginType)) {
                if ($user->role_id == 3) {
                    $wallet = DriverWallet::firstOrNew(['driver_id' => $user->id]);
                    $wallet->total_earning = $wallet->total_earning ?? 0;
                    $wallet->save();
                    $response['driver_wallet'] = $wallet;
                    Mail::to($user->email)->send(new ActiveUserStatus($user->role_id));
                } elseif ($user->role_id == 2) {
                    Mail::to($user->email)->send(new ActiveUserStatus($user->role_id));
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid role_id',
                    ], 400);
                }
            }

            return response()->json($response, 200);
        } catch (ValidationException $e) {
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

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'The user is not registered.', 'status' => 'failed'], 401);
        }
        $roleId = $user->roles->pluck('id')->first();

        if (in_array($roleId, [2, 3]) && $user->email === $request->email) {
            DB::table('user_login_with_otps')->where('email', $request->email)->delete();
            $loginOtp = random_int(1000, 9999);
            $token = Str::random(30);
            DB::table('user_login_with_otps')->insert([
                'email' => $request->email,
                'token' => $token,
                'otp' => $loginOtp,
                'user_id' => $user->id,
            ]);
            Mail::to($request->email)->send(new LoginUserWithOtp($loginOtp));

            return response()->json([
                'message' => "Login OTP sent to your email successfully for roleId {$roleId}.",
                'status' => 'success',
                'roleId' => $roleId,
                'id' => $user->id,
            ], 200);
        } else {
            return response()->json(['message' => 'You are not allowed to send OTP.', 'status' => 'failed'], 401);
        }
    }

    public function user_otp_login_verify(Request $request)
    {
        try {
            $user_otp = DB::table('user_login_with_otps')
                ->where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$user_otp) {
                return response()->json([
                    'message' => 'OTP verification failed.',
                    'status' => 'Failed',
                ], 400);
            }
            $user_id = $user_otp->user_id;
            $user = User::find($user_id);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found.',
                    'status' => 'Failed',
                ], 404);
            }
            $user->fcm_token = $request->fcm_token;
            $user->save();
            DB::table('user_login_with_otps')->where('id', $user_otp->id)->delete();
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'message' => 'OTP verified successfully.',
                'status' => 'Success',
                'token' => $token,
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            // Catch any exceptions and return a generic error response
            return response()->json([
                'message' => 'An error occurred during OTP verification.',
                'status' => 'Failed',
                'error' => $e->getMessage(), // Optionally, include the exception message
            ], 500);
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
        try {
            $user = $request->user();
            if ($user) {
                $user->fcm_token = NULL;
                $user->save();
                $request->user()->currentAccessToken()->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Logout Successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No authenticated user found.',
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        try {
            $user = User::findOrFail($id);

            $latitude = $user->latitude;
            $longitude = $user->longitude;

            return response()->json([
                'userId' => $user->id,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    public function appleLogin(Request $request, $id)
    {
        $data = $request->only(['apple_social_id', 'login_type', 'fcm_token', 'role_id']);

        if ($data['login_type'] === 'apple') {
            $user = User::where('apple_social_id', $data['apple_social_id'])->first();

            if ($user) {
                $user->fcm_token = $data['fcm_token'];
                $user->save();
            } else if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            } else {
                $user = User::create([
                    'apple_social_id' => $data['apple_social_id'],
                    'fcm_token' => $data['fcm_token'],
                    'role_id' => $request->role_id,
                    // Add any additional fields required for user creation
                ]);
                $user->roles()->sync([$request->role_id]);
            }

            // Check for specific roles
            if ($user->role_id == 2 || $user->role_id == 3) {
                if ($user->role_id == 3) {
                    // Create or update driver wallet
                    $wallet = DriverWallet::firstOrNew(['driver_id' => $user->id]);
                    $wallet->total_earning = $wallet->total_earning ?? 0;
                    $wallet->save();
                }

                // Generate token
                $token = $user->createToken($user->email)->plainTextToken;

                return response()->json([
                    'status' => 'ok',
                    'message' => 'User Logged In Successfully',
                    'token' => $token,
                    'user' => $user,
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid role type',
            ], 400);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid login type',
        ], 400);
    }
}
    ############ OTP CODE End ###########################
