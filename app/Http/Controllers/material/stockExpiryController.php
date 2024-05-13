<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\stockExpiryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class stockExpiryController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('material.stockExpiry.stockExpiry',['company_name' => $comp->name]);
    }

    public function showExcel(Request $request){
        return Excel::download(new stockExpiryExport($request->item_from,$request->item_to,$request->datefr,$request->dateto), 'stockExpiryExport.xlsx');
    }

    public function showpdf(Request $request){
        
        $item_from = $request->item_from;
        if(empty($request->item_from)){
            $item_from = '%';
        } 
        $item_to = $request->item_to;
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');

        $stockexp = DB::table('material.stockexp as e')
                ->select('e.idno', 'e.compcode', 'e.deptcode', 'e.itemcode', 'e.uomcode', 'e.expdate', 'e.unit', 'p.uomcode as p_uom', 'p.description as p_desc', 'p.avgcost', 'p.currprice', 'e.balqty', 'e.batchno')
                ->join('material.product as p', function($join){
                    $join = $join->on('p.itemcode', '=', 'e.itemcode')
                                ->on('p.uomcode', '=', 'e.uomcode')
                                ->where('p.compcode', '=', session('compcode'))
                                ->where('p.unit', '=', session('unit'))
                                ->where('p.recstatus', '=', 'ACTIVE');
                })
                ->where('e.compcode',session('compcode'))
                ->where('e.unit',session('unit'))
                ->where('e.year', '=', Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                ->whereBetween('e.itemcode',[$item_from,$item_to.'%'])
                ->whereBetween('e.expdate', [$datefr, $dateto])
                ->orderBy('e.itemcode', 'ASC')
                ->get();

        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        return view('material.stockExpiry.stockExpiry_pdfmake',compact('stockexp'));
        
    }
   
}

