<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\User;
use App\Models\Review;
use App\Models\Document;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Helpers\FcmNotificationHelper;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;


class DriverJobRequestController extends Controller
{

    public function add_job_request_without_counter(Request $request, $owner_id, $driver_id, $job_id)
    {
        try {
            $owner = User::find($owner_id);
            $driver = User::find($driver_id);
            $job = Job::find($job_id);
            $location = User::where('id', $driver_id)->value('location');
            $approve_document = Document::where('user_id', $driver_id)->value('is_active');
            // return  $approve_document;
            if ($approve_document == 1) {
                $driver_job_request = PaymentRequest::create([
                    'owner_id' => $owner_id,
                    'driver_id' => $driver_id,
                    'job_id' => $job_id,
                    'payment_amount' => $job->price,
                    'location' => $location,
                ]);
                $driver_job_request->load('owner', 'driver', 'job');
                $title = $driver->fname . ' ' . $driver->lname;
                $description = 'Sent You a Job Request';
                $notificationData = [
                    'job_id' => $job->id,
                ];
                FcmNotificationHelper::sendFcmNotification($owner->fcm_token, $title, $description, $notificationData);
                PushNotification::create([
                    'title' => $title,
                    'description' => $description,
                    'user_name' => $driver->id,
                    'user_id' => $owner_id,
                    'job_id' => $job_id,
                ]);
                return response()->json([
                    'message' => 'Your request is sent successfully.',
                    'status' => 'success',
                    'data' => $driver_job_request,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Your document is not approved; therefore, you are not eligible to apply for any job.',
                    'status' => 'failed',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing the job request.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function add_job_request_counter(Request $request, $owner_id, $driver_id, $job_id)
    {
        try {
            $owner = User::find($owner_id);
            $driver = User::find($driver_id);
            $job = Job::find($job_id);

            $location = User::where('id', $driver_id)->value('location');
            $approve_document = Document::where('user_id', $driver_id)->value('is_active');

            $validator = Validator::make($request->all(), [
                'counter_offer' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'status' => 'failed',
                ], 400);
            }

            if ($approve_document == 1) {
                $driver_job_request = PaymentRequest::create([
                    'owner_id' => $owner_id,
                    'driver_id' => $driver_id,
                    'job_id' => $job_id,
                    'counter_offer' => $request->counter_offer,
                    'location' => $location,
                ]);
                // Eager load the related models
                $driver_job_request->load('owner', 'driver', 'job');
                $title = $driver->fname . ' ' . $driver->lname;
                $description = 'Job Request with Counter Offer: $' . $request->counter_offer;
                $notificationData = [
                    'job_id' => $job->id,
                ];
                FcmNotificationHelper::sendFcmNotification($owner->fcm_token, $title, $description, $notificationData);
                PushNotification::create([
                    'title' => $title,
                    'description' => $description,
                    'user_name' => $driver->id,
                    'user_id' => $owner_id,
                    'job_id' => $job_id,
                ]);
                return response()->json([
                    'message' => 'Your request is sent successfully.',
                    'status' => 'success',
                    'data' => $driver_job_request,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Your document is not approved; therefore, you are not eligible to apply for any job.',
                    'status' => 'failed',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing the job request.',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getJobRequestsByJob($driver_id)
    {
        try {
            $jobRequests = PaymentRequest::where('driver_id', $driver_id)
                ->with('job.vehicle', 'owner', 'driver')
                ->get();

            $buttonEnabledJobRequests = $jobRequests->filter(function ($jobRequest) {
                return $jobRequest->job->date == now()->format('d-m-Y') && $jobRequest->status == 'Accepted';
            });

            $responseJobRequests = $jobRequests->map(function ($jobRequest) use ($buttonEnabledJobRequests) {
                return [
                    'jobRequest' => $jobRequest,
                    'buttonCondition' => $buttonEnabledJobRequests->contains($jobRequest),
                ];
            });

            return response()->json([
                'message' => 'Job requests fetched successfully.',
                'status' => 'success',
                'jobRequests' => $responseJobRequests,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching job requests.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getJobRequestsByOwner($job_id)
    {
        try {
            $fetchjob = PaymentRequest::where('job_id', $job_id)->first();

            if (!$fetchjob) {
                return response()->json([
                    'message' => 'Job not found.',
                    'status' => 'failed',
                ], 404);
            }

            $jobRequests = PaymentRequest::where('job_id', $job_id)
                ->with([
                    'job',
                    'driver.driverRewiews.owner',
                    'driver.driverRewiews',
                    'driver.bankAccounts' => function ($query) {
                        $query->where('status', 'Active');
                    }
                ])
                ->get();
            $driverIds = $jobRequests->pluck('driver.id')->unique()->toArray();
            $driverReviews = Review::whereIn('driver_id', $driverIds)->get();
            foreach ($jobRequests as &$jobRequest) {
                $driverId = $jobRequest->driver->id;
                $reviews = $driverReviews->where('driver_id', $driverId);
                $averageRating = $reviews->avg('stars');
                $totalReviews = $reviews->count();
                $jobRequest['driver_reviews'] = [
                    'average_rating' => number_format($averageRating, 1),
                    'total_reviews' => $totalReviews,
                ];
            }

            return response()->json([
                'message' => 'Job requests successfully fetched with driver reviews.',
                'status' => 'success',
                'jobRequests' => $jobRequests,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching job requests.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancelJob($id)
    {
        try {
            $cancelRequest = PaymentRequest::where('id', $id)->delete();
            if (!$cancelRequest) {
                return response()->json([
                    'message' => 'Job not found ',
                    'status' => 'failed',
                ], 404);
            }
            return response()->json([
                'message' => 'Job requests successfully deleted',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting job requests.',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getDriverCompletedStatus(Request $request, $driverId)
    {

        try {
            $completedCount = PaymentRequest::where('driver_id', $driverId)
                ->where('status', 'Completed')
                ->count();
            return response()->json(['status' => 'success', 'completed_count' => $completedCount]);
        } catch (QueryException $e) {

            return response()->json(['status' => 'failed', 'error' => 'Database error'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
