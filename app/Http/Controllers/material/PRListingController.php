<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\PRListingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class PRListingController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.PRListing.PRListing',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new PRListingExport($request->datefr,$request->dateto,$request->Status), 'PRListingExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $Status = $request->Status;

        if ($Status == 'ALL'){

            $PRListing = DB::table('material.purreqhd as h')
                        ->select('h.recno')
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->whereBetween('h.purreqdt', [$datefr, $dateto])
                        ->orderBy('h.recno', 'ASC')
                        ->distinct('h.recno');

            $PRListing = $PRListing->get('h.recno');
            
            // dd($PRListing);

            $purreqhd = DB::table('material.purreqhd as h')
                        ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.reqdept', 'h.purreqno', 'h.purreqdt', 'h.suppcode', 's.name as supp_name', 'h.recstatus', 'h.requestby', 'h.requestdate', 'h.supportby', 'h.supportdate', 'h.verifiedby', 'h.verifieddate', 'h.approvedby', 'h.approveddate', 'h.cancelby', 'h.canceldate', 'pr.description as pr_desc', 'rq.description as req_desc')
                        ->leftjoin('material.supplier as s', function($join){
                            $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                        ->where('s.compcode', '=', session('compcode'))
                                        ->where('s.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('sysdb.department as pr', function($join){
                            $join = $join->on('pr.deptcode', '=', 'h.prdept')
                                        ->where('pr.compcode', '=', session('compcode'))
                                        ->where('pr.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('sysdb.department as rq', function($join){
                            $join = $join->on('rq.deptcode', '=', 'h.reqdept')
                                        ->where('rq.compcode', '=', session('compcode'))
                                        ->where('rq.recstatus', '=', 'ACTIVE');
                        })
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->whereBetween('h.purreqdt', [$datefr, $dateto])
                        ->orderBy('h.purreqdt', 'ASC')
                        ->get();
            // dd($purreqhd);

            $purreqdt = DB::table('material.purreqdt as d')
                        ->select('d.compcode', 'd.recno', 'd.lineno_', 'd.pricecode', 'd.itemcode', 'p.description', 'd.uomcode', 'd.pouom', 'd.qtyrequest', 'd.qtybalance', 'd.unitprice', 'd.taxcode', 'd.perdisc', 'd.amtdisc', 'd.amtslstax as tot_gst','d.netunitprice', 'd.totamount','d.amount', 'd.recstatus', 'd.unit', 't.rate','pc.description as pc_desc', 't.description as tax_desc')
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
                        ->get();
                // dd($purreqdt);
        } else {
            $PRListing = DB::table('material.purreqhd as h')
                        ->select('h.recno')
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '=', $Status)
                        ->whereBetween('h.purreqdt', [$datefr, $dateto])
                        ->orderBy('h.recno', 'ASC')
                        ->distinct('h.recno');

            $PRListing = $PRListing->get('h.recno');
            
            // dd($PRListing);
            $purreqhd = DB::table('material.purreqhd as h')
                        ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.reqdept', 'h.purreqno', 'h.purreqdt', 'h.suppcode', 's.name as supp_name', 'h.recstatus', 'h.requestby', 'h.requestdate', 'h.supportby', 'h.supportdate', 'h.verifiedby', 'h.verifieddate', 'h.approvedby', 'h.approveddate', 'h.cancelby', 'h.canceldate', 'pr.description as pr_desc', 'rq.description as req_desc')
                        ->leftjoin('material.supplier as s', function($join){
                            $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                        ->where('s.compcode', '=', session('compcode'))
                                        ->where('s.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('sysdb.department as pr', function($join){
                            $join = $join->on('pr.deptcode', '=', 'h.prdept')
                                        ->where('pr.compcode', '=', session('compcode'))
                                        ->where('pr.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('sysdb.department as rq', function($join){
                            $join = $join->on('rq.deptcode', '=', 'h.reqdept')
                                        ->where('rq.compcode', '=', session('compcode'))
                                        ->where('rq.recstatus', '=', 'ACTIVE');
                        })
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->whereBetween('h.purreqdt', [$datefr, $dateto])
                        ->orderBy('h.purreqdt', 'ASC')
                        ->get();
            // dd($purreqhd);

            $purreqdt = DB::table('material.purreqdt as d')
                        ->select('d.compcode', 'd.recno', 'd.lineno_', 'd.pricecode', 'd.itemcode', 'p.description', 'd.uomcode', 'd.pouom', 'd.qtyrequest', 'd.qtybalance', 'd.unitprice', 'd.taxcode', 'd.perdisc', 'd.amtdisc', 'd.amtslstax as tot_gst','d.netunitprice', 'd.totamount','d.amount', 'd.recstatus', 'd.unit', 't.rate','pc.description as pc_desc', 't.description as tax_desc')
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
                        ->get();
                // dd($purreqdt);
        }

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.PRListing.PRListing_pdfmake',compact('PRListing', 'purreqhd', 'purreqdt'));
        
    }
   
}

