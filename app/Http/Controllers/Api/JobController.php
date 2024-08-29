<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Job;
use App\Models\User;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use App\Models\DriverVehicle;
use App\Models\PaymentRequest;
use App\Models\PushNotification;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\CardException;
use App\Http\Controllers\Controller;
use App\Helpers\FcmNotificationHelper;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function getJobsByUserId($user_id)
    {
        try {
            $jobs = Job::where('user_id', $user_id)
                ->with('vehicle')
                ->get();

            return response()->json([
                'message' => 'Jobs fetched successfully.',
                'status' => 'Success',
                'jobs' => $jobs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching jobs.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // ############# Stripe Code For Job Payment ############
    public function jobPayment(Request $request, $id)
    {
        try {
            $owner = User::find($id);
            $stripe = new StripeClient('sk_test_51OZ9CNH4pKZw8NygRAES6G6JbKVPxg1q96ViQV5PCKWPizBNIWSbUWW56TjAVFrAycu8nMCJ7TtSZe0B2Q9JdiOm00NZ6ir3uP');
            Stripe::setApiKey('sk_test_51OZ9CNH4pKZw8NygRAES6G6JbKVPxg1q96ViQV5PCKWPizBNIWSbUWW56TjAVFrAycu8nMCJ7TtSZe0B2Q9JdiOm00NZ6ir3uP');
            $amountToUse = $this->getAmountToUse($request);
            $customer = $this->createCustomer($stripe, $owner->email);
            $ephemeralKey = $this->createEphemeralKey($stripe, $customer->id);
            $paymentIntent = $this->createPaymentIntent($stripe, $amountToUse, $owner->email, $customer->id);

            if ($paymentIntent) {
                return response()->json([
                    'message' => 'Payment submitted successfully.',
                    'status' => 'success',
                    'client_secret' => $paymentIntent->client_secret,
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Payment failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Payment failed.',
                'status' => 'failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function getAmountToUse($request)
    {
        $amountToUse = $request->payment_amount ?? 0;
        return $amountToUse * 100;
    }

    private function createCustomer($stripe, $email)
    {
        return $stripe->customers->create([
            'email' => $email,
        ]);
    }

    private function createEphemeralKey($stripe, $customerId)
    {
        return $stripe->ephemeralKeys->create(
            ['customer' => $customerId],
            ['stripe_version' => '2023-10-16']
        );
    }

    private function createPaymentIntent($stripe, $amount, $email, $customerId)
    {
        return $stripe->paymentIntents->create([
            'amount' => $amount,
            'currency' => 'gbp',
            'payment_method_types' => ['card'],
            'customer' => $customerId,
            'receipt_email' => $email,
        ]);
    }

    // ############ Create Job End ##################
    // public function jobStore(Request $request, $id)
    // {
    //     try {
    //         $formattedDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('d-m-Y');
    //         $job = Job::create([
    //             'user_id' => $id,
    //             'vehicle_id' => $request->vehicle_id,
    //             'pick_up_location' => $request->pick_up_location,
    //             'drop_off_location' => $request->drop_off_location,
    //             'date' => $formattedDate,
    //             'time' => $request->time,
    //             'hours' => $request->hours,
    //             'days' => $request->days,
    //             'job_type' => $request->job_type,
    //             'price_per_hour' => $request->price_per_hour,
    //             'job_price' => $request->job_price,
    //             'description' => $request->description,
    //             'on_vehicle' => $request->on_vehicle,
    //             'payment_request' => $request->payment_request,
    //         ]);
    //         // ############ Send Notifcations to Drivers #########
    //         $sendJobCreationNotifications = DriverVehicle::where('vehicle_id', $request->vehicle_id)->where('is_active', '1')
    //             ->get();
    //         $sendAdditionalNotifications = DriverVehicle::where('is_active', '0')
    //             ->get();


    //         $owner = User::find($id);
    //         $title = $owner->fname . ' ' . $owner->lname;
    //         $description = 'Posted A New Job. Check it out!';
    //         $notificationData = [
    //             'job_idd' =>  $job->id,
    //         ];
    //         if ($sendJobCreationNotifications && $job->on_vehicle == '0') {
    //             foreach ($sendJobCreationNotifications as $sendJobCreationNotification) {
    //                 $fcmToken = $sendJobCreationNotification->user->fcm_token;

    //                 FcmNotificationHelper::sendFcmNotification($fcmToken, $title, $description, $notificationData);
    //                 PushNotification::create([
    //                     'title' => $title,
    //                     'description' => $description,
    //                     'user_name' =>  $title,
    //                     'user_id' => $sendJobCreationNotification->user->id,
    //                     'job_id' =>  $job->id,
    //                 ]);
    //             }
    //         } else if ($sendAdditionalNotifications && $job->on_vehicle == '1') {
    //             foreach ($sendAdditionalNotifications as $sendAdditionalNotification) {
    //                 $fcmToken = $sendAdditionalNotification->user->fcm_token;
    //                 FcmNotificationHelper::sendFcmNotification($fcmToken, $title, $description, $notificationData);
    //                 PushNotification::create([
    //                     'title' =>  $title,
    //                     'description' => $description,
    //                     'user_name' =>  $title,
    //                     'user_id' => $sendAdditionalNotification->user->id,
    //                     'job_id' =>  $job->id,
    //                 ]);
    //             }
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Job Created successfully.',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error Creating job.',
    //             'status' => 'Error',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function jobStore(Request $request, $id)
    {
        try {
            // Format the date
            $formattedDate = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d');

            // Create the Job
            $job = Job::create([
                'user_id' => $id,
                'vehicle_id' => $request->vehicle_id,
                'pick_up_location' => $request->pick_up_location,
                'drop_off_location' => $request->drop_off_location,
                'date' => $formattedDate,
                'time' => $request->time,
                'hours' => $request->hours,
                'days' => $request->days,
                'job_type' => $request->job_type,
                'price_per_hour' => $request->price_per_hour,
                'job_price' => $request->job_price,
                'description' => $request->description,
                'on_vehicle' => $request->on_vehicle,
                'payment_request' => $request->payment_request,
                'remaining_day' => $request->days,
            ]);

            // Send Notifications to Drivers
            $driversQuery = DriverVehicle::query()
                ->where('vehicle_id', $request->vehicle_id)
                ->where('is_active', $job->on_vehicle == '0' ? '1' : '0');

            $drivers = $driversQuery->with('user')->get();

            $owner = User::find($id);
            $title = $owner->fname . ' ' . $owner->lname;
            $description = 'Posted A New Job. Check it out!';
            $notificationData = [
                'job_id' => $job->id,
            ];

            foreach ($drivers as $driverVehicle) {
                $fcmToken = $driverVehicle->user->fcm_token;

                // Send FCM Notification
                FcmNotificationHelper::sendFcmNotification($fcmToken, $title, $description, $notificationData);

                // Save Notification to Database
                PushNotification::create([
                    'title' => $title,
                    'description' => $description,
                    'user_name' => $title,
                    'user_id' => $driverVehicle->user->id,
                    'job_id' => $job->id,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Job created successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating job.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function jobUpdate(Request $request, $userId, $jobId)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'location' => 'required',
                    'date' => 'required|date_format:d-m-Y',
                    'time' => 'required|date_format:g:i A',
                    'hours' => 'required',
                    'days' => 'required',
                    'price' => 'required',
                    'description' => 'required',
                    'vehicle_id' => 'required',
                    'on_vehicle' => 'required'

                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'status' => 'Failed',
                    'error' => $validator->errors(),
                ], 422);
            }

            $formattedDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('d-m-Y');
            $job = Job::where('id', $jobId)
                ->where('user_id', $userId)
                ->first();

            if (!$job) {
                return response()->json([
                    'message' => 'Job not found.',
                    'status' => 'Failed',
                ], 404);
            }

            $job->update([
                'vehicle_id' => $request->vehicle_id,
                'location' => $request->location,
                'date' => $formattedDate,
                'time' => $request->time,
                'hours' => $request->hours,
                'days' => $request->days,
                'price' => $request->price,
                'description' => $request->description,
                'on_vehicle' => $request->on_vehicle
            ]);

            return response()->json([
                'message' => 'Job updated successfully.',
                'status' => 'Success',
                'job' => $job,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating job.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
