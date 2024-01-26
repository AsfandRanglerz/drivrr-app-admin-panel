<?php

namespace App\Http\Controllers\Api;

use Stripe\Token;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\User;
use Stripe\Customer;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Payment;
use App\Mail\PaymentMail;
use App\Models\EventVenue;
use App\Models\VenuePricing;
use Illuminate\Http\Request;
use App\Models\EntertainerDetail;
use App\Models\EventEntertainers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\EventFeatureAdsPackage;
use App\Models\VenueFeatureAdsPackage;
use App\Models\EntertainerPricePackage;
use Illuminate\Support\Facades\Session;
use Stripe\Exception\ApiErrorException;
use App\Models\EntertainerFeatureAdsPackage;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            // get sender data
            $sender = User::find($request->sender_id);

            // apply checks on price packages and get package amount

            if (isset($request->entertainer_price_package_id)) {
                $amounts = EntertainerPricePackage::find($request->entertainer_price_package_id);
                $amount = $amounts->price_package;
            } elseif (isset($request->venue_pricing_id)) {

                $amounts = VenuePricing::find($request->venue_pricing_id);
                $amount = $amounts->price;
            } elseif ($request->event_feature_ads_packages_id) {
                $amounts = EventFeatureAdsPackage::find($request->event_feature_ads_packages_id);
                $amount = $amounts->price;
            } elseif ($request->entertainer_feature_ads_packages_id) {
                $amounts = EntertainerFeatureAdsPackage::find($request->entertainer_feature_ads_packages_id);
                $amount = $amounts->price;
            } elseif ($request->venue_feature_ads_packages_id) {
                $amounts = VenueFeatureAdsPackage::find($request->venue_feature_ads_packages_id);
                $amount = $amounts->price;
            }

            // Apply check on sender (customer or not)
            if ($sender->customer_id != Null) {
                Charge::create([
                    "amount" => 100 * $amount,
                    "currency" => "usd",
                    "customer" => $sender->customer_id,
                    "description" => "Test payment from" . ' ' . $sender->name,
                    "shipping" => [
                        "name" => $sender->name,
                        "address" => [
                            "line1" => $sender->city,
                            "postal_code" => "98140",
                            "city" => $sender->city,
                            "state" => "PUNJAB",
                            "country" => $sender->country,
                        ],
                    ],
                ]);
            } else {
                $customer = Customer::create([
                    "address" => [
                        "line1" => $sender->city,
                        "postal_code" => "360001",
                        "city" => $sender->city,
                        "state" => "PUNJAB",
                        "country" => $sender->country,
                    ],
                    "email" => $sender->email,
                    "name" => $sender->name,
                    "source" => $request->token,
                ]);
                Charge::create([
                    "amount" => 100 * $amount,
                    "currency" => "usd",
                    "customer" => $customer->id,
                    "description" => "Test payment from" . ' ' . $sender->name,
                    "shipping" => [
                        "name" => $sender->name,
                        "address" => [
                            "line1" => "510 Townsend St",
                            "postal_code" => "98140",
                            "city" => $sender->city,
                            "state" => "PUNJAB",
                            "country" => $sender->country,
                        ],
                    ],
                ]);
                // update customer id in sender record
                User::find($request->sender_id)->update(['customer_id' => $customer->id]);
            }
            Session::flash('success', 'Payment successful!');
        } catch (ApiErrorException $e) {
            Session::flash('error', 'Payment failed: ' . $e->getMessage());
            return $this->sendError($e->getMessage());
        }


        // Payment Save in database
        $payment = new Payment();
        $payment->sender_id = $request->sender_id;
        $payment->entertainer_id = $request->entertainer_id;
        $payment->event_id = $request->event_id;
        $payment->venue_id = $request->venue_id;
        $payment->entertainer_price_package_id = $request->entertainer_price_package_id;
        $payment->venue_pricing_id = $request->venue_pricing_id;
        $payment->entertainer_details_id = $request->entertainer_details_id;
        $payment->event_feature_ads_packages_id = $request->event_feature_ads_packages_id;
        $payment->entertainer_feature_ads_packages_id = $request->entertainer_feature_ads_packages_id;
        $payment->venue_feature_ads_packages_id = $request->venue_feature_ads_packages_id;
        $payment->description = $request->description;
        $payment->type = $request->type;
        $payment->payment = $amount;
        $payment->save();

        // Email send to sender (for payment receive)
        Mail::to($sender->email)->send(new PaymentMail($sender));

        // change hiring status after payment for venue and entertainer/Talent
        if (isset($request->entertainer_details_id)) {
            EventEntertainers::where('event_id', $request->event_id)->where('entertainer_details_id', $request->entertainer_details_id)->update(['status' => 'Hired']);
        } elseif (isset($request->venues_id)) {
            EventVenue::where('event_id', $request->event_id)->where('venues_id', $request->venues_id)->update(['status' => 'Booked']);
        }


        // feature profile after successfull payment
        if (isset($request->venue_feature_ads_packages_id)) {
            Venue::find($request->venues_id)->update(['venue_feature_ads_packages_id' => $request->venue_feature_ads_packages_id, 'feature_status' => 1]);
        } elseif (isset($request->entertainer_feature_ads_packages_id)) {
            EntertainerDetail::find($request->entertainer_details_id)->update(['entertainer_feature_ads_packages_id' => $request->entertainer_feature_ads_packages_id, 'feature_status' => 1]);
        } elseif (isset($request->event_feature_ads_packages_id)) {
            Event::find($request->event_id)->update(['event_feature_ads_packages_id' => $request->event_feature_ads_packages_id, 'feature_status' => 1]);
        }

        // $data = Payment::with('user', 'entertainer', 'venue', 'event', 'entertainerPackage')->find($payment->id);
        $data = Event::with('User', 'entertainerDetails.talentCategory', 'eventVenues.venueCategory', 'eventVenues.venuePhoto')->find($request->event_id);
        return $this->sendSuccess('Payment Success', compact('data'));
    }
}
