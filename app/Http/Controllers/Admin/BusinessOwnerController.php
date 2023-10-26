<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerifyUserEmail;
use App\Mail\SignupPasswordSend;
use Illuminate\Support\Facades\Mail;

class BusinessOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::whereHas('roles', function ($q) {
            $q->where('name', 'Owner');
        })->orderBy('id', 'DESC')->get();
        // return $data;
        return view('admin.owner.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.owner.create');
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
            // 'password' => 'required|confirmed',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        }
        else {
            $image = 'public/admin/assets/images/users/owner.png';
        }

        /**generate random password */
        $password = Str::random(10);
        $owner = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($password),
            // 'email' => $request->email,
            // 'role_id'=> 1,
        ] + ['image' => $image]);
        /* assign the role  */
        $owner->roles()->sync(2);
        $message['email'] = $request->email;
        $message['password'] = $password;
        // return $request->email;
        Mail::to($request->email)->send(new SignupPasswordSend($password));
        // Mail::to($user->email)->send(new RejectDocumentInfo($reason));

        try {
            // Mail::to($request->email)->send(new UserLoginPassword($message));
            return redirect()->route('businessOwner.index')->with(['status' => true, 'message' => 'Business Owner Created successfully.']);
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
    public function show($id)
    {
        //
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
        return view('admin.owner.edit', compact('data'));
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
        // return $request;
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $owner = User::find($id);
        if ($request->hasfile('image')) {
            $destination = 'public/admin/assets/images/users' . $owner->image;
            if (File::exists($destination) || File::exists($owner->image)) {
                File::delete($destination);
                File::delete($owner->image);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = $owner->image;
        }

        $owner->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            // 'last_name' => $request->last_name,
            // 'designation' => $request->designation,
        ] + ['image' => $image]);

        return redirect()->route('businessOwner.index')->with(['status' => true, 'message' => 'Business Owner Updated successfully.']);
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
        return redirect()->back()->with(['status' => true, 'message' => 'Business Owner Deleted successfully.']);
    }

    public function status($id)
    {
        $data = User::find($id);
        $user['fname'] = $data->fname;
        $user['email'] = $data->email;
        // return $user;
        $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
        $status = $data->is_active;
        Mail::to($data->email)->send(new VerifyUserEmail($status));
        return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully.']);
    }
}
