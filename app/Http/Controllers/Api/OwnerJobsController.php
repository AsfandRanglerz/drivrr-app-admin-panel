<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Http\Controllers\Controller;
use App\Models\Job;

class OwnerJobsController extends Controller
{
    public function index($id)
    {

        $jobs = User::with('job')->find($id);
        if (User::with('job')->find($id)->where('is_active', 0)) {
            return response()->json([
                'message' => 'sorry jobs are canceled.',
                'status' => 'failed',
            ], 401);
        }
        return response()->json([
            'message' => 'Jobs show successfully.',
            'status' => 'success',
            'data' => $jobs->job,
        ], 200);
    }
    public function owner_cancelJob($id)
    {
        try {
            $cancelRequest = PaymentRequest::where('id', $id)->delete();
            if (!$cancelRequest) {
                return response()->json([
                    'message' => 'Job not found ',
                    'status' => 'failed',
                ]);
            }
            return response()->json([
                'message' => 'Job requests successfully deleted',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting job requests.',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function cancelJob($id)
    {
        try {
            $cancelJob = Job::where('id', $id)->delete();
            if (!$cancelJob) {
                return response()->json([
                    'message' => 'Job not found ',
                    'status' => 'failed',
                ], 404);
            }
            return response()->json([
                'message' => 'Job Deleted Successfully!',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting job requests.',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
