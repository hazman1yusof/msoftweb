<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\NewDebtorExport;
use Maatwebsite\Excel\Facades\Excel;

class NewDebtor_ReportController extends defaultController
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
        return view('finance.AR.NewDebtor_Report.NewDebtor_Report',[
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
        return Excel::download(new NewDebtorExport($request->yearfrom,$request->yearto), 'NewDebtorExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        // $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        // $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        
        // $date = Carbon::createFromDate(2017, 2, 23);
        // $startOfYear = $date->copy()->startOfYear();
        // $endOfYear   = $date->copy()->endOfYear();
        
        $yearfrom = $request->yearfrom;
        $yearto = $request->yearto;
        
        $startOfYear = $yearfrom."-01-01";
        $endOfYear = $yearto."-12-31";
        
        $debtormast = DB::table('debtor.debtormast')
                    ->where('compcode', '=', session('compcode'))
                    ->where('recstatus', '=', 'ACTIVE')
                    ->whereBetween('adddate', [$startOfYear, $endOfYear])
                    ->orderBy('adddate', 'ASC')
                    ->get();
        
        // dd($debtormast);
        
        return view('finance.AR.NewDebtor_Report.NewDebtor_Report_pdfmake',compact('debtormast'));
        
    }
    
}