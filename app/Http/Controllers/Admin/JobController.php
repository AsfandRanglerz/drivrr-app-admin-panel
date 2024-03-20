<?php

namespace App\Http\Controllers\Admin;

use App\Models\Job;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Mail\jobInactiveVerification;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index($id)
    // {
    //     $data = User::with('job')->find($id);
    //     $jobs = $data->job;
    //     foreach ($jobs as $job) {
    //         $vehicle = $job->vehicle->id;
    //         return view('admin.owner.jobs.index', compact('data', 'vehicle'));
    //     }
    // }
    public function index($id)
    {
        $data = User::with('job.vehicle')->find($id); // Eager loading to avoid N+1 query issues
        $jobs = $data->job;
        $vehicles = [];

        foreach ($jobs as $job) {
            $vehicles[] = $job->vehicle->id;
        }
        return view('admin.owner.jobs.index', compact('data', 'vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $data['job'] = Job::find($id);
    //     return view('admin.owner.jobs.index',$data);
    // }
    public function show(Request $request)
    {
        $id=$request->id;
        $jobs= Job::with('vehicle')->find($id);
        //    return $docters;
        if ($jobs) {
            return response()->json([
                'status' => 200,
                'jobs' =>    $jobs,
                'id' =>    $id,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'jobs Not Found',
            ]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['job'] = Job::find($id);
        $data['vehicle'] = Vehicle::all();
        return view('admin.owner.jobs.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pickup' => 'required',
            'destination' => 'required',
            'date' => 'required',
            'time' => 'required',
            'duration' => 'required',
            'service_type' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);
        if (!$validator) {
            return $this->sendError($validator->errors()->first());
        }
        $owner_job = Job::find($id);
        $owner_job->vehicle_id = $request->vehicle_id;
        $owner_job->pickup = $request->pickup;
        $owner_job->destination = $request->destination;
        $owner_job->date = $request->date;
        $owner_job->time = $request->time;
        $owner_job->duration = $request->duration;
        $owner_job->service_type = $request->service_type;
        $owner_job->price = $request->price;
        $owner_job->description = $request->description;
        $owner_job->update();
        // return $data->id;
        return redirect()->route('owner-job.index', $owner_job->user_id)->with(['status' => true, 'message' => 'Job Updated Successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Job::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Deleted Updated Successfully']);
    }


    public function status($id, $key)
    {
        $data = Job::find($id);
        $owner = User::find($key);
        $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
        $status = $data->is_active;
        if ($status == 1) {
            $owner_email = $owner->email;
            Mail::to($owner_email)->send(new jobInactiveVerification($status));
            return redirect()->back()->with(['status' => true, 'message' => 'Status Updated Successfully']);
        } else {
            return redirect()->back()->with(['status' => false, 'message' => 'Status Updated Unsuccessfully']);
        }
    }
}
