<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ConsolidationAccDtlController extends defaultController
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

            $consolecode = DB::table('finance.glconsol')
                        ->where('compcode','=',session('compcode'))
                        ->where('code','=',$request->code)
                        ->first();

            $code = $request->code;
            //dd($code);
           
            $sqlln = DB::table('finance.glcondtl')->select('lineno_')
                ->where('compcode','=',session('compcode'))
                ->where('code','=',$code)
                ->count('lineno_');

            $li=intval($sqlln)+1;

            DB::table('finance.glcondtl')
                ->insert([  
                    'compcode' => session('compcode'),
                    'lineno_' => $li,
                    'code' => $code,
                    'recstatus' => 'ACTIVE',
                    'acctfr' => $request->acctfr,
                    'acctto' => $request->acctto,
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

            DB::table('finance.glcondtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('lineno_','=',$request->lineno_)
                ->where('code','=',$request->code)
                ->update([  
                    'compcode' => session('compcode'),
                    'recstatus' => 'ACTIVE',
                    'acctfr' => $request->acctfr,
                    'acctto' => $request->acctto,
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

            DB::table('finance.glcondtl')
                ->where('compcode','=',session('compcode'))
                ->where('code','=',$request->code)
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

