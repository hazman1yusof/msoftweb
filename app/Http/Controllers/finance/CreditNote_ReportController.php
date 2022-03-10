<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;
use App\Exports\CreditNoteExport;
use Maatwebsite\Excel\Facades\Excel;

class CreditNote_ReportController extends defaultController
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
        return view('finance.AP.creditNote_Report.creditNote_Report',[
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
        return Excel::download(new CreditNoteExport($request->datefr,$request->dateto), 'CreditNoteReport.xlsx');
    }
}