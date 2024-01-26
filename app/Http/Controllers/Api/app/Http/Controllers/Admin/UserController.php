<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Session;

use App\Models\EntertainerDetail;

use App\Models\TalentCategory;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifiedUser;





class UserController extends Controller

{

    public function index(){

        $data['recruiter']=User::where('role','recruiter')->with(['events' => function ($query) {$query->select('user_id','title'); }])->latest()->get();

        $data['venue']=User::where('role','venue_provider')->with(['venues' => function ($query) {$query->with('venueCategory'); }])->latest()->get();

        // $data['entertainer'] = TalentCategory::with('items')->get();

        $data['entertainer']=User::select('id','name','email','role','phone','dob','country','city','gender','nationality','created_at','is_verify','image')->where('role','entertainer')->with(['entertainerDetail' => function ($query) {$query->with('talentCategory'); }])->latest()->get();

        // $data['entertainer']=TalentCategory::find(2)->user;

        // dd($data['entertainer']);

        // for ($i=0; $i < count($data['entertainer'][0]['entertainerDetail']) ; $i++) {

        //     $dat[] = json_decode($data['entertainer'][0]['entertainerDetail'][$i]['talentCategory'],true);

        //     implode(',',array_column($dat, 'category'));

        // }

        return view('admin.users.index',['data'=>$data]);

    }
    public function verify($id){
        $data = User::find($id);
        // return $data;
        if($data->is_verify=='0'){
         $data->update(['is_verify' => $data->status == 0 ? '1' : '0']);
         try {
            $message['is_verify']=$data->is_verify;
            $message['name'] = $data->name;
            Mail::to($data->email)->send(new VerifiedUser($message));
                return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
            } catch (\Throwable $th) {
                dd($th->getMessage());
                return back()
                ->with(['status' => false, 'message'=>'Unexpected error occured']);
            }
        }
        else{
         $data->update(['is_verify' => $data->status == 1 ? '1' : '0']);
         try {
            $message['is_verify']=$data->is_verify;
            $message['name'] = $data->name;
            Mail::to($data->email)->send(new VerifiedUser($message));
                return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
            } catch (\Throwable $th) {
                dd($th->getMessage());
                return back()
                ->with(['status' => false, 'message'=>'Unexpected error occured']);
            }

        }
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
     }


}

