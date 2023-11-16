<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\DriverVehicle;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class DriverShowJobsController extends Controller
{
    public function get($userId)
    {
        $getJobData = null;

        $userInDriverVehicles = DB::table('driver_vehicles')
            ->where('user_id', $userId)
            ->exists();

        if ($userInDriverVehicles) {
            $getJobData = Job::join('driver_vehicles', 'jobs.vehicle_id', '=', 'driver_vehicles.vehicle_id')
                ->join('users', 'jobs.user_id', '=', 'users.id')
                ->join('vehicles', 'jobs.vehicle_id', '=', 'vehicles.id')
                ->where('driver_vehicles.is_active', '=', '1')
                ->where('jobs.on_vehicle', '=', '0')
                ->get();
        } else {
            $getJobData = Job::select('jobs.*', 'users.*', 'vehicles.*')
                ->join('users', 'jobs.user_id', '=', 'users.id')
                ->join('vehicles', 'jobs.vehicle_id', '=', 'vehicles.id')
                ->where('jobs.on_vehicle', '=', '1')
                ->get();
        }

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
                'job_fetched' => null,
            ], 400);
        }
    }
}
