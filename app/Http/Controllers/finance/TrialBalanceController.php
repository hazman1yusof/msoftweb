<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\trialBalanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class TrialBalanceController extends defaultController
{   

    var $table;
   // var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.trialBalance.trialBalance');
    }

    public function table(Request $request)
    {  
        switch($request->action){
            case 'excelTBnett':
                return $this->excelTBnett($request);
            case 'pdfTBnett':
                return $this->pdfTBnett($request);
            case 'check_jnl_amt':
                return $this->check_jnl_amt($request);
            default:
                return 'error happen..';
        }
    }

    public function excelTBnett(Request $request){
        if(intval($request->monthfrom) > intval($request->monthto)){
            dd('month from need to be less than month to');
        }

        return Excel::download(new trialBalanceExport($request->monthfrom,$request->monthto,$request->yearfrom,$request->yearto,$request->acctfrom,$request->acctto), 'trialBalance.xlsx');
    }

    public function pdfTBnett(Request $request){
        
    }

    public function check_jnl_amt(Request $request){
        
        $gltran = DB::table('finance.gltran')
                        ->where('compcode','06')
                        ->where('year','2025')
                        ->where('period','6')
                        ->where('source','gl')
                        ->where('trantype','jnl')
                        ->get();

        $gltran_auditno = $gltran->unique('auditno');

        foreach ($gltran_auditno as $gl_unq) {
            $pdramt = 0;
            $pcramt = 0;
            foreach ($gltran as $gl) {
                if($gl->auditno == $gl_unq->auditno){
                    if($gl->drcostcode == ''){
                        $pcramt = $pcramt + $gl->amount;
                    }else if($gl->crcostcode == ''){
                        $pdramt = $pdramt + $gl->amount;
                    }
                }
            }
            $gl_unq->pdramt = $pdramt;
            $gl_unq->pcramt = $pcramt;
            $gl_unq->amtdif = $pdramt - $pcramt;
        }

        return view('finance.GL.trialBalance.check_jnl_amt',compact('gltran_auditno'));
    }
}