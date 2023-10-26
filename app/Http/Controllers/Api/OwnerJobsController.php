<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
