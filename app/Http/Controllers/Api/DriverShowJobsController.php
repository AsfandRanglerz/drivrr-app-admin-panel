<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\Validator;

class DriverShowJobsController extends Controller
{
    public function get($userId)
    {
        $getJobData = null;

        $userInDriverVehicles = DB::table('driver_vehicles')
            ->where('user_id', $userId)
            ->where('is_active', '1')
            ->exists();

        if ($userInDriverVehicles) {
            $getJobData = Job::join('driver_vehicles', 'jobs.vehicle_id', '=', 'driver_vehicles.vehicle_id')
                ->join('users', 'jobs.user_id', '=', 'users.id')
                ->join('vehicles', 'jobs.vehicle_id', '=', 'vehicles.id')
                ->where('driver_vehicles.user_id', $userId)
                ->where('driver_vehicles.is_active', '=', '1')
                ->where('jobs.on_vehicle', '=', '1')
                ->select('jobs.*', 'users.fname', 'users.lname', 'users.email', 'users.image', 'vehicles.name')
                ->get();
        } else {
            $getJobData = Job::select('jobs.*', 'users.*', 'vehicles.*')
                ->join('users', 'jobs.user_id', '=', 'users.id')
                ->join('vehicles', 'jobs.vehicle_id', '=', 'vehicles.id')
                ->where('jobs.on_vehicle', '=', '0')
                ->select('jobs.*', 'users.fname', 'users.lname', 'users.email', 'users.image', 'vehicles.name')
                ->get();
        }


        $alljobdata = [];

        if ($getJobData) {

            foreach ($getJobData as $job) {
                $pid = PaymentRequest::where('driver_id', $userId)->where('job_id', $job->id)->first();
                if ($pid) {
                    $job->status = 'yes';
                    $alljobdata[] = $job;
                } else {
                    $job->status = 'no';
                    $alljobdata[] = $job;
                }
            }

            return response()->json([
                'message' => 'Driver JOBS',
                'status' => 'Success',
                'job_fetched' => $getJobData,
            ], 200);
        } else {
            return response()->json([
                'message' => 'JOBS Not Found',
                'status' => 'failed',
                'job_fetched' => null,
            ], 400);
        }
    }
    public function location(Request $request, $userId)
    {
        $driver = User::find($userId);

        if (!$driver) {
            return response()->json([
                'message' => 'Driver not found.',
                'status' => 'Failed',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
                'status' => 'Failed',
            ], 422);
        }

        $driver->location = $request->location;
        $driver->update();

        return response()->json([
            'message' => 'Location Added',
            'status' => 'Success',
            'user' => $driver
        ], 200);
    }
    public function getLocation($userId)
    {
        $validator = Validator::make(['driver_id' => $userId], [
            'driver_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
                'status' => 'Failed',
            ], 422);
        }
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }

        $location = $user->location;

        return response()->json([
            'message' => 'Location retrieved successfully.',
            'status' => 'Success',
            'user_id' => $userId,
            'location' => $location,
        ], 200);
    }
    public function getOwnerDetails($jobId)
    {
        try {
            $jobs = Job::with('owner', 'vehicle')
                ->where('id', $jobId)
                ->first();
            if ($jobs) {
                return response()->json([
                    'message' => 'Get Data successfully.',
                    'status' => 'Success',
                    'ownerDetails' => $jobs,

                ], 200);
            } else {
                return response()->json([
                    'message' => 'Job not found.',
                    'status' => 'Failed',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }
}
