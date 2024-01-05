<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\DailyBillCollectionExport;
use Maatwebsite\Excel\Facades\Excel;

class DailyBillCollection_ReportController extends defaultController
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
        return view('finance.AR.DailyBillCollection_Report.DailyBillCollection_Report',[
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
            default:
                return 'error happen..';
        }
    }
    
    public function showExcel(Request $request){
        return Excel::download(new DailyBillCollectionExport($request->datefr,$request->dateto), 'DailyBillCollectionExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                    ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dm.debtorcode as dm_debtorcode', 'dm.name as dm_name')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source', '=', 'PB')
                    ->where('dh.trantype', '=', 'IN')
                    ->whereBetween('dh.posteddate', [$datefr, $dateto])
                    ->orderBy('dh.posteddate','ASC')
                    ->get();
        
        $array_report = [];
        foreach ($dbacthdr as $key => $value){
            $value->card_amount = 0;
            $value->cheque_amount = 0;
            $value->cash_amount = 0;
            $value->tt_amount = 0;
            $value->cn_amount = 0;
            array_push($array_report, $value);
            
            $dbacthdr_ex = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                            ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dm.debtorcode as dm_debtorcode', 'dm.name as dm_name')
                            ->leftJoin('debtor.debtormast as dm', function($join){
                                $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                            ->where('dm.compcode', '=', session('compcode'));
                            })
                            ->where('dh.payercode', '=', $value->payercode)
                            ->where('dh.posteddate', '=', $value->posteddate)
                            ->where('dh.compcode','=',session('compcode'))
                            ->where('dh.source', '=', 'PB')
                            ->whereIn('dh.trantype',['RC','RD','CN']);
            
            if($dbacthdr_ex->exists()){
                foreach ($dbacthdr_ex->get() as $dbacthdr_exkey => $dbacthdr_exvalue) {
                    $dbacthdr_exvalue->card_amount = 0;
                    $dbacthdr_exvalue->cheque_amount = 0;
                    $dbacthdr_exvalue->cash_amount = 0;
                    $dbacthdr_exvalue->tt_amount = 0;
                    $dbacthdr_exvalue->cn_amount = 0;
                    switch ($dbacthdr_exvalue->trantype) {
                        case 'CN':
                            $dbacthdr_exvalue->cn_amount = $dbacthdr_exvalue->amount;
                            array_push($array_report, $dbacthdr_exvalue);
                            break;
                        case 'RC':
                            switch ($dbacthdr_exvalue->paytype) {
                                case '#F_TAB-CARD':
                                    $dbacthdr_exvalue->card_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CHEQUE':
                                    $dbacthdr_exvalue->cheque_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CASH':
                                    $dbacthdr_exvalue->cash_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-DEBIT':
                                    $dbacthdr_exvalue->tt_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                            }
                            array_push($array_report, $dbacthdr_exvalue);
                            break;
                        case 'RD':
                            switch ($dbacthdr_exvalue->paytype) {
                                case '#F_TAB-CARD':
                                    $dbacthdr_exvalue->card_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CHEQUE':
                                    $dbacthdr_exvalue->cheque_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CASH':
                                    $dbacthdr_exvalue->cash_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-DEBIT':
                                    $dbacthdr_exvalue->tt_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                            }
                            array_push($array_report, $dbacthdr_exvalue);
                            break;
                        default:
                            // code...
                            break;
                    }
                }
            }
        }
        
        $title = "DAILY BILL AND COLLECTION";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }
        
        return view('finance.AR.DailyBillCollection_Report.DailyBillCollection_Report_pdfmake',compact('array_report','title','company'));
        
    }
    
}