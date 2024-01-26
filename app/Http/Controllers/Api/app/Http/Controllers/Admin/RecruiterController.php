<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use App\Models\EntertainerDetail;
use App\Models\EventFeatureAdsPackage;
use App\Mail\UserLoginPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;



class RecruiterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.recruiter.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'phone' => 'required',
            'company' => 'required',
            'designation' => 'required',
            'address' => 'required'
        ]);

        $data = $request->only(['name', 'email', 'role', 'phone', 'company', 'designation', 'address' , 'latitude' , 'longitude']);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // Get the file extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/'), $filename);
            $data['image'] = 'public/images/' . $filename;
        }
        else{
            $data['image'] = 'public/images/1695640800.png';
        }
        $data['role'] = 'recruiter';
        $password = random_int(10000000, 99999999);
        // $data['role'] = 'entertainer';

        $data['password'] = Hash::make($password);
        // dd($message);
        $user = User::create($data);
        $message['email'] = $request->email;
        $message['password']=$password;
        try {
        Mail::to($request->email)->send(new UserLoginPassword($message));
            return redirect()->route('admin.user.index')->with(['status' => true, 'message' => 'Recruiter Created successfully']);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()
            ->with(['status' => false, 'message'=>'Unexpected error occured']);
        }
    }
    /**
     *  Showing Recruiter Event
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $data['recruiter_event'] = Event::with('eventFeatureAdsPackage')->where('user_id', $user_id)->
        latest()->get();
        $data['user_id'] = $user_id;
        return view('admin.recruiter.event.index', compact('data'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id)
    {

        $recruiter = User::find($user_id);
        return view('admin.recruiter.edit', compact('recruiter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {   
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'company' => 'required',
            'designation' => 'required',
            'address' => 'required'

        ]);
        $recruiter = User::find($user_id);
        $recruiter->name   = $request->input('name');
        $recruiter->email  = $request->input('email');
        $recruiter->phone  = $request->input('phone');
        $recruiter->company= $request->input('company');
        $recruiter->designation=   $request->input('designation');
        $recruiter->address  = $request->input('address');
        $recruiter->latitude  = $request->input('latitude');
        $recruiter->longitude= $request->input('longitude');
        if ($request->hasFile('image')) {
            $destination = 'public/images/' . $recruiter->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/images/', $filename);
            $image = 'public/images/' . $filename;
            $recruiter->image = $image;
        }
        $recruiter->update();

        return redirect()->route('admin.user.index')->with(['status' => true, 'message' => 'Recruiter Updated sucessfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Recruiter Deleted sucessfully']);
    }
    public function destroyEvent($event_id)
    {
        $data=Event::destroy($event_id);
        // dd($data);
        return redirect()->back()->with(['status' => true, 'message' => 'Event Deleted sucessfully']);
    }
    public function editEventIndex($user_id, $event_id)
    {
        // dd($user_id,$event_id);
        $data['recruiter_event'] = Event::find($event_id);
        $data['Event_feature_ads_packages']=EventFeatureAdsPackage::select('id','title','price','validity')->get();
        // $data['user_id'] = $id;
        $data['user_id']= $user_id;
        return view('admin.recruiter.event.edit', compact('data'));
    }
    public function updateEvent(Request $request, $event_id)
    {
        // dd($request->input());
        if($request->event_feature_ads_packages_id !=='null' && $request->feature_ads==='on'){
        // dd($request->input());

        $request->validate([
            'title' => 'required',
            // 'cover_image' => 'required',
            // 'location' => 'required',
            'about_event' => 'required',
            'description' => 'required',
            'price' => 'required',
            'event_type' => 'required',
            'joining_type' => 'required',
            // 'hiring_entertainers_status' => 'required',
            'seats' => 'required',
            'date' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $recruiter = Event::find($event_id);
        $recruiter->title = $request->title;
        $recruiter->about_event = $request->about_event;
        $recruiter->description  = $request->description;
        $recruiter->price = $request->price;
        $recruiter->event_type = $request->event_type;
        $recruiter->joining_type = $request->joining_type;
        // $recruiter->hiring_entertainers_status = $request->hiring_entertainers_status;
        $recruiter->seats = $request->seats;
        $recruiter->date = $request->date;
        $recruiter->from = $request->from;
        $recruiter->to = $request->to;
        $recruiter->event_feature_ads_packages_id=$request->event_feature_ads_packages_id;
        if ($request->hasFile('cover_image')) {
            $destination = 'public/images/' . $recruiter->cover_image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/images/', $filename);
            $cover_image = 'public/images/' . $filename;
            $recruiter->cover_image = $cover_image;
        }
        $recruiter->feature_status =1;
        // dd($recuiter);
        $recruiter->update();
        return redirect()->route('recruiter.show',$recruiter->user_id)->with(['status' => true, 'message' => 'Event Updated sucessfully']);
    }else if($request->event_feature_ads_packages_id ==='null' && $request->feature_ads==='on'){
        return redirect()->back()->with(['status'=>false, 'message' => 'Feature Package Must Be Selected']);
    }else{
        $request->validate([
            'title' => 'required',
            // 'cover_image' => 'required',
            // 'location' => 'required',
            'about_event' => 'required',
            'description' => 'required',
            'price' => 'required',
            'event_type' => 'required',
            'joining_type' => 'required',
            // 'hiring_entertainers_status' => 'required',
            'seats' => 'required',
            'date' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $recruiter = Event::find($event_id);
        $recruiter->title = $request->title;
        $recruiter->about_event = $request->about_event;
        $recruiter->description  = $request->description;
        $recruiter->price = $request->price;
        $recruiter->event_type = $request->event_type;
        $recruiter->joining_type = $request->joining_type;
        // $recruiter->hiring_entertainers_status = $request->hiring_entertainers_status;
        $recruiter->seats = $request->seats;
        $recruiter->date = $request->date;
        $recruiter->from = $request->from;
        $recruiter->to = $request->to;
        $recruiter->event_feature_ads_packages_id=null;
        if ($request->hasFile('cover_image')) {
            $destination = 'public/images/' . $recruiter->cover_image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/images/', $filename);
            $cover_image = 'public/images/' . $filename;
            $recruiter->cover_image = $cover_image;
        }
        $recruiter->feature_status =0;
        $recruiter->update();
        return redirect()->route('recruiter.show',$recruiter->user_id)->with(['status' => true, 'message' => 'Event Updated sucessfully']);
    }
    }
    public function createEventIndex($user_id){
        $data['user_id'] = $user_id;
        $data['Event_feature_ads_packages']=EventFeatureAdsPackage::select('id','title','price','validity')->get();
        return view('admin.recruiter.event.add',compact('data'));
    }
    public function storeEvent(Request $request,$user_id){
        if($request->has('event_feature_ads_packages_id')){
        $request->validate([
            'title' => 'required',
            // 'cover_image' => 'required',
            // 'location' => 'required',
            'about_event' => 'required',
            'description' => 'required',
            'price' => 'required',
            'event_type' => 'required',
            'joining_type' => 'required',
            // 'hiring_entertainers_status' => 'required',
            'seats' => 'required',
            'date' => 'required',
            'from' => 'required',
            'to' => 'required',
            'event_feature_ads_packages_id'=>'required'
        ]);
        $data=$request->only(['title','user_id','about_event','description','price','event_type','joining_type','hiring_entertainers_status','seats','date','from','to','event_feature_ads_packages_id']);
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension(); // Get the file extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/'), $filename);
            $data['cover_image'] = 'public/images/' . $filename;
        }
        else{
            $data['cover_image'] = 'public/images/1695640800.png';
        }
        $data['feature_status']=1;
        $data['user_id']=$user_id;
        Event::create($data);
        return redirect()->route('recruiter.show',$user_id)->with(['status' => true, 'message' => 'Event Added successfully']);
    }else{
        $request->validate([
            'title' => 'required',
            // 'cover_image' => 'required',
            // 'location' => 'required',
            'about_event' => 'required',
            'description' => 'required',
            'price' => 'required',
            'event_type' => 'required',
            'joining_type' => 'required',
            // 'hiring_entertainers_status' => 'required',
            'seats' => 'required',
            'date' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $data=$request->only(['title','user_id','about_event','description','price','event_type','joining_type','hiring_entertainers_status','seats','date','from','to']);
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension(); // Get the file extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/'), $filename);
            $data['cover_image'] = 'public/images/' . $filename;
        }
        else{
            $data['cover_image'] = 'public/images/1695640800.png';
        }
        $data['user_id']=$user_id;
        Event::create($data);
        return redirect()->route('recruiter.show',$user_id)->with(['status' => true, 'message' => 'Event Added successfully']);

    }
    }
    public function eventEntertainersIndex($user_id,$event_id){
       $data['event_entertainers']= Event::find($event_id)->entertainerDetails;
       $data['user_id']= $user_id;
    //   dd($data['event_entertainers']);
        return view('admin.recruiter.event.event_entertainers',compact('data'));
    }
    public function eventVenuesIndex($user_id,$event_id){
        $data['event_venues']= Event::find($event_id)->eventVenues;
        $data['user_id']= $user_id;
         return view('admin.recruiter.event.event_venues',compact('data'));
     }
     //feacture ads
    //  public function showfeature()
    //  {
    //     $data['Event_feature_ads_packages']=EventFeatureAdsPackage::select('id','price','validity')->get();
    //     dd($data);
    //     return view('admin.recruiter.event.add',compact('data'));
    //  }


}
