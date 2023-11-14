<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class JobController extends Controller
{
    public function jobStore(Request $request, $id)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'location' => 'required',
                    'date' => 'required|date_format:d-m-Y',
                    'time' => 'required|date_format:g:i A',
                    'hours' => 'required',
                    'days' => 'required',
                    'price' => 'required',
                    'description' => 'required',
                    'vehicle_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'status' => 'Failed',
                    'error' => $validator->errors(),
                ], 422);
            }
            $formattedDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('d-m-Y');
            $job = Job::create([
                'user_id' => $id,
                'vehicle_id' => $request->vehicle_id,
                'location' => $request->location,
                'date' => $formattedDate,
                'time' =>   $request->time,
                'hours' => $request->hours,
                'days' => $request->days,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Job created successfully.',
                'status' => 'Success',
                'job' => $job,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating job.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
