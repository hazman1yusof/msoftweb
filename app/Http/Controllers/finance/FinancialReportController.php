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

        $month = intval($request->month);
        $year = $request->year;

        $glmasref = DB::table('finance.glmasref as gmr')
                        ->select('gmd.compcode','gmd.costcode','gmd.glaccount','gmd.year','gmd.openbalance','gmd.actamount1','gmd.actamount2','gmd.actamount3','gmd.actamount4','gmd.actamount5','gmd.actamount6','gmd.actamount7','gmd.actamount8','gmd.actamount9','gmd.actamount10','gmd.actamount11','gmd.actamount12','gmd.bdgamount1','gmd.bdgamount2','gmd.bdgamount3','gmd.bdgamount4','gmd.bdgamount5','gmd.bdgamount6','gmd.bdgamount7','gmd.bdgamount8','gmd.bdgamount9','gmd.bdgamount10','gmd.bdgamount11','gmd.bdgamount12','gmd.foramount1','gmd.foramount2','gmd.foramount3','gmd.foramount4','gmd.foramount5','gmd.foramount6','gmd.foramount7','gmd.foramount8','gmd.foramount9','gmd.foramount10','gmd.foramount11','gmd.foramount12','gmd.adduser','gmd.adddate','gmd.upduser','gmd.upddate','gmd.deluser','gmd.deldate','gmd.recstatus','gmd.idno')
                        ->leftJoin('finance.glmasdtl as gmd', function($join) use ($year){
                            $join = $join->on('gmd.glaccount','gmr.glaccno')
                                         ->where('gmd.year',$year)
                                         ->where('gmd.compcode','=',session('compcode'));
                        })
                        ->where('gmr.compcode',session('compcode'))
                        ->whereIn('gmr.acttype',['A','L'])
                        ->get();

        foreach ($glmasref as $obj) {
            $arrvalue = (array)$obj;
            $pbalance=0;

            for($x=1;$x<=$month;$x++){
                $pbalance = $pbalance + $arrvalue['actamount'.$x];
            }
            $obj->pbalance = $pbalance + $arrvalue['openbalance'];
        }

        $glrptfmt = DB::table('finance.glrptfmt as gr')
                    ->select('gr.rptname','gr.rowdef','gr.code','gr.description','gr.revsign','gc.lineno_','gc.acctfr','gc.acctto')
                    ->leftJoin('finance.glcondtl as gc', function($join){
                        $join = $join->on('gc.code', '=', 'gr.code')
                                ->where('gc.compcode','=',session('compcode'));
                    })
                    ->where('gr.compcode',session('compcode'))
                    ->where('gr.rptname','BSHEET')
                    ->where('gr.rowdef','D')
                    ->orderBy('gr.lineno_')
                    ->get();

        $excel_data = [];
        foreach ($glrptfmt as $obj) {
            $glmasdtl2 = DB::table('finance.glmasdtl as gldt')
                            ->select('gldt.compcode','gldt.costcode','gldt.glaccount','gldt.year','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12')
                            ->where('gldt.year',$year)
                            ->where('gldt.compcode',session('compcode'))
                            ->whereIn('gldt.glaccount',range($obj->acctfr, $obj->acctto))
                            ->get();

            foreach ($glmasdtl2 as $objgl) {
                $objgl->code = $obj->code;
                $arrgl = (array)$objgl;
                $pytd = $arrgl['openbalance'];

                for ($i=1; $i <= $month; $i++) { 
                    $pytd = $pytd + $arrgl['actamount'.$i];
                }

                $objgl->pytd = $pytd;

                array_push($excel_data,$objgl);
            }
        }
        $excel_data = collect($excel_data);
        // $excel_data = $excel_data->unique('glaccount');

        $table_data = [];
        foreach ($glmasref as $obj1) {
            $diff = 0;
            $obj1->pytd = 0;
            foreach ($excel_data as $obj2) {
                if($obj1->glaccount == $obj2->glaccount && $obj1->costcode == $obj2->costcode){
                    $obj1->pytd = $obj2->pytd;
                    break;
                }
            }

            $diff = round($obj1->pbalance,2) - round($obj1->pytd,2);
            $obj1->diff = $diff;

            if($diff != 0){
                array_push($table_data,$obj1);
            }
        }

        // dd($table_data);

        return view('finance.GL.financialReport.check',compact('table_data'));
    }

    public function checkBS(Request $request){
        return Excel::download(new financialReportExport_bs_check_1($request->month,$request->year), 'FINANCIAL REPORT Balance Sheet Checking.xlsx');
    }

    public function genpdf(Request $request){
        
    }
}