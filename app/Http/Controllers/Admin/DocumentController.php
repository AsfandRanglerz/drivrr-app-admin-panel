<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Document;
use App\Mail\VerifyDocument;
use Illuminate\Http\Request;
use App\Mail\RejectDocumentInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class DocumentController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index($id)
    // {
    //     $data = User::with('document')->find($id);
    //     // $counter = Document::where('user_id',$id)->count();
    //     // return [$counter];
    //     return view('admin.driver.document.index', compact(['data']));
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create(Request $request, $id)
    // {
    //     $data = User::find($id);
    //     return view('admin.driver.document.create', compact('data'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request, $id)
    // {
    //     // return $request;
    //     $request->validate([
    //         'name' => 'required',
    //         'image' => 'required',
    //     ]);

    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extension;
    //         $file->move(public_path('admin/assets/images/users/'), $filename);
    //         $image = 'public/admin/assets/images/users/' . $filename;
    //     } else {
    //         $image = 'public/admin/assets/images/users/1675332882.jpg';
    //     }

    //     $document = Document::create([
    //         'user_id' => $id,
    //         'name' => $request->name,
    //         'image' => $image,
    //     ]);
    //     return redirect()->route('document.index', $id)->with(['status' => true, 'message' => 'Document Created Successfully.']);
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     $document = Document::find($id);
    //     return view('admin.driver.document.edit', compact('document'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //     ]);
    //     $document = Document::find($id);
    //     if ($request->hasfile('image')) {
    //         $destination = 'public/admin/assets/images/users' . $document->image;
    //         if (File::exists($destination) || File::exists($document->image)) {
    //             File::delete($destination);
    //             File::delete($document->image);
    //         }
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extension;
    //         $file->move('public/admin/assets/images/users', $filename);
    //         $image = 'public/admin/assets/images/users/' . $filename;
    //     } else {
    //         $image = $document->image;
    //     }
    //     $document->name = $request->name;
    //     $document->image = $image;
    //     $document->update();
    //     return redirect()->route('document.index', $document->user_id)->with(['status' => true, 'message' => 'Document Updated Successfully.']);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     Document::destroy($id);
    //     return redirect()->back()->with(['status' => true, 'message' => 'Document Deleted Successfully.']);
    // }



    // // public function status($id,$key)
    // // {
    // //     $data = Document::find($id);
    // //     $user = User::find($key);
    // //     $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
    // //     $verify['is_active'] = $data->is_active;
    // //     $verify['name'] = $data->name;
    // //     // return $verify;
    // //     Mail::to($user->email)->send(new VerifyDocument($verify));
    // //     return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully.']);
    // // }

    // public function status(Request $request, $id, $key)
    // {
    //     $status = $request->check;
    //     // return $status;
    //     $data = Document::find($id);
    //     $user = User::find($key);
    //     $data->is_active = $status;
    //     $data->save();
    //     $verify['is_active'] = $data->is_active;
    //     $verify['name'] = $data->name;
    //     $reason = $request->reason;
    //     if ($verify['is_active'] == 2) {
    //         // return $reason;
    //         Mail::to($user->email)->send(new RejectDocumentInfo($reason));
    //         return redirect()->back()->with(['status' => true, 'message' => 'Document Rejection Reason is Sended.']);
    //     } else {
    //         Mail::to($user->email)->send(new VerifyDocument($verify));
    //         return redirect()->back()->with(['status' => true, 'message' => 'Document Approved Successfully.']);
    //     }
    // }
    public function documentData($id)
    {
        $documents = Document::where('user_id', $id)->latest()->get();
        $json_data["data"] = $documents;
        return json_encode($json_data);
    }

    public function documentIndex($id)
    {
        $documents = Document::where('user_id', $id)->latest()->get();
        return view('admin.driver.document.index', compact('documents'));
    }
    public function documentCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,jpg,png|max:1048'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $document = new Document($request->only(['name']));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/DocumDocument'), $filename);
                $document->image = 'public/admin/assets/images/DocumDocument/' . $filename;
            }
            $document->save();
            return response()->json(['alert' => 'success', 'message' => 'DocumDocument Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while CreatingDocument!' . $e->getMessage()], 500);
        }
    }

    public function showDocument($id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['alert' => 'error', 'message' => 'DocumDocument Not Found'], 500);
        }
        return response()->json($document);
    }
    public function updateDocument(Request $request, $id)
    {
        try {
            $document = Document::findOrFail($id);
            $document->fill($request->only(['name']));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('public/admin/assets/images/users'), $filename);
                $document->image = 'public/admin/assets/images/users/' . $filename;
            }
            $document->save();
            return response()->json(['alert' => 'success', 'message' => 'DocumDocument Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return response()->json(['alert' => 'success', 'message' => 'DocumDocument Deleted SuccessFully!']);
    }
}
