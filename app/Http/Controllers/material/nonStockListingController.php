<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\nonStockListingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class nonStockListingController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.nonStockListing.nonStockListing',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new nonStockListingExport($request->item_from,$request->item_to), 'nonStockListingExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $item_from = $request->item_from;
        if(empty($request->item_from)){
            $item_from = '%';
        } 
        $item_to = $request->item_to;

        $product = DB::table('material.product as p')
            ->select('p.idno','p.compcode','p.itemcode','p.description','p.groupcode','p.uomcode','p.qtyonhand','p.avgcost','p.recstatus','p.currprice','p.unit')
            ->where('p.compcode','=',session('compcode'))
            ->where('p.unit','=',session('unit'))
            ->where('p.groupcode', '!=', 'Stock')
            ->where('p.recstatus', '=', 'ACTIVE')
            ->whereBetween('p.itemcode', [$item_from, $item_to])
            ->orderBy('p.itemcode', 'ASC')
            ->get();

            $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('material.nonStockListing.nonStockListing_pdfmake',compact('product'));
        
    }
   
}

