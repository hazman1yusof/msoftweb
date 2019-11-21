<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class AuthorizationController extends defaultController
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
        return view('material.Authorization.authorization');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                // $this->check_duplicate($request);
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
        $authdtl_obj = DB::table('material.authdtl')
            ->where('authorid','=',$request->dtl_authorid)
            ->where('deptcode','=',$request->dtl_deptcode)
            ->where('recstatus','=',$request->dtl_recstatus)
            ->where('trantype','=',$request->dtl_trantype);

        if($authdtl_obj->exists()){
            throw new \Exception('User Authorization Detail already exist', 500);
        }
    }
}