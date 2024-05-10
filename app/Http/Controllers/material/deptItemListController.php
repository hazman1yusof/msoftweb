<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\deptItemListExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class deptItemListController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.deptItemList.deptItemList',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new deptItemListExport($request->dept_from,$request->dept_to), 'deptItemListExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $dept_from = $request->dept_from;
        if(empty($request->dept_from)){
            $dept_from = '%';
        } 
        $dept_to = $request->dept_to;

        $stockloc = DB::table('material.stockloc as s')
                ->select('s.idno','s.deptcode','s.itemcode','p.description','s.uomcode','s.qtyonhand','s.stocktxntype','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.disptype', 's.recstatus')
                ->join('material.product as p', function($join){
                    $join = $join->on('p.itemcode', '=', 's.itemcode')
                                ->on('p.uomcode', '=', 's.uomcode')
                                ->where('p.compcode', '=', session('compcode'))
                                ->where('p.unit', '=', session('unit'))
                                ->where('p.recstatus', '=', 'ACTIVE');
                })
                ->where('s.compcode',session('compcode'))
                ->where('s.unit',session('unit'))
                // ->where('s.recstatus', '=', 'ACTIVE')
                ->where('s.year', '=', Carbon::now("Asia/Kuala_Lumpur"))
                ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
                ->orderBy('s.deptcode', 'ASC')
                ->get();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('material.deptItemList.deptItemList_pdfmake',compact('stockloc'));
        
    }
   
}

