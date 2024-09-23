<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\User;
use App\Models\Review;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function reviewIndex()
    {
        $reviews = Review::select('driver_id', DB::raw('AVG(stars) as average_rating'))
            ->groupBy('driver_id')
            ->get();

        $driverReviews = [];

        foreach ($reviews as $review) {
            $driverId = $review->driver_id;
            $averageRating = number_format($review->average_rating, 1);
            $driver = User::find($driverId);

            if ($driver) {
                $driverName = "{$driver->fname} {$driver->lname}";
                $driverEmail = $driver->email;
                $driverReviews[$driverId]['driverName'] = $driverName;
                $driverReviews[$driverId]['driverEmail'] = $driverEmail;
                $driverReviews[$driverId]['averageRating'] = $averageRating;
                $reviewsForDriver = Review::where('driver_id', $driverId)->get();
                $driverReviews[$driverId]['reviews'] = $reviewsForDriver->toArray();
            } else {
                // Log or handle the case where the driver does not exist
                // For example, log the missing driver and continue with other drivers.
                Log::warning("Driver with ID {$driverId} not found.");
                continue; // Skip to the next iteration if driver is not found
            }
        }

        // Sort the array by averageRating in descending order
        usort($driverReviews, function ($a, $b) {
            return $b['averageRating'] <=> $a['averageRating'];
        });

        return view('admin.driverreview.index', compact('driverReviews'));
    }
}
