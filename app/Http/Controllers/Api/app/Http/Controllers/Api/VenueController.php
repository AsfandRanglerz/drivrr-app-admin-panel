<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Mail\BookVenues;
use App\Models\BookVenue;
use App\Models\EventVenue;
use App\Models\VenuesPhoto;
use App\Models\Notification;
use App\Models\VenuePricing;
use Illuminate\Http\Request;
use App\Models\VenueCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\VenueFeatureAdsPackage;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    //Get venue

    public  function getVenues()
    {
        // $data = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing')->get();
        $lon = Auth::user()->longitude;
        $lat = Auth::user()->latitude;
        $data = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing')->select(
            "*",
            DB::raw("6371 * acos(cos(radians(" . $lat . "))
                        * cos(radians(venues.latitude))
                        * cos(radians(venues.longitude) - radians(" . $lon . "))
                        + sin(radians(" . $lat . "))
                        * sin(radians(venues.latitude))) AS distance")
        )->orderBy('distance', 'ASC')->get();
        return $this->sendSuccess('Venue data', compact('data'));
    }
    // Get my venue
    public  function myVenues()
    {
        $data = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing')->where('user_id', Auth::id())->orderBy('id','DESC')->get();
        return $this->sendSuccess('Venue data', compact('data'));
    }
    // Get single Venue
    public function getSingleVenue($id)
    {
        // $data = User::with('venues', 'venues.venueCategory', 'venues.venuePhoto', 'venues.venuePricing', 'venues.reviews.user')->find($id);
        $data = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing')->find($id);
        if ($data == null) {
            return $this->sendError("Record Not Found!");
        }
        return $this->sendSuccess('Entertainer data', compact('data'));
    }
    // Create Venue
    public function createVenue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'required',
            'about_venue' => 'required',
            'description' => 'required',
            'seats' => 'required',
            'stands' => 'required',
            // 'opening_time' => 'required',
            // 'closing_time' => 'required',
            // 'amenities' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data = $request->only(['title', 'category_id', 'about_venue', 'description', 'seats', 'stands', 'area','address','latitude','longitude']);
        $data['user_id'] = auth()->id();
        if (isset($request->amenities)) {
            $data['amenities'] = implode(',', $request->amenities);
        }
        // if ($request->hasfile('image')) {
        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension(); // getting image extension
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('/'), $filename);
        //     $data['image'] = 'public/uploads/' . $filename;
        // }
        // $data['user_id'] = auth()->id();
        $venue = Venue::create($data);
        if ($request->file('photos')) {
            foreach ($request->file('photos') as $data) {
                $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());
                $data->move(public_path('images'), $image);
                VenuesPhoto::create([
                    'photos' =>  'public/images/' . $image,
                    'venue_id' => $venue->id
                ]);
            }
        }
        if (isset($request->day)) {
            for ($i = 0; $i < count($request->day); $i++) {
                $data = [
                    'venues_id'  => $venue->id,
                    'day'  => $request->day[$i],
                    'price'  => $request->price[$i],
                    'opening_time' => $request->opening_time[$i],
                    'closing_time' => $request->closing_time[$i],
                ];
                VenuePricing::create($data);
            }
        }
        $data = Venue::with('User', 'reviews', 'venueCategory', 'venuePhoto', 'venuePricing')->find($venue->id);
        return $this->sendSuccess('Venue created Successfully', compact('data'));
    }
    // edit venue
    public function editVenue($id)
    {
        $data = Venue::with('User', 'reviews', 'venueCategory', 'venuePhoto', 'venuePricing')->find($id);
        return $this->sendSuccess('Venue data', compact('data'));
    }
    // update venue
    public function updateVenue(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'title' => 'required',
            'category_id' => 'required',
            'about_venue' => 'required',
            'description' => 'required',
            'seats' => 'required',
            'stands' => 'required',
            // 'opening_time' => 'required',
            // 'closing_time' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data = $request->only(['category_id', 'about_venue', 'description', 'seats', 'stands', 'area','address','latitude','longitude']);
        if (isset($request->amenities)) {
            $data['amenities'] = implode(',', $request->amenities);
        }
        $venue = Venue::find($id)->update($data);
        if ($request->file('photos')) {
            VenuesPhoto::where('venue_id', $id)->delete();
            foreach ($request->file('photos') as $data) {
                $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());
                $data->move(public_path('images'), $image);
                VenuesPhoto::create([
                    'photos' =>  'public/images/' . $image,
                    'venue_id' => $id
                ]);
            }
        }
        if (isset($request->day)) {
            VenuePricing::where('venues_id', $id)->delete();
            for ($i = 0; $i < count($request->day); $i++) {
                $data = [
                    'venues_id'  => $id,
                    'day'  => $request->day[$i],
                    'price'  => $request->price[$i],
                    'opening_time' => $request->opening_time[$i],
                    'closing_time' => $request->closing_time[$i],
                ];
                VenuePricing::create($data);
            }
        }
        $venue = Venue::with('User', 'reviews', 'venueCategory', 'venuePhoto', 'venuePricing')->find($id);
        return $this->sendSuccess('Venue Updated Successfully', compact('venue'));
    }
    // delete venue
    public function destroy($id)
    {
        Venue::find($id)->delete();
        $data = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing')->where('user_id', Auth::id())->orderBy('avg_rating', 'DESC')->get();
        return $this->sendSuccess('Venue Delete Successfully', compact('data'));
    }
    // Get Venue Feature Packages
    public function getVenueFeaturePackages()
    {
        $data = VenueFeatureAdsPackage::get();
        return $this->sendSuccess('Venue Ads Packages', compact('data'));
    }
    // Get Venue Select Package
    public function VenueSelectPackage(Request $request)
    {
        Venue::where('user_id', Auth::id())->update([
            'venue_feature_ads_packages_id' => $request->id,
        ]);
        $data = Venue::with('User', 'reviews', 'venueCategory', 'venuePhoto', 'venuePricing')->where('user_id', Auth::id())->first();
        return $this->sendSuccess('Venue Featured Request Successfully', compact('data'));
    }
    // Get venue category
    public function venue_category()
    {
        $data = VenueCategory::get();
        return $this->sendSuccess('Venue Categories', compact('data'));
    }
    // Book a venue
    public function book_venue(Request $request)
    {
        $book_venue = BookVenue::where('user_id', Auth::id())->where('venue_id', $request->venue_id)->first();
        if (isset($book_venue)) {
            return $this->sendError('Already request send');
        } else {
            $data = new BookVenue();
            $data->user_id = Auth::id();
            $data->venue_id = $request->venue_id;
            $data->date = $request->date;
            $data->seats = $request->seats;
            $data->from = $request->from;
            $data->to = $request->to;
            $data->save();
            Mail::to($data->venue->User->email)->send(new BookVenues($data));
            return $this->sendSuccess('Venue Book successfully', compact('data'));
        }
    }
    // venue reviews
    public function venue_reviews($id)
    {
        $data = Venue::with('User', 'reviews.user')->find($id);
        return $this->sendSuccess('Venue reviews', compact('data'));
    }
    // single venue
    public function singleVenue($id)
    {
        $data = Venue::with('User', 'venueCategory', 'venuePhoto', 'venuePricing', 'reviews.user',)->find($id);
        if (isset($data)) {
            return $this->sendSuccess('Venue', compact('data'));
        } else {
            return $this->sendError('Record Not Found !');
        }
    }
    // venue location
    public function venue_location(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $data = Venue::find($request->venue_id);
        $data->address = $request->address;
        $data->longitude = $request->longitude;
        $data->latitude = $request->latitude;
        $data->save();
        return $this->sendSuccess('Venue location updated successfully', compact('data'));
    }
    // Approved Request ForVenue
    public function approvedRequestForVenue(Request $request)
    {
        if ($request->invitation == "Accept") {
            $data = EventVenue::where('event_id', $request->event_id)->where('venues_id', $request->venue_id)->first();
            $data->status = 'Approved';
            $data->save();
            $event = Event::with('User')->find($request->event_id);
            $venue = Venue::with('User', 'venueCategory')->find($request->venue_id);
            $notification = new Notification();
            $notification->user_id = $event->User->id;
            $notification->title = 'Request Accepted';
            $notification->type = 'accept';
            $notification->body = $venue->User->name . ' ' . 'accepted your request to provide his/her venue (' . $venue->venueCategory->category . ') in your event (' . $event->title . ')';
            $notification->event_id = $request->event_id;
            $notification->venue_id = $request->venue_id;
            $notification->save();
            $body = array(
                'id' => $notification->id,
                'event_id' => $request->event_id,
                'venue_id' => $request->venue_id,
                'type'     => 'accept',
            );
            $data = [
                'to' =>  $event->User->fcm_token,
                'notification' => [
                    'title' => "Request Accepted",
                    'body' =>  $venue->User->name . ' ' . 'accepted your request to provide his/her venue (' . $venue->venueCategory->category . ') in your event (' . $event->title . ')',
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
            EventVenue::where('event_id', $request->event_id)->where('venues_id', $request->venue_id)->delete();
            $event = Event::with('User')->find($request->event_id);
            $venue = Venue::with('User', 'venueCategory')->find($request->venue_id);
            $notification = new Notification();
            $notification->user_id = $event->User->id;
            $notification->title = 'Request Rejected';
            $notification->type = 'reject';
            $notification->body = $venue->User->name . ' rejected your request to provide his/her venue (' . $venue->venueCategory->category . ') in your event (' . $event->title . ')';
            $notification->event_id = $request->event_id;
            $notification->venue_id = $request->venue_id;
            $notification->save();
            $body = array(
                'id' => $notification->id,
                'event_id' => $request->event_id,
                'venue_id' => $request->venue_id,
                'type'     => 'reject',
            );
            $data = [
                'to' =>  $event->User->fcm_token,
                'notification' => [
                    'title' => "Request Rejected",
                    'body' =>  $venue->User->name . ' rejected your request to provide his/her venue (' . $venue->venueCategory->category . ') in your event (' . $event->title . ')',
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
            $event = Event::with('User', 'entertainerDetails.talentCategory', 'eventVenues.venueCategory', 'eventVenues.venuePhoto')->find($request->event_id);
            return $this->sendSuccess('Request Reject Successfully', compact('event'));
        }
    }

    public function venuePricing($id){
    $data =  VenuePricing::where('venues_id',$id)->get();
    return $this->sendSuccess('Venue Pricing Record', compact('data'));
    }
}
