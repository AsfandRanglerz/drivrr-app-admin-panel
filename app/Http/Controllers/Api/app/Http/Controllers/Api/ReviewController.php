<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\Venue;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\EntertainerDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // Get Venues Reviews
    public function getVenuesReviews()
    {
        $data = Review::where('role', 'venue')->get();
        return $this->sendSuccess('All Venues Reviews', compact('data'));
    }
    // Get Events Reviews
    public function getEventsReviews()
    {
        $data = Review::where('role', 'event')->get();
        return $this->sendSuccess('All Event Reviews', compact('data'));
    }
    // Get Entertainers Reviews
    public function getEntertainersReviews()
    {
        $data = Review::where('role', 'entertainer')->get();
        return $this->sendSuccess('All Entertainer Reviews', compact('data'));
    }
    // create Reviews
    public function createReviews(Request $request)
    {
        if ($request->role == 'venue') {
            $validator = Validator::make($request->all(), [
                'star' => 'required',
                'message' => 'required',
                'role'    => 'required',
                'event_id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $review = new Review();
            $review->venue_id = $request->venue_id;
            $review->user_id = Auth::id();
            $review->event_id = $request->event_id;
            $review->star = $request->star;
            $review->message = $request->message;
            $review->role = $request->role;
            $review->save();
            $ratings = Review::where('venue_id', $request->venue_id)->get();
            $avg = $ratings->avg('star');
            Venue::where('id', $request->venue_id)->update([
                'avg_rating' => round($avg, 2),
            ]);
            return $this->sendSuccess('Reviews create successfully');
        } elseif ($request->role == 'event') {
            $validator = Validator::make($request->all(), [
                'star' => 'required',
                'message' => 'required',
                'role'    => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $review = new Review();
            $review->event_id = $request->event_id;
            $review->user_id = Auth::id();
            $review->star = $request->star;
            $review->message = $request->message;
            $review->role = $request->role;
            $review->save();
            $ratings = Review::where('event_id', $request->event_id)->get();
            $avg = $ratings->avg('star');
            Event::where('id', $request->event_id)->update([
                'avg_rating' => round($avg, 2),
            ]);
            return $this->sendSuccess('Reviews create successfully');
        } elseif ($request->role == 'entertainer') {
            $validator = Validator::make($request->all(), [
                'star' => 'required',
                'message' => 'required',
                'role'    => 'required',
                'event_id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $review = new Review();
            $review->entertainer_id = $request->entertainer_id;
            $review->user_id = Auth::id();
            $review->event_id = $request->event_id;
            $review->star = $request->star;
            $review->message = $request->message;
            $review->role = $request->role;
            $review->save();
            $ratings = Review::where('entertainer_id', $request->entertainer_id)->get();
            $avg = $ratings->avg('star');
            EntertainerDetail::where('id', $request->entertainer_id)->update([
                'avg_rating' => round($avg, 2),
            ]);
            return $this->sendSuccess('Reviews create successfully');
        } else {
            return $this->sendError('Please enter correct role to create review');
        }
    }
    // Get Single Venue Review
    public function getSingleVenueReview($id)
    {
        $data = Review::with('user')->where('venue_id', $id)->get();
        return $this->sendSuccess('Venue Reviews', compact('data'));
    }
    // Get Single Event Review
    public function getSingleEventReview($id)
    {
        $data = Review::with('user')->where('event_id', $id)->get();
        return $this->sendSuccess('Event Reviews', compact('data'));
    }
    // Get Single Entertainer Review
    public function getSingleEntertainerReview($id)
    {
        $data = Review::with('user')->where('entertainer_id', $id)->get();
        return $this->sendSuccess('Entertainer Reviews', compact('data'));
    }
}
