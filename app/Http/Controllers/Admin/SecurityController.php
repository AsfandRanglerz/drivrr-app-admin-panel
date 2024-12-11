<?php

namespace App\Http\Controllers\admin;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;
use App\Models\TermCondition;
use App\Http\Controllers\Controller;

class SecurityController extends Controller
{
    public function PrivacyPolicy(){
        $data=PrivacyPolicy::first();
        return view('admin.privacyPolicy.index',compact('data'));
    }
    public function PrivacyPolicyEdit(){
        $data=PrivacyPolicy::first();
        return view('admin.privacyPolicy.edit',compact('data'));
    }
    public function PrivacyPolicyUpdate(Request $request){
        $request->validate([
            'description'=>'required'
        ]);

        $data=PrivacyPolicy::first();
        PrivacyPolicy::find($data->id)->update($request->all());
        return redirect('/admin/Privacy-policy')->with(['status'=>true, 'message' => 'Privacy Policy Updated Successfully']);
    }
    public function TermCondition(){
        $data=TermCondition::first();
        return view('admin.termCondition.index',compact('data'));
       }
       public function TermConditionEdit(){
           $data=TermCondition::first();
           return view('admin.termCondition.edit',compact('data'));
       }
    public function TermConditionUpdate(Request $request){
        $request->validate([
            'description'=>'required'
        ]);

        $data=TermCondition::first();
        TermCondition::find($data->id)->update($request->all());
        return redirect('/admin/term-condition')->with(['status'=>true, 'message' => 'Term&Condition Updated Successfully']);
    }

    public function AboutUs(){
        $data=AboutUs::first();
        return view('admin.aboutus.index',compact('data'));
       }
       public function AboutUsEdit(){
           $data=AboutUs::first();
           return view('admin.aboutus.edit',compact('data'));
       }
    public function AboutUsUpdate(Request $request){
        $request->validate([
            'description'=>'required'
        ]);

        $data=AboutUs::first();
        AboutUs::find($data->id)->update($request->all());
        return redirect('/admin/about-us')->with(['status'=>true, 'message' => 'Term&Condition Updated Successfully']);
    }

    public function webViewAboutUs()
    {
        $data=AboutUs::first();
        return view('security.aboutus',compact('data'));
    }

    public function webViewPrivacyPolicy()
    {
        $data=PrivacyPolicy::first();
        return view('security.webView',compact('data'));
    }

    public function webViewTermCondition()
    {
        $data=TermCondition::first();
        return view('security.termcondition',compact('data'));
    }
    public function contactUs()
    {
        return view('admin.contactus.index');
    }
}
