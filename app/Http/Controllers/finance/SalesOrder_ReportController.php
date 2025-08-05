<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;
use App\Exports\SalesOrderExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesOrder_ReportController extends defaultController
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
        return view('finance.SalesOrder_Report.SalesOrder_Report',[
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
        return Excel::download(new SalesOrderExport($request->datefr,$request->dateto,$request->deptcode,$request->scope), 'SalesOrderReport.xlsx');
    }

    public function showpdf(Request $request)
    {
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d H:i:s');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d H:i:s');
        $deptcode = $request->deptcode;
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                    ->select('dh.invno', 'dh.posteddate', 'dh.deptcode', 'dh.amount', 'dm.debtorcode as dm_debtorcode', 'dm.name as debtorname')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source','=','PB')
                    ->where('dh.recstatus','=', 'POSTED')
                    ->where('dh.trantype', '=', 'IN');
                    if(!empty($deptcode)){
                        $dbacthdr = $dbacthdr
                                    ->where('dh.deptcode', '=', $deptcode);
                    }
                    $dbacthdr = $dbacthdr->whereBetween('dh.posteddate', [$datefr, $dateto])
                    ->get();
        
        $totalAmount = $dbacthdr->sum('amount');

        $title = "SALES";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('finance.SalesOrder_Report.SalesOrder_Report_pdfmake',compact('dbacthdr','totalAmount', 'company', 'title'));
    }
}