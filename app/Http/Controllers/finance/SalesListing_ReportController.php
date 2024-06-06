<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\SalesListingExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesListing_ReportController extends defaultController
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
        return view('finance.AR.SalesListing_Report.SalesListing_Report',[
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
        return Excel::download(new SalesListingExport($request->datefr,$request->dateto), 'SalesListingExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt')
                    ->select('dh.idno', 'dh.compcode', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dh.unallocated', 'dh.datesend', 'dm.debtortype as dm_debtortype', 'dm.name as name', 'dt.description as dt_description')
                    ->leftJoin('debtor.debtormast as dm', function ($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('debtor.debtortype as dt', function ($join) use ($request){
                        $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                    ->where('dt.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source','=','PB')
                    ->where('dh.trantype','=','IN')
                    ->where('dh.recstatus','=','POSTED')
                    ->whereBetween('dh.posteddate',[$datefr, $dateto])
                    ->orderBy('dh.posteddate','ASC')
                    ->get();
        
        // dd($dbacthdr);
        
        $dbacthdr_1 = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','PB')
                    ->where('trantype','=','IN')
                    ->where('recstatus','=','POSTED')
                    ->whereBetween('posteddate',[$datefr, $dateto])
                    ->orderBy('posteddate','ASC')
                    ->get();
        
        // dd($dbacthdr_1);
        
        $array_report = [];
        foreach($dbacthdr_1 as $key => $value){
            $value->type = '';
            $value->name = '';
            // array_push($array_report, $value);
            
            $debtormast = DB::table('debtor.debtormast')
                        ->where('compcode','=',session('compcode'))
                        ->where('debtorcode','=',$value->debtorcode)
                        ->first();
            
            // dd($debtormast);
            
            if($debtormast->debtortype == 'PT' || $debtormast->debtortype == 'PR'){
                $value->type = 'SELF PAID';
                $value->name = $debtormast->name;
                array_push($array_report, $value);
            }else{
                $value->type = 'PANEL';
                $value->name = $debtormast->name;
                array_push($array_report, $value);
            }
        }
        // dd($array_report);
        
        $totalAmount = $dbacthdr->sum('amount');
        
        $totamount_expld = explode(".", (float)$totalAmount);
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.AR.SalesListing_Report.SalesListing_Report_pdfmake',compact('dbacthdr','array_report','totalAmount','totamt_eng'));
        
    }
    
}