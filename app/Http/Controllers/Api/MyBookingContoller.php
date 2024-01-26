<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
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
                ->whereIn('status', ['Accepted', 'CancelRide','Completed'])
                ->with('driver.driverVehicle', 'job', 'owner', 'driver.driverRewiews')
                ->get();

            $result = [];

            foreach ($ownerBooking as $data) {
                $driver = $data->driver;
                $job = $data->job;
                $owner = $data->owner; // Fetch owner details

                $jobVehicleId = $job->vehicle_id;
                $filteredDriverVehicles = $driver->driverVehicle
                    ->where('vehicle_id', $jobVehicleId)
                    ->values()
                    ->all();

                // Calculate days left
                $daysLeft = now()->diffInDays($job->date, false);
                $isToday = now()->isSameDay($job->date);
                $result[] = [
                    'payment_request' => $data,
                    'filtered_driver_vehicles' => $filteredDriverVehicles,
                    'owner_details' => $owner,
                    'days_left' => $daysLeft,
                    'is_today' => $isToday,
                ];
            }
            //  ############ Reviews Code of Driver #########
            $driverIds = $ownerBooking->pluck('driver.id')->unique()->toArray();
            $driverReviews = Review::whereIn('driver_id', $driverIds)->get();
            foreach ($result as &$item) {
                $driver = $item['payment_request']->driver_id;
                $reviews = $driverReviews->where('driver_id', $driver);
                $averageRating = $reviews->avg('stars');
                $totalReviews = $reviews->count();
                $item['driver_reviews'] = [
                    'average_rating' => number_format($averageRating, 1),
                    'total_reviews' => $totalReviews,
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
