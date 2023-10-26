<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function jobStore(Request $request, $id)
    {
        // return $request;
        $validator = Validator::make(
            $request->all(),
            [
                'pickup' => 'required',
                'destination' => 'required',
                'date' => 'required',
                'time' => 'required',
                'duration' => 'required',
                'service_type' => 'required',
                'price' => 'required',
                'description' => 'required',
            ]
        );
        if (!$validator) {
            return $this->sendError($validator->errors()->first());
        }
        $job = Job::create([
            'user_id' => $id,
            'vehicle_id' => $request->vehicle_id,
            'pickup' => $request->pickup,
            'destination' => $request->destination,
            'date' => $request->date,
            'time' => $request->time,
            'duration' => $request->duration,
            'service_type' => $request->service_type,
            'price' => $request->price,
            'description' => $request->description,
        ]);
        return response()->json([
            'message' => 'Job created successfully.',
            'status' => 'Success.',
            'this is a job you created' => $job,
        ], 200);
    }
}
