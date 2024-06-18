<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\inventoryTransaction_ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class inventoryTransaction_ReportController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.inventoryTransaction_Report.inventoryTransaction_Report',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new inventoryTransaction_ReportExport($request->datefr,$request->dateto,$request->Status), 'inventoryTransaction_ReportExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $Status = $request->Status;

        if ($Status == 'ALL'){
            $ivtxn = DB::table('material.ivtmphd as h')
                    ->select('h.idno', 'h.compcode', 'h.recno', 'h.trandate', 'h.docno', 'h.txndept', 'h.sndrcv', 'h.sndrcvtype', 'h.recstatus', 'd.recno', 'd.itemcode', 'd.uomcode', 'd.qtyonhand', 'd.uomcoderecv', 'd.qtyonhandrecv', 'd.txnqty', 'd.qtyrequest', 'd.netprice', 'd.expdate', 'd.batchno', 'd.amount', 'p.description')
                    ->join('material.ivtmpdt as d', function($join){
                        $join = $join->on('d.recno', '=', 'h.recno')
                                    ->where('d.compcode', '=', session('compcode'))
                                    ->where('d.unit', '=', session('unit'));
                    })
                    ->join('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                    ->on('p.uomcode', '=', 'd.uomcode')
                                    ->where('p.compcode', '=', session('compcode'))
                                    ->where('p.unit', '=', session('unit'))
                                    ->where('p.recstatus', '=', 'ACTIVE');
                    })
                    ->where('h.compcode',session('compcode'))
                    ->where('h.unit',session('unit'))
                    ->where('h.recstatus', '!=', 'DELETE')
                    ->whereBetween('h.trandate', [$datefr, $dateto])
                    ->orderBy('h.trandate', 'ASC')
                    ->get();
                    // dd($ivtxn);
        }
        else {
            $ivtxn = DB::table('material.ivtmphd as h')
                    ->select('h.idno', 'h.compcode', 'h.recno', 'h.trandate', 'h.docno', 'h.txndept', 'h.sndrcv', 'h.sndrcvtype', 'h.recstatus', 'd.recno', 'd.itemcode', 'd.uomcode', 'd.qtyonhand', 'd.uomcoderecv', 'd.qtyonhandrecv', 'd.txnqty', 'd.qtyrequest', 'd.netprice', 'd.expdate', 'd.batchno', 'd.amount', 'p.description')
                    ->join('material.ivtmpdt as d', function($join){
                        $join = $join->on('d.recno', '=', 'h.recno')
                                    ->where('d.compcode', '=', session('compcode'))
                                    ->where('d.unit', '=', session('unit'));
                    })
                    ->join('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                    ->on('p.uomcode', '=', 'd.uomcode')
                                    ->where('p.compcode', '=', session('compcode'))
                                    ->where('p.unit', '=', session('unit'))
                                    ->where('p.recstatus', '=', 'ACTIVE');
                    })
                    ->where('h.compcode',session('compcode'))
                    ->where('h.unit',session('unit'))
                    ->where('h.recstatus', '=', $Status)
                    ->whereBetween('h.trandate', [$datefr, $dateto])
                    ->orderBy('h.trandate', 'ASC')
                    ->get();
                    // dd($ivtxn);
        }
                    
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.inventoryTransaction_Report.inventoryTransaction_Report_pdfmake',compact('ivtxn'));
        
    }
   
}

