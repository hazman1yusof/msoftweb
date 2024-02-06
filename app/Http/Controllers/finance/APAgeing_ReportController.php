<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APAgeingExport;
use Maatwebsite\Excel\Facades\Excel;

class APAgeing_ReportController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.APAgeing_Report.APAgeing_Report');
    }

    public function showExcel(Request $request){
        return Excel::download(new APAgeingExport($request->suppcode_from,$request->suppcode_to,$request->datefr,$request->dateto), 'APAgeing.xlsx');
    }
    
    public function showpdf(Request $request){

        $suppcode_from = $request->suppcode_from;
        if(empty($request->suppcode_from)){
            $suppcode_from = '%';
        }
        $suppcode_to = $request->suppcode_to;
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');

        $years = range(Carbon::parse($request->datefr)->format('Y'), Carbon::parse($request->dateto)->format('Y'));

        $supp_code = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode', 'su.Name AS supplier_name','su.Addr1 AS Addr1','su.Addr2 AS Addr2', 'su.Addr3 AS Addr3', 'su.Addr4 AS Addr4')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereBetween('ap.postdate', [$datefr, $dateto])
                    ->whereBetween('su.SuppCode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->distinct('ap.suppcode');
    
        $supp_code = $supp_code->get(['ap.suppcode', 'su.supplier_name', 'su.Addr1', 'su.Addr2', 'su.Addr3', 'su.Addr4']);

        $array_report = [];
        $years_bal_all = [];

        foreach ($supp_code as $key => $value){
            $years_bal = [];
            $calc_openbal = DB::table('finance.apacthdr as ap') 
                    ->where('ap.compcode',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->where('ap.suppcode', $value->suppcode)
                    ->whereYear('ap.postdate', '<', Carbon::parse($request->datefr)->format('Y'));

            $openbalb4 = $this->calc_bal($calc_openbal);

            foreach ($years as $year) {
                $apacthdr = DB::table('finance.apacthdr as ap')
                            ->where('ap.compcode', '=', session('compcode'))
                            ->where('ap.unit',session('unit'))
                            ->where('ap.recstatus', '=', 'POSTED')
                            ->where('ap.suppcode', $value->suppcode)
                            ->whereYear('ap.postdate', $year);
            
                $balance = $this->calc_bal($apacthdr);
                $total_bal = $balance + $openbalb4;
                array_push($years_bal,$total_bal);
                $openbalb4 = $total_bal;
            }
            array_push($array_report, $value);
            array_push($years_bal_all,$years_bal);

        }
        //dd($array_report);
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->datefr = Carbon::parse($request->datefr)->format('d-m-Y');
        $header->dateto = Carbon::parse($request->dateto)->format('d-m-Y');
        $header->suppcode_from = $request->suppcode_from;
        $header->suppcode_to = $request->suppcode_to;
        $header->compname = $company->name;

        return view('finance.AP.APAgeing_Report.APAgeing_Report_pdfmake',compact('years','years_bal_all','array_report','header', 'supp_code'));
        
    }

    public function calc_bal($obj){
        $balance = 0;
        foreach ($obj->get() as $key => $value){
            
            switch ($value->trantype) {
                 case 'IN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PV': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PD': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }

        return $balance;
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
}