<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\ARSummaryExport;
use Maatwebsite\Excel\Facades\Excel;

class ARSummary_ReportController extends defaultController
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
        return view('finance.AR.ARSummary_Report.ARSummary_Report',[
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
        return Excel::download(new ARSummaryExport($request->debtorcode_from,$request->debtorcode_to,$request->datefr,$request->dateto), 'ARSummaryExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $debtorcode_from = $request->debtorcode_from;
        if(empty($request->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $request->debtorcode_to;
        
        // $debtor = DB::table('debtor.dbacthdr as dh')
        //         ->select(
        //             'dm.debtorcode',
        //             DB::raw("(DATE_FORMAT(created_at, '%Y')) as my_year"),
        //         )
        //         ->leftJoin('debtor.debtormast as dm', function($join){
        //             $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
        //                         ->where('dm.compcode', '=', session('compcode'));
        //         })
        //         ->where('dh.compcode', '=', session('compcode'))
        //         ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
        //         ->whereBetween('dh.debtorcode',[$debtorcode_from,$debtorcode_to.'%'])
        //         ->whereBetween('dh.posteddate', [$datefr, $dateto])
        //         ->groupBy('dm.debtorcode')
        //         ->orderBy('dm.debtorcode', 'ASC')
        //         ->get();
        
        // dd($debtor);
        
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
        foreach ($debtormast as $key => $value){
            $dbacthdr = DB::table('debtor.dbacthdr as dh')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'pm.Name', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate')
                        ->leftJoin('hisdb.pat_mast as pm', function($join){
                            $join = $join->on('pm.MRN', '=', 'dh.mrn')
                                        ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dh.compcode', '=', session('compcode'))
                        ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                        ->where('debtorcode',$value->debtorcode)
                        ->whereBetween('dh.posteddate', [$datefr, $dateto])
                        ->orderBy('dh.posteddate', 'ASC')
                        ->get();
            
            $calc_openbal = DB::table('debtor.dbacthdr as dh')
                            ->where('dh.compcode', '=', session('compcode'))
                            ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                            ->where('dh.debtorcode', '=', $value->debtorcode)
                            ->whereDate('dh.posteddate', '<', $datefr);
            
            $openbal = $this->calc_openbal($calc_openbal);
            $value->openbal = $openbal;
            
            $value->reference = '';
            $value->amount_dr = 0;
            $value->amount_cr = 0;
            $balance = $openbal;
            foreach ($dbacthdr as $key => $value){
                switch ($value->trantype) {
                    case 'IN':
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->reference = $value->remark;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'DN':
                        $value->reference = $value->remark;
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'BC':
                        // $value->reference
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RF':
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->reference = $value->remark;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'CN':
                        $value->reference = $value->remark;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RC':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RD':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RT':
                        // $value->reference
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    default:
                        // code...
                        break;
                }
            }
        }
        
        // dd($array_report);
        
        // $array_report = $array_report
        //                 ->map(function ($values) {
        //                     return $values->groupBy(function ($val) {
        //                         return Carbon::parse($val->dataPagamento)->format('M');
        //                     });
        //                 })
        //                 ->toArray();
        
        $title = "AR SUMMARY";
        
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
        
        return view('finance.AR.ARSummary_Report.ARSummary_Report_pdfmake', compact('debtormast','array_report','title','company'));
        
    }
    
    public function calc_openbal($obj){
        
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