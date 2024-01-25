<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\User;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;
use App\Models\DriverWallet;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\PushNotification;
use App\Mail\OwnerCancelJobRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FcmNotificationHelper;


class OwnerGetJobREquests extends Controller
{
    public function ownerPayDriver($id)
    {
        try {
            $ownerPay = PaymentRequest::findOrFail($id);
            if ($ownerPay->counter_offer == null) {
                $ownerJob = $ownerPay->job->price;
                $ownerPay->update(['payment_amount' =>  $ownerJob]);
                $driverId = $ownerPay->driver_id;
                $driverWallet = DriverWallet::where('driver_id', $driverId)->firstOrFail();
                $driverWallet->update(['total_earning' => $driverWallet->total_earning + $ownerJob]);
                return response()->json([
                    'message' => 'Payment amount updated successfully',
                    'status' => 'success',
                    'totalAmount' =>  $driverWallet,
                ], 200);
            } else if ($ownerPay->counter_offer > 0) {
                $ownerCounterpay = $ownerPay->counter_offer;
                $ownerPay->update(['payment_amount' =>  $ownerCounterpay]);
                $driverId = $ownerPay->driver_id;
                $driverWallet = DriverWallet::where('driver_id', $driverId)->firstOrFail();
                $driverWallet->update(['total_earning' => $driverWallet->total_earning + $ownerCounterpay]);

                return response()->json([
                    'message' => 'Payment amount with CounterOffer updated successfully',
                    'status' => 'success',
                    'totalAmount' =>  $driverWallet,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
                'status' => 'error',
            ], 500);
        }
    }
    // ########## Ride Accept + Stripe Integration Code #############

    public function owner_accept_job_request(Request $request, $id)
    {
        try {
            // Create a new instance of the Stripe client
            $stripe = new \Stripe\StripeClient('sk_test_51OZ9CNH4pKZw8NygRAES6G6JbKVPxg1q96ViQV5PCKWPizBNIWSbUWW56TjAVFrAycu8nMCJ7TtSZe0B2Q9JdiOm00NZ6ir3uP');

            // Set the API key using the Stripe client instance
            \Stripe\Stripe::setApiKey('sk_test_51OZ9CNH4pKZw8NygRAES6G6JbKVPxg1q96ViQV5PCKWPizBNIWSbUWW56TjAVFrAycu8nMCJ7TtSZe0B2Q9JdiOm00NZ6ir3uP');

            $owner_accept = PaymentRequest::find($id);

            if (!$owner_accept) {
                return response()->json([
                    'message' => 'Request Rejected.',
                    'status' => 'failed',
                ], 400);
            }

            $job = $owner_accept->job;
            $owner = $owner_accept->owner;

            if (!$job) {
                return response()->json([
                    'message' => 'Job not found for the given PaymentRequest.',
                    'status' => 'failed',
                ], 400);
            }

            $amountToUse = $owner_accept->payment_amount ? $owner_accept->payment_amount * 100 : $owner_accept->counter_offer * 100;

            if (!$owner->email) {
                return response()->json([
                    'message' => 'Customer email is required for the payment.',
                    'status' => 'failed',
                ], 400);
            }

            // Create a customer in Stripe
            $customer = $stripe->customers->create([
                'email' => $owner->email,
            ]);

            // Create an ephemeral key for the customer
            $ephemeralKey = $stripe->ephemeralKeys->create(
                ['customer' => $customer->id],
                ['stripe_version' => '2023-10-16']
            );

            $data = [
                'amount' => $amountToUse,
                'currency' => 'gbp',
                'payment_method_types' => ['card'],
                'customer' => $customer->id,
                'receipt_email' => $owner->email,
            ];

            // Create a payment intent
            $paymentIntent = $stripe->paymentIntents->create($data);

            $owner_accept->update([
                'status' => 'Accepted',
            ]);

            $job->update([
                'active_job' => '1',
            ]);

            $driver = User::find($owner_accept->driver_id);

            if ($driver) {
                $title = $owner->fname . ' ' . $owner->lname;
                $description = 'Your Job Request Is Accepted';
                $notificationData = [
                    'job_idd' =>  $job->id,
                ];
                FcmNotificationHelper::sendFcmNotification($driver->fcm_token, $title, $description, $notificationData);
                PushNotification::create([
                    'title' => $title,
                    'description' => $description,
                    'user_name' =>  $owner->id,
                    'user_id' => $driver->id,
                    'job_id' => $job->id,
                ]);
            }

            return response()->json([
                'message' => 'Payment submitted successfully.',
                'status' => 'success',
                'client_secret' => $paymentIntent->client_secret,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Payment failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Payment failed.',
                'status' => 'failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function owner_cancle_request($id)
    {
        $check = PaymentRequest::where('id', $id)->first();
        if ($check) {
            $job_request = PaymentRequest::find($id);
            $driver = User::find($job_request->driver_id);
            $driver_email = $driver->email;
            $owner = User::find($job_request->owner_id);

            if ($job_request->status !== 'CancelRide') {
                $job_request->update(['status' => 'CancelRide']);

                if ($job_request->job->is_active === '0') {
                    $job_request->job->update([
                        'is_active' => '1',
                        'active_job' => '0'
                    ]);
                }
                $title = $owner->fname . ' ' . $owner->lname;
                $description = 'Your Ride has been canceled';
                $notificationData = [
                    'job_idd' => $job_request->job_id,
                ];
                FcmNotificationHelper::sendFcmNotification($driver->fcm_token, $title, $description, $notificationData);
                PushNotification::create([
                    'title' => $title,
                    'description' => $description,
                    'user_name' =>  $owner->id,
                    'user_id' => $driver->id,
                    'job_id' => $job_request->job_id,
                ]);
            }
            return response()->json([
                'message' => 'This request has been canceled',
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'This request has already been canceled.',
                'status' => 'failed',
            ], 400);
        }
    }
}
