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
            // Fetch booking data with relationships
            $ownerBooking = PaymentRequest::where('owner_id', $ownerId)
                ->with([
                    'driver:id,fname,lname,phone,image',
                    'job:id,description,date,time,job_price,price_per_hour,vehicle_id',
                    'owner:id,fname,lname,image,email',
                    'driver.driverRewiews'
                ])->select('id', 'owner_id', 'driver_id', 'job_id', 'payment_amount', 'location', 'status')
                ->get();

            // Initialize result array
            $result = [];

            // Process each booking entry
            foreach ($ownerBooking as $data) {
                $driver = $data->driver;
                $job = $data->job;
                $owner = $data->owner;

                // Filter driver vehicles by job's vehicle_id
                $filteredDriverVehicles = $driver->driverVehicle // Assuming the relationship is named driverVehicles
                    ->where('vehicle_id', $job->vehicle_id)
                    ->values()
                    ->all();

                // Calculate days left and check if the job is today
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

            // Fetch all unique driver IDs from the bookings
            $driverIds = $ownerBooking->pluck('driver.id')->unique()->toArray();

            // Get all reviews for the drivers
            $driverReviews = Review::whereIn('driver_id', $driverIds)->get();

            // Attach driver reviews to the corresponding booking data
            foreach ($result as &$item) {
                $driverId = $item['payment_request']->driver_id;
                $reviews = $driverReviews->where('driver_id', $driverId);
                $averageRating = $reviews->avg('stars');
                $totalReviews = $reviews->count();
                $item['driver_reviews'] = [
                    'average_rating' => number_format($averageRating, 1),
                    'total_reviews' => $totalReviews,
                ];
            }

            // Return the response
            if ($result) {
                return response()->json([
                    'message' => 'My Booking Data Fetched Successfully',
                    'status' => 'success',
                    'bookingData' => $result
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Booking Data Found',
                    'status' => 'failed',
                ], 404);
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
