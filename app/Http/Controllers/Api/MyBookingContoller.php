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
            $ownerBooking = PaymentRequest::where('owner_id', $ownerId)
                ->where('status', 'Accepted')
                ->with('driver.driverVehicle', 'job')
                ->get();

            $result = [];

            foreach ($ownerBooking as $data) {
                $driver = $data->driver;
                $job = $data->job;
                $jobVehicleId = $job->vehicle_id;
                $filteredDriverVehicles = $driver->driverVehicle
                    ->where('vehicle_id', $jobVehicleId)
                    ->values()
                    ->all();
                if ($data->status !== 'CancelRide') {
                    $result[] = [
                        'payment_request' => $data,
                        'filtered_driver_vehicles' => $filteredDriverVehicles,
                    ];
                }
            }

            if ($result) {
                return response()->json([
                    'message' => 'My Booking Data Get Successfully',
                    'status' => 'success',
                    'bookingData' => $result
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Booking Data Found',
                    'status' => 'failed',
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
