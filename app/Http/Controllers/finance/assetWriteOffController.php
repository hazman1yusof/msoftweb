<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class assetWriteOffController extends defaultController
{   

    var $table;
    //var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "assetcode";
    }

    public function show(Request $request)
    {   
        return view('finance.FA.assetWriteOff.assetWriteOff');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_WriteOff':
                return $this->save_WriteOff($request);
            default:
                return 'error happen..';
        }
    }

    public function save_WriteOff($request){

        DB::beginTransaction();

        try {
            $recno = $this->recno('FA','WOF');

            //AMIK DARI SYSPARAM FA,TRF 

            DB::table('finance.fatran')
                ->insert([
                    'compcode' => session('compcode'),
                    'trantype' => 'WOF',
                    'assetcode' => $request->assetcode,
                    'assettype' => $request->assettype,
                    'assetno' => $request->assetno,
                    'auditno' => $recno,
                    'deptcode' => $request->currdeptcode,
                    'curloccode' => $request->newloccode,
                    'trandate' => $request->date,
                    'amount' => $request->cost,
                    'amount1' => $request->acuum,
                    'reference' => $request->reason,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            DB::table('finance.faregister')
                ->where('idno',$request->idno)
                ->update([
                    'recstatus' => 'WRITE-OFF'
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

}