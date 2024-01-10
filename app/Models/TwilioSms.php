<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Twilio\Rest\Client;
use App\Models\User;
use Carbon\Carbon;

class TwilioSms extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mobile_no',
        'otp',
        'token',
        'expired_at',
    ];

    public function sendOtp()
    {
        try {
            $user = User::where('phone', $this->mobile_no)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $twilioSid = getenv('TWILIO_SID');
            $twilioAuthToken = getenv('TWILIO_TOKEN');
            $twilioPhoneNumber = getenv('TWILIO_FROM');
            $twilioClient = new Client($twilioSid, $twilioAuthToken);
            $otp = $this->generateOtp();
            $this->user_id = $user->id;
            $this->otp = $otp;
            $this->save();
            $message = $twilioClient->messages->create(
                $this->mobile_no,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => "Your OTP is: $otp",
                ]
            );
            return $message->sid;
        } catch (\Exception $e) {
            return null;
        }
    }
    private function generateOtp()
    {
        return rand(1000, 9999);
    }
    // ########## OTP Verification Code #########

    public function verifyOtp($inputOtp)
    {
        if ($inputOtp == $this->otp) {
            if ($this->expired_at && now()->lt($this->expired_at)) {
                $this->token = $this->generateToken();
                $this->expired_at = null;
                $this->save();
                return true;
            }
        }

        return false;
    }

    private function generateToken()
    {
        return bcrypt(str_random(30));
    }
}
