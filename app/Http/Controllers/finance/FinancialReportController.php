<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\financialReportExport;
use App\Exports\financialReportExport_units;
use App\Exports\financialReportExport_bs;
use App\Exports\financialReportExport_bs_main;
use App\Exports\financialReportExport_bs_check_1;
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
            case 'check':
                return $this->check($request);
            case 'checkBS':
                return $this->checkBS($request);
            default:
                return 'error happen..';
        }
    }

    public function genexcel(Request $request){
        // if(intval($request->monthfrom) > intval($request->monthto)){
        //     dd('month from need to be less than month to');
        // }
        if($request->reporttype == '1'){
            if($request->Class == 'All'){
                return Excel::download(new financialReportExport($request->monthfrom,$request->monthto,$request->yearfrom,$request->yearto,$request->reportname), 'FINANCIAL REPORT Profit and Loss.xlsx');
            }else if($request->Class == 'Department'){

            }else if($request->Class == 'Units'){
                return Excel::download(new financialReportExport_units($request->monthfrom,$request->monthto,$request->yearfrom,$request->yearto,$request->reportname), 'FINANCIAL REPORT Profit and Loss (Units).xlsx');
            }else if($request->Class == 'Variance'){
                
            }
        }else if($request->reporttype == '2'){
            return Excel::download(new financialReportExport_bs_main($request->monthfrom,$request->monthto,$request->yearfrom,$request->yearto,$request->reportname), 'FINANCIAL REPORT Balance Sheet.xlsx');
        }else{
            abort(403, 'Report Type Not Exist');
        }
    }

    public function check(Request $request){

        return view('finance.GL.financialReport.check');
    }

    public function checkBS(Request $request){
        return Excel::download(new financialReportExport_bs_check_1($request->month,$request->year), 'FINANCIAL REPORT Balance Sheet Checking.xlsx');
    }

    public function genpdf(Request $request){
        
    }
}