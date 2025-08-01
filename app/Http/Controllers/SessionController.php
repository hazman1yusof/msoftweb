<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\sysdb\Company;
use App\User;
use DB;
use Auth;
use Hash;
use Carbon\Carbon;
use Session;

class SessionController extends Controller
{
    public function __construct(){
    	// $this->middleware('guest', ['except'=>'destroy']);
    }

 	public function create(){
    	$company = company::all();
        $bgpic_ = DB::table('sysdb.sysparam')
                        ->where('compcode','all')
                        ->where('source','def')
                        ->where('trantype','loginbg');

        if(!$bgpic_->exists()){
            $bgpic = './img/carousel/Supply-Change-Management.jpg';
        }else{
            $bgpic = $bgpic_->first()->pvalue1;
        }

    	return view('init.login',compact("company",'bgpic'));
    }

    public function create2(){
        $company = company::all();

        return view('init.login2',compact("company"));
    }

    public function qrcode(){
        return view('init.qrcode');
    }

    public function store(Request $request){
        if(empty(request('computerid'))){
            return back();
        }

    	$user = User::where('username',request('username'))
    				->where('password',request('password'))
    				->where('compcode',request('cmb_companies'))
    				->first();
    	if($user){
    		Auth::login($user);
            $request->session()->put('compcode', request('cmb_companies'));
            $request->session()->put('username', request('username'));
            $request->session()->put('deptcode', $user->dept);
            if(!empty(request('computerid'))){
                $request->session()->put('computerid',request('computerid'));
            }

            $doctor = DB::table('hisdb.doctor')->where('loginid','=',$user->username);

            if($doctor->exists()){
                $request->session()->put('isdoctor', $user->username);
            }

            if($user->dept != ''){
                $units = DB::table('sysdb.department')
                    ->select('sector')
                    ->where('deptcode','=',$user->dept)
                    ->where('compcode','=',request('cmb_companies'))
                    ->first();
                $request->session()->put('unit', $units->sector);
            }else{
                $units = DB::table('sysdb.sector')
                    ->select('sectorcode')
                    ->where('compcode','=',request('cmb_companies'))
                    ->first();
                $request->session()->put('unit', $units->sectorcode);
            }

            if(strtoupper($user->programmenu) == 'WAREHOUSE'){
                return redirect('/warehouse');
            }else if(strtoupper($user->programmenu) == 'IMPLANT'){
                return redirect('/implant');
            }else if(strtoupper($user->programmenu) == 'KHEALTH'){
                return redirect('/khealth');
            }

            if($user->groupid == 'patient'){
                return redirect('/apptrsc?TYPE=DOC');
            }
            
            if(request('myurl') == '192.168.0.108'){
                return redirect('/home');
            }else if(!empty($request->mobile) && $request->mobile == 'true'){
                return redirect('/mobile');
            }else{
                return redirect()->home();
            }
    	}else{
    		return back();
    	}
    }

    public function qrcode_prereg(Request $request){
        if($request->select == 'ic'){
            $validatedData = $request->validate([
                'ic' => 'required|max:20|min:10',
            ]);
        }else{
            $validatedData = $request->validate([
                'idnumber' => 'required|max:20|min:5',
            ]);
        }

        DB::beginTransaction();
        
        try {

            if($request->select == 'ic'){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('Active','1')
                            ->where('Newic','=', $request->ic);

                if($pat_mast->exists()){
                    $pat_mast_obj = $pat_mast->first();
                    
                }else{
                    // pleae register at counter alert
                    return redirect()->back()->withErrors('No I/C in Database, please register at the counter first');
                }
            }else{
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('Active','1')
                            ->where('idnumber','=', $request->idnumber);

                if($pat_mast->exists()){
                    $pat_mast_obj = $pat_mast->first();
                    
                }else{
                    // pleae register at counter alert
                    return redirect()->back()->withErrors('No passport / idnumber in Database, please register at the counter first');
                }
            }

            $mrn = $pat_mast_obj->MRN;
            $episno = $pat_mast_obj->Episno;

            if(intval($episno) < 1){
                return redirect()->back()->withErrors('Episode not registered yet, please register at the counter first');
            }

            //check if date,mrn duplicate
            $pre_episode = DB::table('hisdb.pre_episode')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',$mrn)
                                ->whereDate('adddate',Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

            if(!$pre_episode->exists()){
                DB::table("hisdb.pre_episode")
                    ->insert([
                        "compcode" => '9A',
                        "mrn" => $mrn,
                        "episno" => 0,
                        "adddate" => Carbon::now("Asia/Kuala_Lumpur"),
                        "adduser" => 'SYSTEM-QRCODE',
                        'Newic'    => $pat_mast_obj->Newic,
                        'Name'    => $pat_mast_obj->Name,
                        'telhp'    => $pat_mast_obj->telhp,
                        'telno'    => $pat_mast_obj->telh,
                        'apptdate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);     

            }else{
                return redirect()->back()->withSuccess("You already registered today");
            }

            DB::commit();

            return redirect()->back()->withSuccess("Thank you, you have succesfully pre-registered");

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function destroy(){
    	Session::flush();

    	$company = company::all();
        return redirect('/home');
        // return view('init.login',compact("company"));
    }

    public function changeSessionUnit(Request $request){

        // dd('asd');
        $dept = DB::table('sysdb.department')
                    ->where('deptcode','=',$request->deptcode)
                    ->where('compcode','=',session('compcode'));

        if($dept->exists()){
            $dept = $dept->first();

            $sector = DB::table('sysdb.sector')
                    ->where('sectorcode','=',$dept->sector)
                    ->where('compcode','=',session('compcode'));

            if($sector->exists()){
                $request->session()->put('deptcode', $request->deptcode);
                $request->session()->put('unit', $dept->sector);
            }
        }

        // $user = User::where('username',session('username'))
        //             ->where('compcode',session('compcode'))
        //             ->first();

        return redirect('/home');
    }
}
