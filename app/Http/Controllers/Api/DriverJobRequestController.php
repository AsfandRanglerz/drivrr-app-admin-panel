<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\Job;
use App\Models\Document;
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

            if ($approve_document == 1) {
                $driver_job_request = PaymentRequest::create([
                    'owner_id' => $owner_id,
                    'driver_id' => $driver_id,
                    'job_id' => $job_id,
                    'counter_offer' => 0,
                    'location' => $location,
                ]);
                return response()->json([
                    'message' => 'Your request is sent successfully.',
                    'status' => 'success',
                    'owner' => $owner,
                    'job' => $job,
                    'driver' => $driver,
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

                return response()->json([
                    'message' => 'Your request is sent successfully.',
                    'status' => 'success',
                    'owner' => $owner,
                    'job' => $job,
                    'driver' => $driver,
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
            // Use where instead of find, and specify the column and value
            $fetchjob = PaymentRequest::where('driver_id', $driver_id)->first();

            if (!$fetchjob) {
                return response()->json([
                    'message' => 'Driver not found.',
                    'status' => 'failed',
                ], 404);
            }
            $jobRequests = PaymentRequest::where('driver_id', $driver_id)
                ->with('job','owner','vehicle')
                ->get();

            return response()->json([
                'message' => 'Job requests without counter fetched successfully.',
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

}
