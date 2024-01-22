<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\FcmNotificationHelper;
use Illuminate\Support\Facades\Validator;


class ReviewController extends Controller
{

    public function ownerReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required',
            'driver_id' => 'required',
            'stars' => 'required',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $review = Review::create([
            'owner_id' => $request->owner_id,
            'driver_id' => $request->driver_id,
            'stars' => $request->stars,
            'comment' => $request->comment,
        ]);
        $owner = User::find($request->owner_id);
        $driver = User::find($request->driver_id);
        $title = $owner->fname . ' ' . $owner->lname;

        $description = 'Gives You Review';
        FcmNotificationHelper::sendFcmNotification($driver->fcm_token, $title, $description);
        return response()->json([
            'message' => 'Thanks for giving a review.',
            'status' => 'success',
            'data' => $review,
        ], 200);
    }

    public function showFeedBackToDriver($driverId)
    {
        try {
            $review = Review::where('driver_id', $driverId)->with('owner')->get();

            if ($review) {
                return response()->json([
                    'message' => 'thanks for giving review.',
                    'status' => 'success',
                    'data' => $review,
                ], 200);
            } else {
                return response()->json(['message' => 'Driver not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
