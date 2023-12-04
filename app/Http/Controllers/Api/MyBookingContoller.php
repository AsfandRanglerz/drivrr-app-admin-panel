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
                ->with('driver.driverVehicle', 'job.vehicle')
                ->get();
            $result = [];

            foreach ($ownerBooking as $paymentRequest) {
                $driver = $paymentRequest->driver;
                $job = $paymentRequest->job;
                $jobVehicleId = $job->vehicle->id;

                // Filtering driverVehicles based on vehicles_id
                $filteredDriverVehicles = $driver->driverVehicle->filter(function ($driverVehicle) use ($jobVehicleId) {
                    return $driverVehicle->vehicle_id == $jobVehicleId;
                });
                // Create an array with the relevant information for the result
                $result[] = [
                    'payment_request' => $paymentRequest,
                    'filtered_driver_vehicles' => $filteredDriverVehicles,
                ];
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
