<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\Job;
use App\Models\Document;

class OwnerGetJobREquests extends Controller
{
    public function show_job_requests($owner_id)
    {
        $job_id = PaymentRequest::where('owner_id',$owner_id)->value('job_id');
        $driver_id = PaymentRequest::where('owner_id',$owner_id)->value('driver_id');
        $driver_requests = PaymentRequest::where('owner_id',$owner_id)->get();
        return [$job_id,$driver_id,$driver_requests];
    }
}
