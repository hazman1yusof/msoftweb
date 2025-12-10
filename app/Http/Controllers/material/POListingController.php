<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\POListingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class POListingController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        // $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        $deptcode = DB::table('sysdb.department')
                ->where('compcode','=',session('compcode'))
                ->where('purdept','=','1')
                ->where('recstatus','=','ACTIVE')
                ->get();
        return view('material.POListing.POListing',compact('deptcode'));
    }

    public function showExcel(Request $request){
        return Excel::download(new POListingExport($request->datefr,$request->dateto,$request->Status,$request->deptcode), 'POListingExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $Status = $request->Status;

        if ($Status == 'ALL'){

            $POListing = DB::table('material.purordhd as h')
                        ->select('h.recno')
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->whereBetween('h.purdate', [$datefr, $dateto])
                        ->orderBy('h.recno', 'ASC')
                        ->distinct('h.recno');

            $POListing = $POListing->get('h.recno');
            
            // dd($POListing);

            $purordhd = DB::table('material.purordhd as h')
                        ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.purordno', 'h.purdate', 'h.suppcode', 's.name as supp_name', 'h.recstatus', 'h.requestby', 'h.requestdate', 'h.supportby', 'h.supportdate', 'h.verifiedby', 'h.verifieddate', 'h.approvedby', 'h.approveddate', 'h.cancelby', 'h.canceldate', 'dp.description as dept_desc', 'dd.description as deldept_desc')
                        ->join('material.supplier as s', function($join){
                            $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                        ->where('s.compcode', '=', session('compcode'))
                                        ->where('s.recstatus', '=', 'ACTIVE');
                        })
                        ->join('sysdb.department as dp', function($join){
                            $join = $join->on('dp.deptcode', '=', 'h.prdept')
                                        ->where('dp.compcode', '=', session('compcode'))
                                        ->where('dp.recstatus', '=', 'ACTIVE');
                        })
                        ->join('material.deldept as dd', function($join){
                            $join = $join->on('dd.deptcode', '=', 'h.deldept')
                                        ->where('dd.compcode', '=', session('compcode'))
                                        ->where('dd.recstatus', '=', 'ACTIVE');
                        })
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->whereBetween('h.purdate', [$datefr, $dateto])
                        ->orderBy('h.purdate', 'ASC')
                        ->get();
            //dd($purordhd);

            $purorddt = DB::table('material.purorddt as d')
                        ->select('d.compcode', 'd.recno', 'd.lineno_', 'd.suppcode', 'd.purdate','d.pricecode', 'd.itemcode', 'p.description','d.uomcode','d.pouom','d.qtyorder','d.qtyoutstand','d.qtyrequest','d.qtydelivered', 'd.perslstax', 'd.unitprice', 'd.taxcode', 'd.perdisc', 'd.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount','d.amount', 'd.unit', 'd.recstatus', 'pc.description as pc_desc', 't.description as tax_desc')
                        ->join('material.product as p', function($join){
                            $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                        ->on('p.uomcode', '=', 'd.uomcode')
                                        ->where('p.compcode', '=', session('compcode'))
                                        ->where('p.unit', '=', session('unit'))
                                        ->where('p.recstatus', '=', 'ACTIVE');
                        })
                        ->join('material.pricesource as pc', function($join){
                            $join = $join->on('pc.pricecode', '=', 'd.pricecode')
                                        ->where('p.compcode', '=', session('compcode'))
                                        ->where('p.recstatus', '=', 'ACTIVE');
                        })
                        ->join('hisdb.taxmast as t', function($join){
                            $join = $join->on('t.taxcode', '=', 'd.taxcode')
                                        ->where('t.compcode', '=', session('compcode'))
                                        ->where('t.recstatus', '=', 'ACTIVE');
                        })
                        ->where('d.compcode',session('compcode'))
                        ->where('d.unit',session('unit'))
                        ->where('d.recstatus','!=', 'DELETE')
                        ->whereBetween('d.purdate', [$datefr, $dateto])
                        ->orderBy('d.purdate', 'ASC')
                        ->get();
                // dd($purorddt);
        } else {
            $POListing = DB::table('material.purordhd as h')
                        ->select('h.recno')
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '=', $Status)
                        ->whereBetween('h.purdate', [$datefr, $dateto])
                        ->orderBy('h.recno', 'ASC')
                        ->distinct('h.recno');

            $POListing = $POListing->get('h.recno');
            
            // dd($POListing);
            $purordhd = DB::table('material.purordhd as h')
                        ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.purordno', 'h.purdate', 'h.suppcode', 's.name as supp_name', 'h.recstatus', 'h.requestby', 'h.requestdate', 'h.supportby', 'h.supportdate', 'h.verifiedby', 'h.verifieddate', 'h.approvedby', 'h.approveddate', 'h.cancelby', 'h.canceldate', 'dp.description as dept_desc', 'dd.description as deldept_desc')
                        ->join('material.supplier as s', function($join){
                            $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                        ->where('s.compcode', '=', session('compcode'))
                                        ->where('s.recstatus', '=', 'ACTIVE');
                        })
                        ->join('sysdb.department as dp', function($join){
                            $join = $join->on('dp.deptcode', '=', 'h.prdept')
                                        ->where('dp.compcode', '=', session('compcode'))
                                        ->where('dp.recstatus', '=', 'ACTIVE');
                        })
                        ->join('material.deldept as dd', function($join){
                            $join = $join->on('dd.deptcode', '=', 'h.deldept')
                                        ->where('dd.compcode', '=', session('compcode'))
                                        ->where('dd.recstatus', '=', 'ACTIVE');
                        })
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '=', $Status)
                        ->whereBetween('h.purdate', [$datefr, $dateto])
                        ->orderBy('h.purdate', 'ASC')
                        ->get();
            // dd($purordhd);

            $purorddt = DB::table('material.purorddt as d')
                        ->select('d.compcode', 'd.recno', 'd.lineno_', 'd.suppcode', 'd.purdate','d.pricecode', 'd.itemcode', 'p.description','d.uomcode','d.pouom','d.qtyorder','d.qtyoutstand','d.qtyrequest','d.qtydelivered', 'd.perslstax', 'd.unitprice', 'd.taxcode', 'd.perdisc', 'd.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount','d.amount', 'd.unit', 'd.recstatus', 'pc.description as pc_desc', 't.description as tax_desc')
                        ->join('material.product as p', function($join){
                            $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                        ->on('p.uomcode', '=', 'd.uomcode')
                                        ->where('p.compcode', '=', session('compcode'))
                                        ->where('p.unit', '=', session('unit'))
                                        ->where('p.recstatus', '=', 'ACTIVE');
                        })
                        ->join('material.pricesource as pc', function($join){
                            $join = $join->on('pc.pricecode', '=', 'd.pricecode')
                                        ->where('p.compcode', '=', session('compcode'))
                                        ->where('p.recstatus', '=', 'ACTIVE');
                        })
                        ->join('hisdb.taxmast as t', function($join){
                            $join = $join->on('t.taxcode', '=', 'd.taxcode')
                                        ->where('t.compcode', '=', session('compcode'))
                                        ->where('t.recstatus', '=', 'ACTIVE');
                        })
                        ->where('d.compcode',session('compcode'))
                        ->where('d.unit',session('unit'))
                        ->whereBetween('d.purdate', [$datefr, $dateto])
                        ->orderBy('d.purdate', 'ASC')
                        ->get();
                // dd($purorddt);
        }

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.POListing.POListing_pdfmake',compact('POListing', 'purordhd', 'purorddt'));
        
    }
   
}

