<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MyBookingContoller extends Controller
{

    public function get($ownerId)
    {
        try {
            // $ownerBooking = PaymentRequest::where('owner_id', $ownerId)
            //     ->where('status', 'Accepted')
            //     ->with('driver.driverVehicle', 'job')
            //     ->whereHas('driver.driverVehicle', function ($query) {
            //         $query->whereHas('job', function ($subQuery) {
            //             $subQuery->whereColumn('driver_vehicles.vehicle_id', '=', 'jobs.vehicle_id');
            //         });
            //     })
            //     ->get();
            $ownerBooking = DB::table('payment_requests')
                ->join('users', 'payment_requests.driver_id', '=', 'users.id')
                ->leftJoin('driver_vehicles', 'users.id', '=', 'driver_vehicles.user_id')
                ->join('jobs', function ($join) {
                    $join->on('payment_requests.job_id', '=', 'jobs.id')
                        ->where(function ($subJoin) {
                            $subJoin->on('driver_vehicles.vehicle_id', '=', 'jobs.vehicle_id')
                                ->orWhere(function ($orSubJoin) {
                                    $orSubJoin->where('jobs.active_job', '=', '1')
                                        ->orWhere('jobs.on_vehicle', '=', '0');
                                });
                        });
                })
                ->select('payment_requests.*', 'users.*', 'driver_vehicles.*', 'jobs.*')
                ->where('payment_requests.owner_id', $ownerId)
                ->where('payment_requests.status', 'Accepted')
                ->get();



            if ($ownerBooking->isNotEmpty()) {
                return response()->json([
                    'message' => 'My Booking Data Get Successfully',
                    'status' => 'success',
                    'bookingData' => $ownerBooking
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Booking Data Found',
                    'status' => 'success',
                    'bookingData' => []
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching booking data',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
