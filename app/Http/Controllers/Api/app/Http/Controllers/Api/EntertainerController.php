<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Models\BookVenue;
use App\Models\EventVenue;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\TalentCategory;
use App\Models\EntertainerDetail;
use App\Models\EventEntertainers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\EntertainerEventPhotos;
use App\Models\EntertainerPricePackage;
use Illuminate\Support\Facades\Validator;
use App\Models\EntertainerFeatureAdsPackage;

class EntertainerController extends Controller
{
    // create entertainer
    public function createEntertainer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required',
            // 'title' => 'required',
            'image' => 'required',
            'bio' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            // 'event_photos' => 'required',
            'description' => 'required',
            // 'time' => 'required',
            // 'price_package' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data = $request->only(['location', 'bio', 'category_id', 'price', 'description', 'own_equipment', 'shoe_size', 'waist', 'weight', 'height', 'awards','skin_color']);
        $data['user_id'] = auth()->id();
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images'), $filename);
            $data['image'] = 'public/images/' . $filename;
        }
        // $data['user_id'] = auth()->id();
        $entertainer = EntertainerDetail::create($data);
        if ($request->file('event_photos')) {
            foreach ($request->file('event_photos') as $data) {
                $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());
                $data->move(public_path('images'), $image);
                EntertainerEventPhotos::create([
                    'event_photos' =>  'public/images/' . $image,
                    'entertainer_details_id' => $entertainer->id
                ]);
            }
        }

        if (isset($request->time)) {
            for ($i = 0; $i < count($request->time); $i++) {
                $data = [
                    'entertainer_details_id'  => $entertainer->id,
                    'time'  => $request->time[$i],
                    'price_package'  => $request->price_package[$i],
                ];

                EntertainerPricePackage::create($data);
            }
        }
        $data = EntertainerDetail::with('entertainerEventPhotos', 'entertainerPricePackage')->find($entertainer->id);
        return $this->sendSuccess('Talent created Successfully', compact('data'));
    }
    // get entertainer
    public  function getEntertainer()
    {
        $data = EntertainerDetail::with('User','entertainerEventPhotos', 'entertainerPricePackage', 'talentCategory', 'reviews.user')->get();
        return $this->sendSuccess('Entertainer data', compact('data'));
    }
    // get single entertainer
    public function getSingleEntertainer($id)
    {
        // , 'entertainerDetail.entertainerPricePackage'
        $data = User::with('entertainerDetail.entertainerEventPhotos', 'entertainerDetail.entertainerPricePackage', 'entertainerDetail.talentCategory')->find($id);
        if ($data == null) {
            return $this->sendError("Record Not Found!");
        }
        return $this->sendSuccess('Entertainer data', compact('data'));
    }

    // update entertainer
    public function updateEntertainer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required',
            'bio' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data = $request->only(['location', 'bio', 'category_id', 'price', 'description', 'own_equipment', 'shoe_size', 'waist', 'weight', 'height', 'awards','skin_color']);
        $data['user_id'] = auth()->id();
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images'), $filename);
            $data['image'] = 'public/images/' . $filename;
        }
        $data['user_id'] = auth()->id();
        $entertainer = EntertainerDetail::find($id)->update($data);

        if ($request->file('event_photos')) {
            EntertainerEventPhotos::where('entertainer_details_id', $id)->delete();
            foreach ($request->file('event_photos') as $data) {
                $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());
                $data->move(public_path('images'), $image);
                EntertainerEventPhotos::create([
                    'event_photos' =>  'public/images/' . $image,
                    'entertainer_details_id' => $id
                ]);
            }
        }
        if (isset($request->time)) {
            EntertainerPricePackage::where('entertainer_details_id', $id)->delete();
            for ($i = 0; $i < count($request->time); $i++) {
                $data = [
                    'entertainer_details_id'  => $id,
                    'time'  => $request->time[$i],
                    'price_package'  => $request->price_package[$i],
                ];

                EntertainerPricePackage::create($data);
            }
        }
        $data = EntertainerDetail::with('entertainerEventPhotos', 'entertainerPricePackage')->find($id);
        return $this->sendSuccess('Entertainer updated Successfully', compact('data'));
    }
    // Get Entertainer Price Package
    public function getEntertainerPricePackage($id)
    {
        $data = EntertainerPricePackage::where('entertainer_details_id', $id)->get();
        if ($data == null) {
            return $this->sendError('Record Not Found!');
        }
        return $this->sendSuccess('Entertainer Price Package data', compact('data'));
    }
    // Get Entertainer Feature Packages
    public function getEntertainerFeaturePackages()
    {
        $data = EntertainerFeatureAdsPackage::get();
        return $this->sendSuccess('Entertainer Ads Packages', compact('data'));
    }
    // Entertainer Select Package
    public function EntertainerSelectPackage(Request $request)
    {
        EntertainerDetail::where('user_id', Auth::id())->update([
            'entertainer_feature_ads_packages_id' => $request->id,
        ]);
        $data = EntertainerDetail::where('user_id', Auth::id())->first();
        return $this->sendSuccess('Entertainer Featured Request Successfully', compact('data'));
    }
    // Get Talent category
    public function talentCategory()
    {
        $data  =  TalentCategory::get();
        return $this->sendSuccess('Talent Categories', compact('data'));
    }
    // delete talent
    public function delete_talent($id)
    {
        EntertainerDetail::destroy($id);
        $data = EntertainerDetail::with('User', 'reviews.user', 'entertainerEventPhotos', 'entertainerPricePackage', 'talentCategory')->where('user_id', Auth::id())->orderBy('avg_rating', 'DESC')->get();
        return $this->sendSuccess('Entertainer deleted Successfully', compact('data'));
    }
    // entertainer reviews
    public function entertainer_reviews($id)
    {
        $data = EntertainerDetail::with('User', 'reviews.user')->find($id);
        return $this->sendSuccess('Entertainer reviews', compact('data'));
    }
    // get single talent
    public function getSingleTalent($id)
    {
        $data = EntertainerDetail::with('User','entertainerEventPhotos', 'entertainerPricePackage', 'talentCategory', 'reviews.user')->find($id);
        if (isset($data)) {
            return $this->sendSuccess('Talent data', compact('data'));
        } else {
            return $this->sendError("Record Not Found!");
        }
    }
    // Approved Request For Event
    public function approvedRequestForEvent(Request $request)
    {
        if ($request->invitation == 'Accept') {
            $data = EventEntertainers::where('event_id', $request->event_id)->where('entertainer_details_id', $request->entertainer_details_id)->first();
            $data->status = 'Approved';
            $data->save();
            $event = Event::with('User')->find($request->event_id);
            $talent = EntertainerDetail::with('talentCategory')->find($request->entertainer_details_id);
            $notification = new Notification();
            $notification->user_id = $event->User->id;
            $notification->title = 'Request Accepted';
            $notification->type = 'accept';
            $notification->body = $talent->User->name . ' ' . 'accepted your request in your event' . ' ' . $event->title . ' as a' . ' ' . $talent->talentCategory->category;
            $notification->event_id = $request->event_id;
            $notification->entertainer_details_id = $request->entertainer_details_id;
            $notification->save();
            $body = array(
                'id' => $notification->id,
                'event_id' => $request->event_id,
                'entertainer_details_id' => $request->entertainer_details_id,
                'type'     => 'accept',
            );
            $data = [
                'to' =>  $event->User->fcm_token,
                'notification' => [
                    'title' => "Request Accepted",
                    'body' =>  $talent->User->name . ' ' . 'accepted your request in your event' . ' ' . $event->title . ' as a' . ' ' . $talent->talentCategory->category,
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
            $event = Event::with('User', 'entertainerDetails.talentCategory', 'eventVenues.venueCategory', 'eventVenues.venuePhoto')->find($request->event_id);
            return $this->sendSuccess('Request accept Successfully', compact('event'));
        } elseif ($request->invitation == 'Reject') {
            EventEntertainers::where('event_id', $request->event_id)->where('entertainer_details_id', $request->entertainer_details_id)->delete();
            $event = Event::with('User')->find($request->event_id);
            $talent = EntertainerDetail::with('User', 'talentCategory')->find($request->entertainer_details_id);
            $notification = new Notification();
            $notification->user_id = $event->User->id;
            $notification->title = 'Request Rejected';
            $notification->type = 'reject';
            $notification->body = $talent->User->name . ' ' . 'rejected your request in your event' . ' ' . $event->title . ' as a' . ' ' . $talent->talentCategory->category;
            $notification->event_id = $request->event_id;
            $notification->entertainer_details_id = $request->entertainer_details_id;
            $notification->save();
            $body = array(
                'id' => $notification->id,
                'event_id' => $request->event_id,
                'entertainer_details_id' => $request->entertainer_details_id,
                'type'   =>  'reject',
            );
            $data = [
                'to' =>  $event->User->fcm_token,
                'notification' => [
                    'title' => "Request Rejected",
                    'body' =>  $talent->User->name . ' ' . 'rejected your request in your event' . ' ' . $event->title . ' as a' . ' ' . $talent->talentCategory->category,
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
            $event = Event::with('User', 'entertainerDetails.talentCategory', 'eventVenues.venueCategory', 'eventVenues.venuePhoto')->find($request->event_id);
            return $this->sendSuccess('Request Reject Successfully', compact('event'));
        }
    }

    public function talentPackage($id)
    {

        $data = EntertainerPricePackage::where('entertainer_details_id', $id)->get();
        return $this->sendSuccess('Entertainer package', compact('data'));
    }
}
