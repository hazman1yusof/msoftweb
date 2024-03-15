<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ConsolidationCostCenterControllerDtl extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

     public function form(Request $request)
    {   

        DB::enableQueryLog();
        switch($request->oper){
            case 'add': 
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'edit_all':
                return $this->edit_all($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }
    public function add(Request $request){

        DB::beginTransaction();
        try {

            $cc_code = DB::table('finance.glcondeptgrp')
                        ->where('compcode','=',session('compcode'))
                        ->where('code','=',$request->code)
                        ->first();
            
            $code = $request->groupcode;
            //dd($code);
       
            $sqlln = DB::table('finance.glcondept')->select('lineno_')
                ->where('compcode','=',session('compcode'))
                ->where('groupcode','=',$code)
                ->count('lineno_');

            $li=intval($sqlln)+1;

            DB::table('finance.glcondept')
                ->insert([  
                    'compcode' => session('compcode'),
                    'lineno_' => $li,
                    'groupcode' => $code,
                    'recstatus' => 'ACTIVE',
                    'costcodefr' => $request->costcodefr,
                    'costcodeto' => $request->costcodeto,
                    // 'computerid' => session('computerid'),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();
        try {

            DB::table('finance.glcondept')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([  
                    'recstatus' => 'ACTIVE',
                    'costcodefr' => $request->costcodefr,
                    'costcodeto' => $request->costcodeto,
                    // 'computerid' => session('computerid'),
                    'upduser' => strtoupper(session('username')),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }

    public function del(Request $request){

        DB::beginTransaction();
        try {

            DB::table('finance.glcondept')
                ->where('compcode','=',session('compcode'))
                ->where('groupcode','=',$request->groupcode)
                ->where('idno','=',$request->idno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }
}

