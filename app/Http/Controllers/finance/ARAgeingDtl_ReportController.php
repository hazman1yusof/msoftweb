<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\ARAgeingDtlExport;
use Maatwebsite\Excel\Facades\Excel;

class ARAgeingDtl_ReportController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    var $auditno;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report',[
            'company_name' => $comp->name
        ]);
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
            case 'depreciation':
                return $this->depreciation($request);
            default:
                return 'error happen..';
        }
    }
    
    public function showExcel(Request $request){
        return Excel::download(new ARAgeingDtlExport($request->debtorcode_from,$request->debtorcode_to,$request->datefr,$request->dateto), 'ARAgeingDtlExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $debtorcode_from = $request->debtorcode_from;
        if(empty($request->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $request->debtorcode_to;
        
        $years = range(Carbon::parse($request->datefr)->format('Y'), Carbon::parse($request->dateto)->format('Y'));
        
        $debtormast = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.debtorcode', 'dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode', '=', session('compcode'))
                    ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                    ->whereBetween('dh.debtorcode',[$debtorcode_from,$debtorcode_to.'%'])
                    ->whereBetween('dh.posteddate', [$datefr, $dateto])
                    ->orderBy('dm.debtorcode', 'ASC')
                    ->distinct('dm.debtorcode');
        
        $debtormast = $debtormast->get(['dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4']);
        
        $array_report = [];
        $years_bal_all = [];
        foreach ($debtormast as $key => $value){
            $years_bal = [];
            $calc_openbal = DB::table('debtor.dbacthdr as dh')
                            ->where('dh.compcode', '=', session('compcode'))
                            ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                            ->where('dh.debtorcode', '=', $value->debtorcode)
                            ->whereYear('dh.posteddate', '<', Carbon::parse($request->datefr)->format('Y'));
            
            $openbalb4 = $this->calc_bal($calc_openbal);
            
            foreach ($years as $year) {
                $dbacthdr = DB::table('debtor.dbacthdr as dh')
                            ->where('dh.compcode', '=', session('compcode'))
                            ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                            ->where('dh.debtorcode', '=', $value->debtorcode)
                            ->whereYear('dh.posteddate', $year);
                
                $balance = $this->calc_bal($dbacthdr);
                $total_bal = $balance + $openbalb4;
                array_push($years_bal,$total_bal);
                $openbalb4 = $total_bal;
            }
            array_push($array_report, $value);
            array_push($years_bal_all,$years_bal);
        }
        
        // dd($array_report);
        
        $title = "AR AGEING DETAILS";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }
        
        return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report_pdfmake', compact('years','debtormast','array_report','years_bal_all','title','company'));
        
    }
    
    public function calc_bal($obj){
        
        $balance = 0;
        
        foreach ($obj->get() as $key => $value){
            switch ($value->trantype) {
                case 'IN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'BC':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'RF':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RC':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RD':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RT':
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }
        
        return $balance;
        
    }
    
}