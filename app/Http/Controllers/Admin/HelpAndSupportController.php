<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Mail\SendResponseToUser;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FcmNotificationHelper;

class HelpAndSupportController extends Controller
{
    public function index()
    {

        return view('admin.helpAndSupport.index');
    }

    public function getData($type)
    {
        $data = Question::whereHas('user.roles', function ($query) use ($type) {
            $query->where('name', $type);
        })
            ->latest()
            ->get();

        $data = $data->map(function ($question) {
            return [
                'id' => $question->id,
                'fname' => $question->user->fname,
                'lname' => $question->user->lname,
                'email' => $question->user->email,
                'title' => $question->title,
                'details' => $question->details
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        try {
            $query = Question::where('id', $id)->with('user')->first();
            if ($query) {
                $query->update([
                    'answer' => $request->answer,
                ]);
                $fcmToken = $query->user->fcm_token;
                if ($query->user->role_id == 2) {
                    $notificationData = [
                        'type' => 'Owner',
                    ];
                } else {
                    $notificationData = [
                        'type' => 'Driver',
                    ];
                }
                if (!is_null($fcmToken)) {
                    FcmNotificationHelper::sendFcmNotification($fcmToken, 'Help & Support', 'The Admin has responded to your query.', $notificationData);
                    PushNotification::create([
                        'title' => 'Help & Support',
                        'description' => 'The Admin has responded to your query.',
                        'user_name' => $query->user->fname . ' ' . $query->user->lname,
                        'user_id' => $query->user->id,
                        'admin' => 'Admin',
                    ]);
                }

                return response()->json(['status' => true, 'message' => 'Feedback sent successfully.']);
            } else {
                return response()->json(['status' => false, 'error' => 'No matching question found.'], 500);
            }
        } catch (\Exception $e) {

            return response()->json(['status' => false, 'error' => 'An error occurred while sending the feedback.' . $e->getMessage()], 500);
        }
    }
}
