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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDocument($id)
    {
        $document = Document::where('user_id', $id)->get();
        $json_data["data"] = $document;
        return json_encode($json_data);
    }
    public function indexDocument($id)
    {
        $data = User::with('document')->find($id);
        // $counter = Document::where('user_id',$id)->count();
        // return [$counter];
        return view('admin.driver.document.index', compact(['data', 'id']));
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
    public function createDocument(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,jpg,png|max:1048',
                'is_active' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $document = new Document($request->only(['is_active']));
            $document->user_id = $id;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users/'), $filename);
                $document->image = 'public/admin/assets/images/users/' . $filename;
            }
            $document->save();
            return response()->json(['alert' => 'success', 'message' => 'Document Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Document!' . $e->getMessage()], 500);
        }
    }
    public function showDocument($id)
    {
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['alert' => 'error', 'message' => 'Document Not Found'], 500);
        }
        return response()->json($document);
    }


    public function updateDocument(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $document = Document::findOrFail($id);
            $document->fill($request->only(['is_active']));
            if ($request->hasFile('image')) {
                $oldImagePath = $document->image;
                if ($document->image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users/'), $filename);
                $document->image = 'public/admin/assets/images/users/' . $filename;
            }
            $document->save();
            return response()->json(['alert' => 'success', 'message' => 'Document Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        $imagePath =  $document->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $document->delete();
        return response()->json(['alert' => 'success', 'message' => 'Document Deleted SuccessFully!']);
    }
}
