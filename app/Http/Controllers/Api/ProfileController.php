<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function update(Request $request ,$id)
    {
        $validator = Validator::make($request->all(),
        [
            'fname'=>'required',
            'lname'=>'required',
            'phone'=>'required',
            'email'=>'required|email',
        ]);
        if($validator->fails())
        {
            return $this->sendError($validator->errors()->first());
        }
        $user = User::find($id);
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

        $user->update([
        'fname' => $request->fname,
        'lname' => $request->lname,
        'phone' => $request->phone,
        'email' => $request->email,
        'image' => $image,
         ]);
        return response()->json([
         'message'=>'your profile is updated successfully.',
         'status'=>'success'
        ],200);
    }
}
