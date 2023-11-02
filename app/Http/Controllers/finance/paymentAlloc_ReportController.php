<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\PaymentAllocExport;
use Maatwebsite\Excel\Facades\Excel;

class paymentAlloc_ReportController extends defaultController
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
        return view('finance.AR.paymentAlloc_Report.paymentAlloc_Report',[
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
        return Excel::download(new PaymentAllocExport($request->datefr,$request->dateto), 'PaymentAllocExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d H:i:s');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d H:i:s');
        
        $dballoc = DB::table('debtor.dballoc as da', 'debtor.dbacthdr as dh', 'debtor.dbacthdr as dc', 'debtor.debtormast as dm')
                    ->select('da.doctrantype', 'da.allocdate', 'da.recptno as da_recptno', 'da.refauditno', 'da.amount as allocamount', 'da.debtorcode', 'dh.entrydate as doc_entrydate', 'dh.recptno as dh_recptno', 'dh.reference', 'dh.amount as dh_amount', 'dc.entrydate as ref_entrydate', 'dm.debtorcode as dm_debtorcode', 'dm.name as debtorname')
                    ->leftJoin('debtor.dbacthdr as dh', function($join) use ($request){
                        $join = $join->on('dh.source', '=', 'da.docsource')
                                    ->on('dh.trantype', '=', 'da.doctrantype')
                                    ->on('dh.auditno', '=', 'da.docauditno');
                    })
                    ->leftJoin('debtor.dbacthdr as dc', function($join) use ($request){
                        $join = $join->on('dc.source', '=', 'da.refsource')
                                    ->on('dc.trantype', '=', 'da.reftrantype')
                                    ->on('dc.auditno', '=', 'da.refauditno');
                    })
                    ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'da.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('da.compcode','=',session('compcode'))
                    ->where('da.recstatus','=',"POSTED")
                    ->whereIn('da.doctrantype',['RD','RC'])
                    ->whereBetween('da.allocdate', [$datefr, $dateto])
                    // ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->get();
        // dd($dballoc);
        
        $title = "PAYMENT ALLOCATION LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('finance.AR.paymentAlloc_Report.paymentAlloc_Report_pdfmake',compact('dballoc', 'title', 'company'));
        
    }
    
}