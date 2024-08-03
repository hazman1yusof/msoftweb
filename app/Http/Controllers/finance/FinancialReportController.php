<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\financialReportExport;
use App\Exports\financialReportExport_bs;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class FinancialReportController extends defaultController
{   

    var $table;
   // var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.financialReport.financialReport');
    }

    public function table(Request $request)
    {  
        switch($request->action){
            case 'genexcel':
                return $this->genexcel($request);
            case 'genpdf':
                return $this->genpdf($request);
            default:
                return 'error happen..';
        }
    }

    public function genexcel(Request $request){
        // if(intval($request->monthfrom) > intval($request->monthto)){
        //     dd('month from need to be less than month to');
        // }

        if($request->reporttype == 'PROFIT & LOSS (DETAIL)'){
            return Excel::download(new financialReportExport($request->monthfrom,$request->monthto,$request->yearfrom,$request->yearto,$request->reportname), 'FINANCIAL REPORT Profit and Loss.xlsx');
        }else if($request->reporttype == 'BALANCE SHEET'){
            return Excel::download(new financialReportExport_bs($request->monthfrom,$request->monthto,$request->yearfrom,$request->yearto,$request->reportname), 'FINANCIAL REPORT Balance Sheet.xlsx');
        }else{

        }
    }

    public function genpdf(Request $request){
        
    }
}