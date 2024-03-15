<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class ConsolidationAccController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.consolidationAcc.consolidationAcc');
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

    public function edit(Request $request){

        DB::beginTransaction();
        try {

            DB::table('finance.glconsol')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('code','=',$request->code)
                ->update([  
                    'compcode' => session('compcode'),
                    'recstatus' => 'ACTIVE',
                    'code' => $request->code,
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

}
