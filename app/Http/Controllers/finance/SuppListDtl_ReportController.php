<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\SuppListDtlExport;
use Maatwebsite\Excel\Facades\Excel;

class SuppListDtl_ReportController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.SuppListDtl_Report.SuppListDtl_Report');
    }

    public function showExcel(Request $request){
        return Excel::download(new SuppListDtlExport($request->suppgroup), 'SuppListDtl.xlsx');
    }
    
    public function showpdf(Request $request){

        $suppgroup = $request->suppgroup;

        $supp_code = DB::table('material.supplier')
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus', '=', 'ACTIVE')
                    ->where('suppgroup', '=', $suppgroup)
                    ->orderBy('suppcode', 'ASC')
                    ->get();
        //dd($supp_code);
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->suppgroup = $request->suppgroup;
        $header->compname = $company->name;

        return view('finance.AP.SuppListDtl_Report.SuppListDtl_Report_pdfmake',compact('header', 'supp_code'));
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