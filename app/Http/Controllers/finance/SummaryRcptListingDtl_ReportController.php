<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\SummaryRcptListingDtlExport;
use Maatwebsite\Excel\Facades\Excel;

class SummaryRcptListingDtl_ReportController extends defaultController
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
        return view('finance.AR.SummaryRcptListingDtl_Report.SummaryRcptListingDtl_Report',[
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
        return Excel::download(new SummaryRcptListingDtlExport($request->datefr,$request->dateto,$request->tillcode,$request->tillno), 'SummaryRcptListingDtlExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');

        $tilldetl = DB::table('debtor.tilldetl')
                    ->where('compcode',session('compcode'))
                    ->where('tillcode',$request->tillcode)
                    ->where('tillno',$request->tillno)
                    ->first();

        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt','debtor.tilldetl as dl')
                ->select(
                    'dh.tillcode',  'dh.entrydate', 'dl.cashier as cashier', 'dh.tillno',
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CASH' then dh.amount else 0 end) as cash"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CARD' then dh.amount else 0 end) as card"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CHEQUE' then dh.amount else 0 end) as cheque"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-DEBIT' then dh.amount else 0 end) as autodebit"),
                    )
                ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                    $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                ->where('dm.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                    $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                ->where('dt.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.tilldetl as dl', function($join) use ($request){
                                $join = $join->on('dl.tillno', '=', 'dh.tillno')
                                            ->where('dl.compcode', '=', session('compcode'));
                            })
                ->where('dh.compcode','=',session('compcode'))
                ->whereIn('dh.trantype',['RD','RC'])
                ->groupBy('dh.tillcode', 'dh.entrydate','dl.cashier', 'dh.tillno')
                ->whereBetween('dh.entrydate', [$datefr, $dateto])
                ->get();

        $totalAmount = $dbacthdr->sum('amount');

        $dbacthdr_rf = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt','debtor.tilldetl as dl')
                ->select(
                    'dh.tillcode',  'dh.entrydate', 'dl.cashier as cashier', 'dh.tillno',
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CASH' then dh.amount else 0 end) as cash"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CARD' then dh.amount else 0 end) as card"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CHEQUE' then dh.amount else 0 end) as cheque"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-DEBIT' then dh.amount else 0 end) as autodebit"),
                    )
                ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                    $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                ->where('dm.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                    $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                ->where('dt.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.tilldetl as dl', function($join) use ($request){
                                $join = $join->on('dl.tillno', '=', 'dh.tillno')
                                            ->where('dl.compcode', '=', session('compcode'));
                            })
                ->where('dh.compcode','=',session('compcode'))
                ->whereIn('dh.trantype',['RF'])
                ->groupBy('dh.tillcode', 'dh.entrydate','dl.cashier', 'dh.tillno')
                ->whereBetween('dh.entrydate', [$datefr, $dateto])
                ->get();
        
        $db_dbacthdr = DB::table('debtor.dbacthdr as db')
                    ->where('db.compcode',session('compcode'))
                    ->where('db.tillcode',$request->tillcode)
                    ->where('db.tillno',$request->tillno)
                    ->join('debtor.paymode as pm', function($join) use ($request){
                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                    ->where('pm.source','AR')
                                    ->where('pm.compcode',session('compcode'));
                    });
        
        if($db_dbacthdr->exists()){
    
            $sum_cash = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$request->tillcode)
                        // ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CASH')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');

            
            $sum_chq = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$request->tillcode)
                        // ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CHEQUE')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_card = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$request->tillcode)
                        // ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CARD')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_bank = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$request->tillcode)
                        // ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','BANK')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_all = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$request->tillcode)
                        // ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_cash_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$request->tillcode)
                            // ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CASH')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_chq_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$request->tillcode)
                            // ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CHEQUE')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_card_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$request->tillcode)
                            // ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CARD')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_bank_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$request->tillcode)
                            // ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','BANK')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_all_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$request->tillcode)
                            // ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $grandtotal_cash = $sum_cash - $sum_cash_ref;
            $grandtotal_card = $sum_card - $sum_card_ref;
            $grandtotal_chq = $sum_chq - $sum_chq_ref;
            $grandtotal_bank = $sum_bank - $sum_bank_ref;
            $grandtotal_all = $sum_all - $sum_all_ref;

        }
        
        $title = "SUMMARY RECEIPT DETAIL";

        $title2 = "REFUND LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $totamount_expld = explode(".", (float)$totalAmount);
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.AR.SummaryRcptListingDtl_Report.SummaryRcptListingDtl_Report_pdfmake',compact('tilldetl','dbacthdr','dbacthdr_rf','totalAmount','sum_cash','sum_chq','sum_card','sum_bank','sum_all','sum_cash_ref','sum_chq_ref','sum_card_ref','sum_bank_ref','sum_all_ref','grandtotal_cash','grandtotal_card', 'grandtotal_bank', 'grandtotal_chq', 'grandtotal_all','title', 'title2','company','totamt_eng'));
        
        
    }
}