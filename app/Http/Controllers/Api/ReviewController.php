<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;

class ReviewController extends Controller
{

    public function ownerReview(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'owner_id'=>'required',
            'driver_id'=>'required',
            'stars'=>'required',
            'comment'=>'required',
        ]);
        if(!$validator)
        {
            return $this->sendError($validator->errors()->first());
        }
        $review = Review::create([
            'owner_id'=>$request->owner_id,
            'driver_id'=>$request->driver_id,
            'stars'=>$request->stars,
            'comment'=>$request->comment
        ]);
        return response()->json([
            'message'=>'thanks for giving review.',
            'status'=>'success',
            'data'=>$review,
        ],200);
    }

    public function showFeedBackToDriver($id)
    {
        $review = User::with('review')->find($id);
        return $review;
    }
}
