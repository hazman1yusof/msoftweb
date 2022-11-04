<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\customer;
use DB;
use Auth;
use Hash;
use Session;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $url = redirect()->back()->getTargetUrl();

        $id = substr($url, strrpos($url, '/') + 1);

        // if($id == 'emergency'){
        //     $user = User::where('username','farid')
        //                 ->where('password','farid');


        //     Auth::login($user->first(),false);

        //     return back();
        // }

        return view('login');
    }

    public function login(Request $request)
    {   
        // $remember = (!empty($request->remember)) ? true:false;
        $remember = false;
        // $user = User::where('username','=',$request->username);

        // $compcode = DB::table('sysdb.company')
        //             ->first();

        // $request->session()->put('compcode', $compcode->compcode);


        $user = User::where('username',request('username'))
                    ->where('password',request('password'));

        if($user->exists()){
            $request->session()->put('username', request('username'));
            $request->session()->put('compcode', $user->first()->compcode);
            
            // if($user->first()->status == 'Inactive'){
            //     return back()->withErrors(['Sorry, your account is inactive, contact admin to activate it again']);
            // }
            
            if ($request->password == $user->first()->password) {
                Auth::login($user->first(),$remember);
                if(Auth::user()->groupid == 'patient'){
                    return redirect('/preview');
                }else if(strtoupper(Auth::user()->groupid) == 'DOCTOR'){
                    return redirect('/doctornote');
                }else if(strtoupper(Auth::user()->groupid) == 'REHABILITATION'){
                    $this->setsession_($request);
                    return redirect('/doctornote');
                }else if(strtoupper(Auth::user()->groupid) == 'PHYSIOTERAPHY'){
                    $this->setsession_($request);
                    return redirect('/doctornote');
                }else if(strtoupper(Auth::user()->groupid) == 'DIETICIAN'){
                    return redirect('/doctornote');
                }else if(strtoupper(Auth::user()->groupid) == 'CLINICAL'){
                    return redirect('/emergency');
                }else if(strtoupper(Auth::user()->groupid) == 'ADMIN'){
                    return redirect('/dashboard');
                }else if(strtoupper(Auth::user()->groupid) == 'MR'){
                    return redirect('/dashboard');
                }else if(strtoupper(Auth::user()->groupid) == 'REGISTER'){
                    return redirect('/mainlanding');
                }else{
                    return redirect('/dashboard');
                }
            }else{
                return back()->withErrors(['Try again, Password entered incorrect']);
            }
        }else{
            return back()->withErrors(['Try again, Username or Password incorrect']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        // dd('sdsd');
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }

    public function setsession_(Request $request){
        $chggroup = DB::table('sysdb.sysparam')
                        ->where('compcode','=',Auth::user()->compcode)
                        ->where('source','=','OE')
                        ->where('trantype','=',Auth::user()->groupid);

        if($chggroup->exists()){
            $chggroup = $chggroup->first();
            $request->session()->put('chggroup', $chggroup->pvalue1);
        }

    }
}
