<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Models\EventVenue;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\EntertainerDetail;
use App\Models\EventEntertainers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //    test push notification
    public function pushNotification(Request $request)
    {
        dd($request->input());
    }
    // Get User Notification
    public function getUserNotification()
    {
        $data = Notification::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        return $this->sendSuccess('All Notifications', compact('data'));
    }
    //  Entertainer request to event creartor
    public function requestEventEntertainer(Request $request)
    {
        $data =  new EventEntertainers();
        $data->event_id = $request->event_id;
        $data->entertainer_details_id = $request->entertainer_details_id;
        $data->status = 'Response';
        $data->save();
        $event = Event::with('User')->find($request->event_id);
        $entertainerDetail = EntertainerDetail::with('talentCategory', 'User')->find($request->entertainer_details_id);
        $notification = new Notification();
        $notification->user_id = $event->User->id;
        $notification->title = 'Hiring Request';
        $notification->body = $entertainerDetail->User->name . ' ' . 'wants to request you in your event' . ' ' . $event->title . 'as a' . ' ' . $entertainerDetail->talentCategory->category;
        $notification->event_id = $request->event_id;
        $notification->entertainer_details_id = $request->entertainer_details_id;
        $notification->save();
        $body = array(
            'id' => $notification->id,
            'event_id' => $request->event_id,
            'entertainer_details_id' => $request->entertainer_details_id,
        );
        $data = [
            'to' => $event->User->fcm_token,
            'notification' => [
                'title' => "Hiring Request",
                'body' =>  $entertainerDetail->User->name . ' ' . 'wants to request you in your event' . ' ' . $event->title . 'as a' . ' ' . $entertainerDetail->talentCategory->category,
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
        return $this->sendSuccess('Request send Successfully');
    }
    // Request  Venue  foer event
    public function requestEventVenue(Request $request)
    {
        $data =  new EventVenue();
        $data->event_id = $request->event_id;
        $data->venue_id = $request->venue_id;
        $data->status = 'Response';
        $data->save();
        $event = Event::with('User')->find($request->event_id);
        $venue = Venue::with('User')->find($request->venue_id);
        $notification = new Notification();
        $notification->user_id = $event->User->id;
        $notification->title = 'Hiring Request';
        $notification->body = $venue->User->name . ' ' . 'wants to request you in your event' . ' ' . $event->title . 'my venue' . ' ' . $venue->title;
        $notification->event_id = $request->event_id;
        $notification->venue = $request->venue_id;
        $notification->save();
        $body = array(
            'id' => $notification->id,
            'event_id' => $request->event_id,
            'venue_id' => $venue->id,
        );
        $data = [
            'to' => $event->User->fcm_token,
            'notification' => [
                'title' => "Hiring Request",
                'body' =>  $venue->User->name . ' ' . 'wants to request you in your event' . ' ' . $event->title . 'my venue' . ' ' . $venue->title,
            ],
            'data' => [
                'RequestData' => $body,
            ],
            'content_available' => true,
        ];

        $SERVER_API_KEY = 'AAAAGAYvVyg:APA91bHn703e-8w6gHludk4Wd8Uj1HjFXYp6933n-ZQx-a8qM_Hu86nJh-XlVv7CBUXikcOICEN1TW4sswuAjjeD7RWaCwttgE3R26ZvLGdwkIgHR9HigoxyZusqQucp-i5vdjyqWww8';


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
        return $this->sendSuccess('Request send Successfully');
    }
}
