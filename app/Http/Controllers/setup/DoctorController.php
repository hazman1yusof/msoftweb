<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;

class DoctorController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('setup.doctor.doctor');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                if($request->appointment == '1'){
                    DB::table('hisdb.apptresrc')->insert([
                        'compcode' => session('compcode'),
                        'resourcecode' => $request->doctorcode,
                        'description' => $request->doctorname,
                        'recstatus' => 'A',
                        'adduser' => session('username'),
                        'adddate' => now()
                    ]);
                }
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }
}