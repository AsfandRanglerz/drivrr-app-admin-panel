<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\DriverVehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class DriverShowJobsController extends Controller
{
    public function get()
    {
        try {

            $getJobData = Job::join('driver_vehicles', 'jobs.vehicle_id', '=', 'driver_vehicles.vehicle_id')
                ->join('users', 'jobs.user_id', '=', 'users.id')
                ->join('vehicles', 'jobs.vehicle_id', '=', 'vehicles.id')
                ->where('driver_vehicles.is_active', '=', '1')
                ->get();
            if ($getJobData) {
                return response()->json([
                    'message' => 'Driver JOBS',
                    'status' => 'Success',
                    'job_fetched' => $getJobData,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Driver JOBS Not Fetched',
                    'status' => 'failed',
                    'job_fetched' => Null,
                ], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error fetching data'], 400);
        }
    }
}
