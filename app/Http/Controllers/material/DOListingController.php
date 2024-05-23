<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\DOListingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class DOListingController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.DOListing.DOListing',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new DOListingExport($request->datefr,$request->dateto), 'DOListingExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');

        $DOListing = DB::table('material.delordhd as h')
                ->select('h.recno')
                ->where('h.compcode',session('compcode'))
                ->where('h.unit',session('unit'))
                ->where('h.recstatus', '!=', 'CANCELLED')
                ->whereBetween('h.trandate', [$datefr, $dateto])
                ->orderBy('h.recno', 'ASC')
                ->distinct('h.recno');

        $DOListing = $DOListing->get('h.recno');
        
        // dd($DOListing);

        $delordhd = DB::table('material.delordhd as h')
                ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.delordno', 'h.reqdept', 'h.trandate', 'h.suppcode', 's.name as supp_name', 'h.srcdocno', 'h.invoiceno', 'h.totamount', 'h.docno')
                ->join('material.supplier as s', function($join){
                    $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                ->where('s.compcode', '=', session('compcode'))
                                ->where('s.recstatus', '=', 'ACTIVE');
                })
                ->where('h.compcode',session('compcode'))
                ->where('h.unit',session('unit'))
                ->where('h.recstatus', '!=', 'CANCELLED')
                ->whereBetween('h.trandate', [$datefr, $dateto])
                ->orderBy('h.trandate', 'ASC')
                ->get();
            // dd($delordhd);

        $delorddt = DB::table('material.delorddt as d')
            ->select('d.compcode','d.recno','d.lineno_','d.pricecode','d.itemcode','p.description','d.uomcode','d.pouom', 'd.suppcode','d.trandate','d.deldept','d.deliverydate','d.qtyorder','d.qtydelivered', 'd.qtyoutstand','d.unitprice','d.taxcode', 'd.perdisc','d.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount', 'd.amount', 'd.expdate','d.batchno', 'd.unit','d.idno')
            ->join('material.product as p', function($join){
                $join = $join->on('p.itemcode', '=', 'd.itemcode')
                            ->on('p.uomcode', '=', 'd.uomcode')
                            ->where('p.compcode', '=', session('compcode'))
                            ->where('p.unit', '=', session('unit'))
                            ->where('p.recstatus', '=', 'ACTIVE');
            })
            ->where('d.compcode',session('compcode'))
            ->where('d.unit',session('unit'))
            ->where('d.recstatus', '!=', 'CANCELLED')
            ->whereBetween('d.trandate', [$datefr, $dateto])
            ->orderBy('d.trandate', 'ASC')
            ->get();
            // dd($delorddt);

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.DOListing.DOListing_pdfmake',compact('DOListing', 'delordhd', 'delorddt'));
        
    }
   
}

