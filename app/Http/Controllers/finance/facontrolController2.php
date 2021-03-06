<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;

class facontrolController2 extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $yearperiod = DB::table('sysdb.period')->get();
        return view('finance.FA.facontrol2.facontrol2',compact('yearperiod'));
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            // case 'edit':
            //     return $this->defaultEdit($request);
            // case 'del':
            //     return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }
}
