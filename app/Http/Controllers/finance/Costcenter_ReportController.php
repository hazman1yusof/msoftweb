<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;
use App\Exports\CostCenterExport;
use Maatwebsite\Excel\Facades\Excel;

class Costcenter_ReportController extends defaultController
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
        return view('finance.GL.costcenter_Report.costcenter_Report',[
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
        return Excel::download(new CostCenterExport, 'CostCenterReport.xlsx');
    }
}