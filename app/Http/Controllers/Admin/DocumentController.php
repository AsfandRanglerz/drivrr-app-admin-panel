<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\VerifyDocument;
use App\Mail\RejectDocumentInfo;
use App\Models\Document;
use Illuminate\Support\Facades\Mail;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = User::with('document')->find($id);
        // $counter = Document::where('user_id',$id)->count();
        // return [$counter];
        return view('admin.driver.document.index', compact(['data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $data = User::find($id);
        return view('admin.driver.document.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'image' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/users/1675332882.jpg';
        }

        $document = Document::create([
            'user_id' => $id,
            'name' => $request->name,
            'image' => $image,
        ]);
        return redirect()->route('document.index', $id)->with(['status' => true, 'message' => 'Document Created successfully.']);
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
        $document = Document::find($id);
        return view('admin.driver.document.edit', compact('document'));
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
            'name' => 'required',
        ]);
        $document = Document::find($id);
        if ($request->hasfile('image')) {
            $destination = 'public/admin/assets/images/users' . $document->image;
            if (File::exists($destination) || File::exists($document->image)) {
                File::delete($destination);
                File::delete($document->image);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = $document->image;
        }
        $document->name = $request->name;
        $document->image = $image;
        $document->update();
        return redirect()->route('document.index', $document->user_id)->with(['status' => true, 'message' => 'Document Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Document::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Document deleted Successfully.']);
    }



    // public function status($id,$key)
    // {
    //     $data = Document::find($id);
    //     $user = User::find($key);
    //     $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
    //     $verify['is_active'] = $data->is_active;
    //     $verify['name'] = $data->name;
    //     // return $verify;
    //     Mail::to($user->email)->send(new VerifyDocument($verify));
    //     return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully.']);
    // }

    public function status(Request $request, $id, $key)
    {
        $data = Document::find($id);
        $user = User::find($key);
        $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
        $verify['is_active'] = $data->is_active;
        $verify['name'] = $data->name;
        $reason = $request->reason;
        if ($verify['is_active'] == 0) {
            // return $reason;
            Mail::to($user->email)->send(new RejectDocumentInfo($reason));
            return redirect()->back()->with(['status' => true, 'message' => 'Document rejection reason is sended.']);
        } else {
            Mail::to($user->email)->send(new VerifyDocument($verify));
            return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully.']);
        }
    }
}
