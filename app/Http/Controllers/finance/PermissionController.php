<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;

class PermissionController extends defaultController
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
        return view('finance.permission.permission');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                $this->check_duplicate($request);
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function check_duplicate(Request $request){
        $authdtl_obj = DB::table('finance.permission')
            ->where('compcode','=',session('compcode'))
            ->where('authorid','=',$request->authorid);

        if($authdtl_obj->exists()){
            throw new \Exception('This User already exist', 500);
        }
    }

    // public function check_duplicate(Request $request){
    //     $authdtl_obj = DB::table('material.authdtl')
    //         ->where('authorid','=',$request->dtl_authorid)
    //         ->where('deptcode','=',$request->dtl_deptcode)
    //         ->where('recstatus','=',$request->dtl_recstatus)
    //         ->where('trantype','=',$request->dtl_trantype);

    //     if($authdtl_obj->exists()){
    //         throw new \Exception('User permission Detail already exist', 500);
    //     }
    // }
}