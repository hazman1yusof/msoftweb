<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class ComputeridController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('setup.computerid.computerid');
    }

    public function table(Request $request)
    {   
        switch($request->action){

        }
    }

    public function form(Request $request)
    {  
        switch($request->action){
            case 'setcompid': return $this->setcompid($request);break;
        }
    }

    public function setcompid(Request $request){
        if(!empty($request->computerid)){
            $request->session()->put('computerid',$request->computerid);
        }
        dd(session('computerid'));
    }
}
