<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\sysdb\Company;
use App\User;
use DB;
use Auth;
use Hash;
use Session;

class SessionController extends Controller
{
    public function __construct(){
    	$this->middleware('guest', ['except'=>'destroy']);
    }

 	public function create(){
    	$company = company::all();

    	return view('init.login',compact("company"));
    }

    public function create2(){
        $company = company::all();

        return view('init.login2',compact("company"));
    }

    public function store(Request $request){
    	$user = User::where('username',request('username'))
    				->where('password',request('password'))
    				->where('compcode',request('cmb_companies'))
    				->first();
    	if($user){
    		Auth::login($user);
            $request->session()->put('compcode', request('cmb_companies'));
            $request->session()->put('username', request('username'));
            $request->session()->put('deptcode', $user->deptcode);

            if($user->deptcode != ''){
                $units = DB::table('sysdb.department')
                    ->select('sector')
                    ->where('deptcode','=',$user->deptcode)
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

            if($user->groupid == 'patient'){
                return redirect('/apptrsc?TYPE=DOC');
            }
            
            if(request('myurl') == '192.168.0.108'){
                return redirect()->home_ofis();
            }else{
                return redirect()->home();
            }
    	}else{
    		return back();
    	}
    }

    public function destroy(){
    	Session::flush();

    	$company = company::all();
        return redirect()->home();
        // return view('init.login',compact("company"));
    }
}
