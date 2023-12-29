<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\StockTakeExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class stockBalanceController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.stockBalance.stockBalance');
    }

    public function report(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'stockBalance_pdf':
                return $this->stockBalance_pdf($request);
            case 'stockBalance_xls':
                return $this->stockBalance_xls($request);
            case 'stockSheet_pdf':
                return $this->stockSheet_pdf($request);
            case 'stockSheet_xls':
                return $this->stockSheet_xls($request);
            default:
                return 'error happen..';
        }
    }
    
    public function stockBalance_pdf(Request $request){
        // $recno = $request->recno;
        // if(!$recno){
        //     abort(404);
        // }
         $validator = Validator::make($request->all(), [
            'dept_from' => 'required',
            'dept_to' => 'required',
            'item_from' => 'required',
            'item_to' => 'required',
            'year' => 'required',
            'period' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }
        
        
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('material.stockBalance.stockBalance_pdf',compact('phycnthd','phycntdt','company'));
        
    }

    public function stockBalance_xls(Request $request){
        return Excel::download(new StockTakeExport($request->recno), 'StockTakeExport.xlsx');
    }

    public function stockSheet_pdf(Request $request){
        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }
        
        $phycnthd = DB::table('material.phycnthd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->first();

        $phycntdt = DB::table('material.phycntdt AS pdt')
            ->select('pdt.idno','pdt.compcode','pdt.srcdept','pdt.phycntdate','pdt.phycnttime','pdt.lineno_','pdt.itemcode','pdt.uomcode','pdt.adduser','pdt.adddate','pdt.upduser','pdt.upddate','pdt.unitcost','pdt.phyqty','pdt.thyqty','pdt.recno','pdt.expdate','pdt.updtime','pdt.stktime','pdt.frzdate','pdt.frztime','pdt.dspqty','pdt.batchno','p.description')
            ->leftJoin('material.product as p', function($join) use ($request){
                        $join = $join->on('p.itemcode', '=', 'pdt.itemcode')
                                     ->on('p.uomcode', '=', 'pdt.uomcode')
                                     ->where('p.unit','=',session('unit'))
                                     ->where('p.compcode','=',session('compcode'));
                    })
            ->where('pdt.compcode','=',session('compcode'))
            ->where('pdt.recno','=',$recno)
            ->get();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('material.stockCount.stockCount_pdfmake',compact('phycnthd','phycntdt','company'));
        
    }

    public function stockSheet_xls(Request $request){
        return Excel::download(new StockTakeExport($request->recno), 'StockTakeExport.xlsx');
    }
}

