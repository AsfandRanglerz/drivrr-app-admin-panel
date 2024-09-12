<?php

namespace App\Helpers;

use Google\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client as GuzzleClient;

class FcmNotificationHelper
{
    private static function getGoogleAccessToken()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/drivrr-b3f38-firebase-adminsdk-7e8al-90f96d5ec4.json'));
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');

        $accessToken = $client->fetchAccessTokenWithAssertion();
        Log::info('Access Token: ' . $accessToken['access_token']);
        return $accessToken['access_token'];
    }

    public static function sendFcmNotification($fcmToken, $title, $body, $notificationData = [])
    {
        Log::info('Device Token: ' . $fcmToken);

        $accessToken = self::getGoogleAccessToken();
        $message = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $notificationData
            ],
        ];

        $client = new GuzzleClient();
        try {
            $response = $client->post('https://fcm.googleapis.com/v1/projects/drivrr-b3f38/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $message,
            ]);

            Log::info('FCM Response: ' . $response->getBody()->getContents());
            return response()->json([
                'message' => 'Notification Sent Successfully',
                'fcm' => $notificationData,  // Return the notification data in the response
            ], 200);
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Notification Send Unsuccessfully',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
