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
}