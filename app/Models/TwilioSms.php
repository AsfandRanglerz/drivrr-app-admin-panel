<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class TwilioSms extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

//     public function sendOtp()
//     {
//         try {
//             $account_sid = getenv('TWILIO_SID');
//             $auth_token = getenv('TWILIO_TOKEN');
//             $twilio_number = "+447893933009";

//             $client = new Client($account_sid, $auth_token);
//             $client->messages->create(
//                 '+447893933009',
//                 array(
//                     'from' => $twilio_number,
//                     'body' => 'I sent this message in under 10 minutes!'
//                 )
//             );
//         } catch (\Exception $e) {
//             return null;
//         }
//     }

//     private function generateOtp()
//     {
//         return rand(1000, 9999);
//     }

//     public function verifyOtp($inputOtp)
//     {
//         if ($inputOtp == $this->otp) {
//             if ($this->expired_at && now()->lt($this->expired_at)) {
//                 $this->token = $this->generateToken();
//                 $this->expired_at = null;
//                 $this->save();
//                 return true;
//             }
//         }

//         return false;
//     }

//     private function generateToken()
//     {
//         return bcrypt(str_random(30));
//     }
// }
}
