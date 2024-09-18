<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\inventoryRequest_ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class inventoryRequest_ReportController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.inventoryRequest_Report.inventoryRequest_Report',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new inventoryRequest_ReportExport($request->datefr,$request->dateto), 'inventoryRequest_ReportExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');

        $ivrequest = DB::table('material.ivreqhd as h')
                ->select('h.idno', 'h.compcode', 'h.recno as h_recno', 'h.reqdt', 'h.ivreqno', 'h.reqdept', 'h.reqtodept', 'h.recstatus', 'd.recno as d_recno','d.itemcode', 'd.uomcode', 'd.pouom', 's.qtyonhand', 'd.qtyrequest', 'd.qtybalance', 'd.qtytxn', 'd.netprice', 'd.ivreqno', 'd.reqdept', 'p.description')
                ->join('material.ivreqdt as d', function($join){
                    $join = $join->on('d.recno', '=', 'h.recno')
                                ->where('d.compcode', '=', session('compcode'))
                                // ->where('d.unit', '=', session('unit'))
                                ->where('d.recstatus', '!=', 'DELETE');
                })
                ->leftjoin('material.product as p', function($join){
                    $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                ->on('p.uomcode', '=', 'd.uomcode')
                                ->where('p.compcode', '=', session('compcode'))
                                ->where('p.unit', '=', session('unit'))
                                ->where('p.recstatus', '=', 'ACTIVE');
                })
                ->leftjoin('material.stockloc as s', function($join){
                    $join = $join->on('s.itemcode', '=', 'p.itemcode')
                                ->on('s.uomcode', '=', 'p.uomcode')
                                ->on('s.deptcode', '=', 'h.reqtodept')
                                ->where('s.compcode', '=', session('compcode'))
                                ->where('s.year', '=', Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                                ->where('s.unit', '=', session('unit'));
                })
                ->where('h.compcode',session('compcode'))
                ->whereIn('h.recstatus',['POSTED','PARTIAL'])
                // ->where('h.unit',session('unit'))
                ->whereBetween('h.reqdt', [$datefr, $dateto])
                ->orderBy('h.reqdt', 'ASC')
                ->get();
            // dd($ivrequest);

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.inventoryRequest_Report.inventoryRequest_Report_pdfmake',compact('ivrequest'));
        
    }
   
}

