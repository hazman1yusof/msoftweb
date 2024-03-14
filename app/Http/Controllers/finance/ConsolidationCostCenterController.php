<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class ConsolidationCostCenterController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.consolidationCostCenter.consolidationCostCenter');
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
            case 'addDetail':
                return $this->addDetail($request);
            default:
                return 'error happen..';
        }
    }

    public function addDetail(Request $request){

        DB::beginTransaction();
        try {

            $consolecode = DB::table('finance.glcondeptgrp')
                        ->where('compcode','=',session('compcode'))
                        ->where('code','=',$request->code);

            // if($costcode->exists()){
            //     throw new \Exception("Record Duplicate");
            // }
            $lineno_ = DB::table('finance.glcondept') 
                ->where('compcode','=',session('compcode'))
                ->where('code','=',$consolecode->code)->max('lineno_');

            if($lineno_ == null){
                $lineno_ = 1;
            }else{
                $lineno_ = $lineno_+1;
            }

            DB::table('finance.glcondept')
                ->insert([  
                    'compcode' => session('compcode'),
                    'lineno_' => $lineno_,
                    'code' => strtoupper($consolecode->code),
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
}
