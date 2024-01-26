<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Introvideo;

class IntrovideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Introvideo::first();
        return view('admin.pages.introVideo.index',['data'=>$data]);
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
        $request->validate([
            'video'=>'required',

        ]);
        $fileName = $request->video->getClientOriginalName();
        $filePath = 'video/'. $fileName;
        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));
        // File URL to access the video in frontend
        if ($isFileUploaded) {
            $video=new Introvideo();
            $video->video = $filePath;
            $video->save();
            return redirect()->route('intro-video.index')
            ->with(['status' => true, 'message' => 'Video created successfully']);
        }
            return back()
            ->with(['status' => false, 'message'=>'Unexpected error occured']);
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
        $this->validate($request, [
            'video' => 'required|file',
        ]);
        $fileName = $request->video->getClientOriginalName();
        $filePath = 'video/'. $fileName;
        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));
        // File URL to access the video in frontend
        if ($isFileUploaded) {
            $video=Introvideo::find($id);
            $video->video = $filePath;
            $video->update();
            return redirect("/admin/pages/intro-video")
            ->with(['status' => true, 'message' => 'Video updated successfully']);
        }
            return back()
            ->with(['status' => false, 'message'=>'Unexpected error occured']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
