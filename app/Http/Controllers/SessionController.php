<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\sysdb\Company;
use App\User;
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
    		return redirect()->home();
    	}else{
    		return back();
    	}
    }

    public function destroy(){
    	Session::flush();

    	$company = company::all();
        return view('init.login',compact("company"));
    }
}
