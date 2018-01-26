<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;

class AppointmentController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "resourcecode";
    }

    public function show(Request $request)
    {   
        return view('hisdb.apptrsc.apptrsc');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function getEvent(Request $request){
        $select = DB::table('hisdb.apptbook')->where('doctor','=',$request->drrsc)->get();
        return $select;
        // $sql = "SELECT id, title, start, end, color FROM events WHERE doctor='$drrsc'";
        return "asdasd";
    }
}
