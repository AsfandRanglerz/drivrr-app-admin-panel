<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class JobController extends Controller
{
    public function getJobsByUserId($user_id)
    {
        try {
            $jobs = Job::where('user_id', $user_id)
                ->with('vehicle')
                ->get();

            return response()->json([
                'message' => 'Jobs fetched successfully.',
                'status' => 'Success',
                'jobs' => $jobs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching jobs.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function jobStore(Request $request, $id)
    {
        try {
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
                'on_vehicle' => $request->on_vehicle
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
    public function jobUpdate(Request $request, $userId, $jobId)
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
                    'on_vehicle' => 'required'

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
            $job = Job::where('id', $jobId)
                ->where('user_id', $userId)
                ->first();

            if (!$job) {
                return response()->json([
                    'message' => 'Job not found.',
                    'status' => 'Failed',
                ], 404);
            }

            $job->update([
                'vehicle_id' => $request->vehicle_id,
                'location' => $request->location,
                'date' => $formattedDate,
                'time' => $request->time,
                'hours' => $request->hours,
                'days' => $request->days,
                'price' => $request->price,
                'description' => $request->description,
                'on_vehicle' => $request->on_vehicle
            ]);

            return response()->json([
                'message' => 'Job updated successfully.',
                'status' => 'Success',
                'job' => $job,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating job.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
