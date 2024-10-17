<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MyBookingContoller extends Controller
{

    // public function get($ownerId)
    // {
    //     try {
    //         // Fetch booking data with relationships
    //         $ownerBooking = PaymentRequest::where('owner_id', $ownerId)
    //             ->with([
    //                 'driver:id,fname,lname,phone,image',
    //                 'job:id,description,days,date,time,job_price,price_per_hour,vehicle_id,pick_up_long,pick_up_late,drop_off_long,drop_off_late,drop_off_location,pick_up_location',
    //                 'owner:id,fname,lname,image,email',
    //                 'driver.driverRewiews'
    //             ])->select('id', 'owner_id', 'driver_id', 'job_id', 'payment_amount', 'location', 'status')
    //             ->get();

    //         // Initialize result array
    //         $result = [];

    //         // Process each booking entry
    //         foreach ($ownerBooking as $data) {
    //             $driver = $data->driver;
    //             $job = $data->job;
    //             $owner = $data->owner;
    //             $filteredDriverVehicles = $driver->driverVehicle
    //                 ->where('vehicle_id', $job->vehicle_id)
    //                 ->values()
    //                 ->all();
    //             $jobEndDate = Carbon::parse($job->date)->addDays($job->days - 1);
    //             $isToday = now()->between($job->date, $jobEndDate);
    //             $result[] = [
    //                 'payment_request' => $data,
    //                 'filtered_driver_vehicles' => $filteredDriverVehicles,
    //                 'owner_details' => $owner,
    //                 'days_left' => now()->diffInDays($jobEndDate, false),
    //                 'is_today' => $isToday,
    //             ];
    //         }

    //         // Fetch all unique driver IDs from the bookings
    //         $driverIds = $ownerBooking->pluck('driver.id')->unique()->toArray();

    //         // Get all reviews for the drivers
    //         $driverReviews = Review::whereIn('driver_id', $driverIds)->get();

    //         // Attach driver reviews to the corresponding booking data
    //         foreach ($result as &$item) {
    //             $driverId = $item['payment_request']->driver_id;
    //             $reviews = $driverReviews->where('driver_id', $driverId);
    //             $averageRating = $reviews->avg('stars');
    //             $totalReviews = $reviews->count();
    //             $item['driver_reviews'] = [
    //                 'average_rating' => number_format($averageRating, 1),
    //                 'total_reviews' => $totalReviews,
    //             ];
    //         }

    //         // Return the response
    //         if ($result) {
    //             return response()->json([
    //                 'message' => 'My Booking Data Fetched Successfully',
    //                 'status' => 'success',
    //                 'bookingData' => $result
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'No Booking Data Found',
    //                 'status' => 'failed',
    //             ], 404);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'An error occurred while fetching booking data',
    //             'status' => 'error',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function get($ownerId)
    {
        try {
            // Fetch booking data with relationships
            $ownerBooking = PaymentRequest::where('owner_id', $ownerId)
                ->with([
                    'driver:id,fname,lname,phone,image',
                    'job:id,description,days,date,time,job_price,price_per_hour,vehicle_id,pick_up_long,pick_up_late,drop_off_long,drop_off_late,drop_off_location,pick_up_location',
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

                $filteredDriverVehicles = $driver->driverVehicle
                    ->where('vehicle_id', $job->vehicle_id)
                    ->values()
                    ->all();

                $jobStartDate = Carbon::parse($job->date);
                $jobEndDate = $jobStartDate->copy()->addDays($job->days - 1);
                $today = now();

                // Calculate days left until job starts
                $daysLeft = $today->diffInDays($jobStartDate, false); // Returns positive if job starts in the future

                // Check if today is the job date
                $isToday = $today->isToday() && $today->isBetween($jobStartDate, $jobEndDate);

                $result[] = [
                    'payment_request' => $data,
                    'filtered_driver_vehicles' => $filteredDriverVehicles,
                    'owner_details' => $owner,
                    'days_left' => $daysLeft >= 0 ? $daysLeft : 0, // Set to 0 if the job has already started
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
