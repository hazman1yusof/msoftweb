<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\DebtorListSummaryExport;
use App\Exports\DebtorListDtlExport;
use Maatwebsite\Excel\Facades\Excel;

class DebtorList_ReportController extends defaultController
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
        return view('finance.AR.DebtorList_Report.DebtorList_Report',[
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
    
    public function summaryExcel(Request $request){
        return Excel::download(new DebtorListSummaryExport($request->debtortype), 'DebtorListSummaryExport.xlsx');
    }
    
    public function dtlExcel(Request $request){
        return Excel::download(new DebtorListDtlExport($request->debtortype), 'DebtorListDtlExport.xlsx');
    }
    
    public function summarypdf(Request $request){
        
        $debtortype = $request->debtortype;
        
        $debtormast = DB::table('debtor.debtormast')
                    ->where('compcode', '=', session('compcode'))
                    ->where('debtortype', '=', $debtortype)
                    ->where('recstatus', '=', 'ACTIVE')
                    ->orderBy('debtortype', 'ASC')
                    ->get();
        
        // dd($debtormast);
        
        $company = DB::table('sysdb.company')
                ->where('compcode','=',session('compcode'))
                ->first();
        
        $header = new stdClass();
        $header->printby = session('username');
        $header->debtortype = $request->debtortype;
        $header->compname = $company->name;
        
        return view('finance.AR.DebtorList_Report.DebtorListSummary_Report_pdfmake',compact('debtormast', 'header'));
        
    }
    
    public function dtlpdf(Request $request){
        
        $debtortype = $request->debtortype;
        
        $debtormast = DB::table('debtor.debtormast')
                    ->where('compcode', '=', session('compcode'))
                    ->where('debtortype', '=', $debtortype)
                    ->where('recstatus', '=', 'ACTIVE')
                    ->orderBy('debtortype', 'ASC')
                    ->get();
        
        // dd($debtormast);
        
        $company = DB::table('sysdb.company')
                ->where('compcode','=',session('compcode'))
                ->first();
        
        $header = new stdClass();
        $header->printby = session('username');
        $header->debtortype = $request->debtortype;
        $header->compname = $company->name;
        
        return view('finance.AR.DebtorList_Report.DebtorListDtl_Report_pdfmake',compact('debtormast', 'header'));
        
    }
    
}