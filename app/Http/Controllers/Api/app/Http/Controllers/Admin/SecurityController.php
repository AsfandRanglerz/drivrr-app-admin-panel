<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use App\Models\TermCondition;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function PrivacyPolicy()
    {
        $data = PrivacyPolicy::first();
        return view('admin.privacyPolicy.index', compact('data'));
    }
    public function PrivacyPolicyEdit()
    {
        $data = PrivacyPolicy::first();
        return view('admin.privacyPolicy.edit', compact('data'));
    }
    public function PrivacyPolicyUpdate(Request $request, $id)
    {
        $request->validate([
            'description' => 'required',
        ]);

        // $data=PrivacyPolicy::first();
        PrivacyPolicy::find($id)->update($request->all());
        return redirect('/admin/Privacy-policy')->with(['status' => true, 'message' => 'Updated Successfully']);
    }

    public function getPrivacyPolicy(){
        $data = PrivacyPolicy::first();
        return view('admin.privacyPolicy.webView', compact('data'));

    }
    public function TermCondition()
    {
        $data = TermCondition::first();
        return view('admin.termCondition.index', compact('data'));
    }
    public function TermConditionEdit()
    {
        $data = TermCondition::first();
        return view('admin.termCondition.edit', compact('data'));
    }
    public function TermConditionUpdate(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $data = TermCondition::first();
        TermCondition::find($data->id)->update($request->all());
        return redirect('/admin/term-condition')->with(['status' => true, 'message' => 'Updated Successfully']);
    }

    public function AboutUs()
    {
        $data = AboutUs::first();
        return view('admin.about.index', compact('data'));
    }
    public function AboutUsEdit($id)
    {
        $data = AboutUs::find($id);
        return view('admin.about.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function AboutUsUpdate(Request $request, $id)
    {
        AboutUs::find($id)->update(['description' => $request->description]);
        return redirect()->route('aboutUs.index')->with(['status' => true, 'message' => 'Updated Successfully']);

    }
    public function getAboutUs(){
        $data = AboutUs::first();
        return view('admin.about.webView', compact('data'));

    }

}
