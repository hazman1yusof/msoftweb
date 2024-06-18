<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\GRTListingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class GRTListingController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.GRTListing.GRTListing',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new GRTListingExport($request->datefr,$request->dateto,$request->Status), 'GRTListingExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $Status = $request->Status;

        if ($Status == 'ALL'){
        
            $GRTListing = DB::table('material.delordhd as h')
                        ->select('h.recno')
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->where('h.trantype', '=', 'GRT')
                        ->whereBetween('h.trandate', [$datefr, $dateto])
                        ->orderBy('h.recno', 'ASC')
                        ->distinct('h.recno');

            $GRTListing = $GRTListing->get('h.recno');
            
            // dd($GRTListing);

            $delordhd = DB::table('material.delordhd as h')
                        ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.delordno', 'h.trandate', 'h.suppcode', 's.name as supp_name', 'h.srcdocno', 'h.invoiceno', 'h.totamount', 'h.docno', 'h.recstatus', 'dp.description as dept_desc', 'prd.description as dd_desc', 'do.srcdocno as do_srcdocno', 'do.trantype', 'do.prdept')
                        ->leftjoin('material.delordhd as do', function($join){
                            $join = $join->on('do.prdept', '=', 'h.prdept')
                                        ->on('do.docno', '=','h.srcdocno')
                                        ->where('do.trantype', '=', 'GRN')
                                        ->where('do.compcode',session('compcode'));
                        })
                        ->leftjoin('material.supplier as s', function($join){
                            $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                        ->where('s.compcode', '=', session('compcode'))
                                        ->where('s.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('sysdb.department as dp', function($join){
                            $join = $join->on('dp.deptcode', '=', 'h.prdept')
                                        ->where('dp.compcode', '=', session('compcode'))
                                        ->where('dp.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('sysdb.department as prd', function($join){
                            $join = $join->on('prd.deptcode', '=', 'h.deldept')
                                        ->where('prd.compcode', '=', session('compcode'))
                                        ->where('prd.recstatus', '=', 'ACTIVE');
                        })
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->where('h.trantype', '=', 'GRT')
                        ->get();
                    // dd($delordhd);

            $delorddt = DB::table('material.delorddt as d')
                    ->select('d.compcode','d.recno','d.lineno_','d.pricecode','d.itemcode','p.description','d.uomcode','d.pouom', 'd.suppcode','d.trandate','d.deldept','d.deliverydate','d.qtyorder','d.qtydelivered', 'd.qtyreturned','d.unitprice','d.taxcode', 'd.perdisc','d.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount', 'd.amount', 'd.expdate','d.batchno', 'd.unit','d.idno', 'd.recstatus', 'pc.description as pc_desc', 't.description as tax_desc')
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
                    ->where('d.recstatus', '!=', 'DELETE')
                    ->orderBy('d.lineno_', 'ASC')
                    ->get();
                    // dd($delorddt);
        } else {
                
        $GRTListing = DB::table('material.delordhd as h')
                    ->select('h.recno')
                    ->where('h.compcode',session('compcode'))
                    ->where('h.unit',session('unit'))
                    ->where('h.recstatus', '=', $Status)
                    ->where('h.trantype', '=', 'GRT')
                    ->whereBetween('h.trandate', [$datefr, $dateto])
                    ->orderBy('h.recno', 'ASC')
                    ->distinct('h.recno');

        $GRTListing = $GRTListing->get('h.recno');
    
        // dd($GRTListing);

        $delordhd = DB::table('material.delordhd as h')
                    ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.delordno', 'h.trandate', 'h.suppcode', 's.name as supp_name', 'h.srcdocno', 'h.invoiceno', 'h.totamount', 'h.docno', 'h.recstatus', 'dp.description as dept_desc', 'prd.description as dd_desc','do.srcdocno as do_srcdocno', 'do.trantype', 'do.prdept')
                    ->leftjoin('material.delordhd as do', function($join){
                        $join = $join->on('do.prdept', '=', 'h.prdept')
                                    ->on('do.docno','=','h.srcdocno')
                                    ->where('do.trantype', '=', 'GRN')
                                    ->where('do.compcode',session('compcode'));
                    })
                    ->leftjoin('material.supplier as s', function($join){
                        $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                    ->where('s.compcode', '=', session('compcode'))
                                    ->where('s.recstatus', '=', 'ACTIVE');
                    })
                    ->leftjoin('sysdb.department as dp', function($join){
                        $join = $join->on('dp.deptcode', '=', 'h.prdept')
                                    ->where('dp.compcode', '=', session('compcode'))
                                    ->where('dp.recstatus', '=', 'ACTIVE');
                    })
                    ->leftjoin('sysdb.department as prd', function($join){
                        $join = $join->on('prd.deptcode', '=', 'h.deldept')
                                    ->where('prd.compcode', '=', session('compcode'))
                                    ->where('prd.recstatus', '=', 'ACTIVE');
                    })
                    ->where('h.compcode',session('compcode'))
                    ->where('h.unit',session('unit'))
                    ->where('h.recstatus','=', $Status)
                    ->where('h.trantype', '=', 'GRT')
                    ->get();
                // dd($delordhd);

        $delorddt = DB::table('material.delorddt as d')
                ->select('d.compcode','d.recno','d.lineno_','d.pricecode','d.itemcode','p.description','d.uomcode','d.pouom', 'd.suppcode','d.trandate','d.deldept','d.deliverydate','d.qtyorder','d.qtydelivered', 'd.qtyreturned','d.unitprice','d.taxcode', 'd.perdisc','d.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount', 'd.amount', 'd.expdate','d.batchno', 'd.unit','d.idno', 'd.recstatus', 'pc.description as pc_desc', 't.description as tax_desc')
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
                ->where('d.recstatus', '!=', 'DELETE')
                // ->whereBetween('d.trandate', [$datefr, $dateto])
                ->orderBy('d.lineno_', 'ASC')
                ->get();
                // dd($delorddt);
        }
       
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.GRTListing.GRTListing_pdfmake',compact('GRTListing', 'delordhd', 'delorddt'));
        
    }
   
}

