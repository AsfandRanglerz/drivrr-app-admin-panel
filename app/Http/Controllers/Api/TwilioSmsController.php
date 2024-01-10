<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\TwilioSms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TwilioSmsController extends Controller
{
    public function sendOtp(Request $request, TwilioSms $twilioSms)
    {
        $request->validate([
            'mobile_no' => 'required|numeric',
        ]);
        try {
            $user = User::where('phone', $request->input('mobile_no'))->first();

            if (!$user) {
                return response()->json(['message' => 'Number not found'], 404);
            }
            $smsRecord = TwilioSms::updateOrCreate(
                ['user_id' => $user->id],
                ['expired_at' => now()]
            );
            if ($smsRecord->expired_at && now()->lt($smsRecord->expired_at)) {
                return response()->json(['message' => 'OTP is still valid'], 422);
            }

            $result = $smsRecord->sendOtp();
            if ($result) {
                $smsRecord->otp = $result;
                $smsRecord->token = $smsRecord->generateToken();
                $smsRecord->expired_at = now()->addMinutes(1);
                $smsRecord->save();

                return response()->json(['message' => 'OTP sent successfully', 'sid' => $result]);
            } else {
                return response()->json(['message' => 'Failed to send OTP'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
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
