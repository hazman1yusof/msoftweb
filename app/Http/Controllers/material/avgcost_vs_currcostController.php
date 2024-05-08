<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\avgcost_vs_currcostExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class avgcost_vs_currcostController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.avgcost_vs_currcost.avgcost_vs_currcost',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new avgcost_vs_currcostExport($request->item_from,$request->item_to), 'avgcost_vs_currcostExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $item_from = $request->item_from;
        if(empty($request->item_from)){
            $item_from = '%';
        } 
        $item_to = $request->item_to;

        $product = DB::table('material.product as p')
            ->select('p.idno','p.compcode','p.itemcode','p.recstatus', 'p.suppcode', 'p.unit', 'p.description', 'p.uomcode', 'p.qtyonhand', 'p.avgcost', 'p.currprice', 'p.groupcode', 'p.productcat', 'p.chgflag', 's.SuppCode', 's.Name')
            ->leftJoin('material.supplier as s', function($join){
                $join = $join->on('s.SuppCode', '=', 'p.suppcode')
                            ->where('s.compcode', '=', session('compcode'));
            })
            ->where('p.compcode','=',session('compcode'))
            ->where('p.recstatus', '=', 'ACTIVE')
            ->whereBetween('p.itemcode', [$item_from, $item_to])
            ->orderBy('p.itemcode', 'ASC')
            ->get();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('material.avgcost_vs_currcost.avgcost_vs_currcost_pdfmake',compact('product'));
        
        
    }
   
}

