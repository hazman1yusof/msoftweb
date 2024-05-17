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
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.POListing.POListing',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new POListingExport($request->datefr,$request->dateto), 'POListingExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');

        $POListing = DB::table('material.purordhd as h')
                ->select('h.recno')
                ->where('h.compcode',session('compcode'))
                ->where('h.unit',session('unit'))
                ->where('h.recstatus', '!=', 'CANCELLED')
                ->whereBetween('h.purdate', [$datefr, $dateto])
                ->orderBy('h.recno', 'ASC')
                ->distinct('h.recno');

        $POListing = $POListing->get('h.recno');
        
        // dd($POListing);

        $purordhd = DB::table('material.purordhd as h')
                ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.purordno', 'h.purdate', 'h.suppcode', 's.name as supp_name', 'h.totamount')
                ->join('material.supplier as s', function($join){
                    $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                ->where('s.compcode', '=', session('compcode'))
                                ->where('s.recstatus', '=', 'ACTIVE');
                })
                ->where('h.compcode',session('compcode'))
                ->where('h.unit',session('unit'))
                ->where('h.recstatus', '!=', 'CANCELLED')
                ->whereBetween('h.purdate', [$datefr, $dateto])
                ->orderBy('h.purdate', 'ASC')
                ->get();
            // dd($purordhd);

        $purorddt = DB::table('material.purorddt as d')
            ->select('d.compcode', 'd.recno', 'd.lineno_', 'd.suppcode', 'd.purdate','d.pricecode', 'd.itemcode', 'p.description','d.uomcode','d.pouom','d.qtyorder','d.qtyoutstand','d.qtyrequest','d.qtydelivered', 'd.perslstax', 'd.unitprice', 'd.taxcode', 'd.perdisc', 'd.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount','d.amount', 'd.unit')
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
            ->whereBetween('d.purdate', [$datefr, $dateto])
            ->orderBy('d.purdate', 'ASC')
            ->get();
            // dd($purorddt);

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.POListing.POListing_pdfmake',compact('POListing', 'purordhd', 'purorddt'));
        
    }
   
}

