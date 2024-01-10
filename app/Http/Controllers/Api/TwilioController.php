<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Twilio\Rest\Client;
use App\Models\TwilioSms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TwilioController extends Controller
{
    // public function otp(Request $request)
    // {
    //     $request->validate([
    //         'mobile_no' => 'required|exists:users,phone',
    //     ]);

    //     try {
    //         $user = User::where('phone', $request->input('mobile_no'))->first();
    //         $smsRecord = TwilioSms::updateOrCreate(
    //             ['user_id' => $user->id],
    //             ['expired_at' => now()]
    //         );

    //         if ($smsRecord->expired_at && now()->lt($smsRecord->expired_at)) {
    //             return response()->json(['message' => 'OTP is still valid'], 422);
    //         }

    //         $result = $smsRecord->sendOtp();
    //         if ($result) {
    //             return response()->json(['message' => 'OTP sent successfully', 'sid' => $result]);
    //         } else {
    //             return response()->json(['message' => 'Failed to send OTP'], 500);
    //         }
    //     } catch (\Exception $e) {
    //         // Log the error or handle it appropriately
    //         return response()->json(['message' => 'An error occurred'], 500);
    //     }
    // }
    public function otp(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|numeric|digits:11',
        ]);
        $user = User::where('phone', $request->mobile_no)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $otp = mt_rand(1000, 9999);
        $otpVerification = TwilioSms::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => $otp,
                'expired_at' => now()->addMinutes(1),
            ]
        );

        try {
            // Use Twilio to send the OTP via SMS
            $account_sid = getenv('TWILIO_SID');
            $auth_token = getenv('TWILIO_TOKEN');
            $twilio_number = getenv('TWILIO_FROM');
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $request->mobile_no,
                [
                    'from' => $twilio_number,
                    'body' => "Your OTP is: $otp",
                ]
            );
            return response()->json(['message' => 'OTP sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
    }
    public function verifyOtp(Request $request, TwilioSms $twilioSms)
    {
        $request->validate([
            'mobile_no' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);
        try {
            $smsRecord = TwilioSms::where('mobile_no', $request->input('mobile_no'))->first();

            if (!$smsRecord) {
                return response()->json(['message' => 'SMS record not found'], 404);
            }

            if ($smsRecord->verifyOtp($request->input('otp'))) {
                return response()->json(['token' => $smsRecord->token]);
            } else {
                return response()->json(['message' => 'OTP verification failed'], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    // public function verifyOtp(Request $request, TwilioSms $twilioSms)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'mobile_no' => 'required|numeric',
    //         'otp' => 'required|numeric',
    //     ]);

    //     try {
    //         $user = User::where('phone', $request->input('mobile_no'))->first();

    //         if (!$user) {
    //             return response()->json(['message' => 'User not found'], 404);
    //         }
    //         $smsRecord = TwilioSms::where('mobile_no', $request->input('mobile_no'))->first();
    //         if (!$smsRecord) {
    //             return response()->json(['message' => 'SMS record not found'], 404);
    //         }
    //         // Verify the OTP
    //         if ($smsRecord->verifyOtp($request->input('otp'))) {
    //             return response()->json(['token' => $smsRecord->token]);
    //         } else {
    //             return response()->json(['message' => 'OTP verification failed'], 422);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'An error occurred'], 500);
    //     }
    // }
}
