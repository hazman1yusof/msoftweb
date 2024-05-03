<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\SupplierExport;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('material.supplier.supplier');
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

    public function showpdf(Request $request){
        $supp_code = DB::table('material.supplier')
            ->where('compcode','=',session('compcode'))
            ->where('recstatus', '=', 'ACTIVE')
            ->orderBy('suppcode', 'ASC')
            ->get();

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('material.supplier.supplier_pdfmake',compact('supp_code','company'));
        
    }

    public function showExcel(Request $request){
        return Excel::download(new SupplierExport, 'SupplierReport.xlsx');
    }
}