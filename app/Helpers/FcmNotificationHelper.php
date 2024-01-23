<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FcmNotificationHelper
{
    public static function sendFcmNotification($fcmToken, $title, $description, $notificationData = [])
    {

        $response = Http::withHeaders([
            'Authorization' => 'key=AAAAerlut_I:APA91bHPRL6PQ0T1Mbb1EtU-SHFxb2XkMylJfNPSAWsjq4NF9ib3no_t3RZfniHVWMOXHAkI3nfYyLHqcNaqrKUyCkUuJEc6fhs9KKUOCNFbHE_V1bekRONfyIEY1arm0JavFKO6vv-_',
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $description,
                'data' => $notificationData,
            ],

        ]);
        if ($response->successful()) {
            return response()->json([
                'message' => 'Notifications Sent Successfully',
                'fcm'=>$notificationData

        ], 200);
        } else {
            return response()->json(['error' => 'Notifications Send Unsuccessfully'], 400);
        }
    }
}
