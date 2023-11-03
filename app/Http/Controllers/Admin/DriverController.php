<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Job;
use App\Models\Review;
use App\Models\Question;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\VerifyUserEmail;
use App\Mail\SignupPasswordSend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\storage;


class DriverController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = User::whereHas('roles', function ($q) {
        //     $q->where('title', 'driver');
        // })->orderBy('id', 'DESC')->get();

        $data = User::whereHas('roles', function ($q) {
            $q->where('name', 'driver');
        })->orderBy('id', 'DESC')->get()
            ->each(function ($user) {
                $user->documentCount = $user->document->count();
                $user->vehicleCount = $user->driverVehicle->count();
            });
        // return $data;
        return view('admin.driver.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.driver.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users,email|email',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/users/driver.jpg';
        }


        /**generate random password */
        $password = Str::random(10);
        $driver = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            'image' => $image,
            'role_id' => 2,
            // 'password' => Hash::make($password),
        ]);

        /** assign the role  */
        $driver->roles()->sync(3);

        $message['email'] = $request->email;
        $message['password'] = $password;
        Mail::to($request->email)->send(new SignupPasswordSend($password));

        try {
            // Mail::to($request->email)->send(new UserLoginPassword($message));
            return redirect()->route('driver.index')->with(['status' => true, 'message' => 'Driver   Created successfully.']);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()
                ->with(['status' => false, 'message' => $th->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // return "running";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return view('admin.driver.edit', compact('data'));
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
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $driver = User::find($id);
        if ($request->hasfile('image')) {
            $destination = 'public/admin/assets/images/users' . $driver->image;
            if (File::exists($destination) || File::exists($driver->image)) {
                File::delete($destination);
                File::delete($driver->image);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = $driver->image;
        }


        $driver->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
        ] + ['image' => $image]);
        return redirect()->route('driver.index')->with(['status' => true, 'message' => 'Driver Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete associated data
        // if(Document::where('user_id', $id) ||  Review::where('user_id', $id) || Question::where('user_id', $id))
        // {
        //     User::destroy($id);
        // }
        // Document::where('user_id', $id)->delete();
        // Review::where('user_id', $id)->delete();
        // Question::where('user_id', $id)->delete();
        // // Then delete the user
        User::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Driver Deleted successfully']);
    }

    public function status($id)
    {
        $data = User::find($id);
        $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
        $status = $data->is_active;
        Mail::to($data->email)->send(new VerifyUserEmail($status));
        return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully']);
    }



    // public function show_documents()
    // {
    //     $data = User::whereHas('roles', function ($q) {
    //         $q->where('title', 'driver');
    //     })->orderBy('id', 'DESC')->get();
    //     return view('admin.driver.index', compact('data'));
    // }
}
