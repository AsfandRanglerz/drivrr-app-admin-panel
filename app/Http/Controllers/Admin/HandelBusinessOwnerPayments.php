<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\PaymentRequest;

class HandelBusinessOwnerPayments extends Controller
{
    public function completeJobGet()
    {
        $paymentRequests = PaymentRequest::with([
            'owner:id,fname,lname,email',
            'driver:id,fname,lname,email',
            'job:id,job_type,days,remaining_day,drop_off_location,pick_up_location,last_completion_date,price_per_hour,job_price,date,hours'
        ])
            ->whereIn('status', ['Accepted', 'Completed'])
            ->latest()
            ->get();
        $json_data["data"] = $paymentRequests;
        return json_encode($json_data);
    }

    public function report()
    {
        $paymentRequests = PaymentRequest::with([
            'owner:id,fname,lname,email',
            'driver:id,fname,lname,email',
            'job:id,job_type,job_price,days,remaining_day,drop_off_location,pick_up_location,last_completion_date,price_per_hour'
        ])
            ->whereIn('status', ['Accepted', 'Completed'])
            ->latest()
            ->get();

        // return  $paymentRequests;
        return view('admin.jobinformation.index', compact('paymentRequests'));
    }
    public function completeJobs()
    {
        $paymentRequests = PaymentRequest::with('owner', 'driver', 'job.vehicle')->latest()->get();
        // return  $paymentRequests;
        return view('admin.completedjobs.index', compact('paymentRequests'));
    }
}
