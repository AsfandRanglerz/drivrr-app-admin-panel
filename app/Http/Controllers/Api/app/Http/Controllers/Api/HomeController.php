<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use App\Models\TermCondition;
use Illuminate\Support\Carbon;
use App\Models\EntertainerDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Home Page
    public function HomePage()
    {
        $lon = Auth::user()->longitude;
        $lat = Auth::user()->latitude;
        if ($lat) {
            // $data['venue_event'] = Venue::with(['events' => function ($query) {
            //     $query->where('date', '>=', now()->format('Y-m-d'));
            //     $query->with('User');
            // }])
            //     ->select(
            //         "venues.id",
            //         "venues.address",
            //         DB::raw("6371 * acos(cos(radians(" . $lat . "))
            //              * cos(radians(venues.latitude))
            //             * cos(radians(venues.longitude) - radians(" . $lon . "))
            //             + sin(radians(" . $lat . "))
            //            * sin(radians(venues.latitude))) AS distance")
            //     )
            //     ->orderBy('distance', 'ASC')
            //     ->limit(10)->get();
            $data['venue_event'] = Venue::with(['events' => function ($query) {
                $query->where('date', '>=', now()->format('Y-m-d'));
                $query->with('user'); // Load the user associated with each event
            }])
            ->select(
                "venues.id",
                "venues.address",
                DB::raw("6371 * acos(cos(radians(" . $lat . "))
                    * cos(radians(venues.latitude))
                    * cos(radians(venues.longitude) - radians(" . $lon . "))
                    + sin(radians(" . $lat . "))
                    * sin(radians(venues.latitude))) AS distance")
            )
            ->orderBy('distance', 'ASC')
            ->limit(10)
            ->get();
        } else {
            $data['venue_event'] = Event::with('User', 'entertainerDetails', 'eventVenues')->get();
        }

        $data['entertainer'] = EntertainerDetail::with('User', 'reviews.user', 'entertainerEventPhotos', 'entertainerPricePackage', 'talentCategory')->orderBy('avg_rating', 'DESC')->limit(10)->get();
        if ($lat) {
            $data['venue'] = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing')->select(
                "*",
                DB::raw("6371 * acos(cos(radians(" . $lat . "))
            * cos(radians(venues.latitude))
            * cos(radians(venues.longitude) - radians(" . $lon . "))
            + sin(radians(" . $lat . "))
            * sin(radians(venues.latitude))) AS distance")
            )->orderBy('distance', 'ASC')->limit(10)->get();
        } else {
            $data['venue'] = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing')->get();
        }
        $data['feature_entertainer'] = EntertainerDetail::with('User', 'reviews.user', 'entertainerEventPhotos', 'entertainerPricePackage', 'talentCategory', 'entertainerFeatureAdsPackage')->where('feature_status', '1')->get();
        $data['feature_events'] = Event::with('User', 'entertainerDetails', 'eventVenues', 'eventFeatureAdsPackage')->where('feature_status', '1')->where('date', '>=', now()->format('Y-m-d'))->get();
        $data['feature_venue'] = Venue::with('User', 'reviews.user', 'venueCategory', 'venuePhoto', 'venuePricing', 'venueFeatureAdsPackage')->where('feature_status', '1')->get();
        return $this->sendSuccess('Home Page Data', compact('data'));
    }

    // Feature Events
    public function featureEvents()
    {
        $data['feature_events'] = Event::with('User', 'entertainerDetails', 'eventVenues', 'eventFeatureAdsPackage')->where('feature_status', '1')->where('date', '>=', now()->format('Y-m-d'))->get();
        return $this->sendSuccess('Feature Events', compact('data'));
    }

    // Top Rated Events
    public function topRatedEvents()
    {
        $data = Event::orderBy('avg_rating', 'DESC')->get();
        return $this->sendSuccess('Top rated events', compact('data'));
    }
    // Top Rated Entertainers
    public function topRatedEntertainers()
    {
        $data = EntertainerDetail::orderBy('avg_rating', 'DESC')->get();
        return $this->sendSuccess('Top rated entertainers', compact('data'));
    }
    // Top Rated Venues
    public function topRatedVenues()
    {
        $data = Venue::orderBy('avg_rating', 'DESC')->get();
        return $this->sendSuccess('Top rated venues', compact('data'));
    }
    // Terms
    public function terms()
    {
        $data = TermCondition::first();
        return $this->sendSuccess('Data sent  Successfully', compact('data'));
    }
}
