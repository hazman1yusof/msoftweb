<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class InvChartController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function invChart_chart(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name')
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    // ->where('pm.Episno','=',$episno)
                    ->first();
        
        $datetime = DB::table('nursing.nurs_investigation')
                    // ->select(DB::raw('DATE_FORMAT(entereddate, "%d/%m/%Y") as date'),DB::raw('TIME(enteredtime) as time'))
                    ->select('entereddate','enteredtime',DB::raw('DATE_FORMAT(entereddate, "%d/%m/%Y") as date'),DB::raw('TIME(enteredtime) as time'))
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno)
                    ->whereBetween('entereddate',[$datefr,$dateto])
                    ->groupBy('entereddate','enteredtime')
                    ->orderBy('entereddate','asc')
                    ->orderBy('enteredtime','asc')
                    ->get();
        
        $inv_type = DB::table('nursing.nurs_invest_type as type')
                    ->select('type.inv_code','cat.inv_cat')
                    ->leftJoin('nursing.nurs_invest_cat as cat', function ($join){
                        $join = $join->on('cat.inv_code','=','type.inv_code')
                                    ->where('cat.compcode','=',session('compcode'));
                    })
                    ->where('type.compcode','=',session('compcode'))
                    ->orderBy('type.idno','asc')
                    ->orderBy('cat.idno','asc')
                    ->get();
        
        $inv_cat = DB::table('nursing.nurs_invest_cat')
                    ->where('compcode','=',session('compcode'))
                    ->orderBy('idno','asc')
                    ->get();
        
        $nurs_investigation = DB::table('nursing.nurs_investigation')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$mrn)
                            ->where('episno','=',$episno)
                            ->get();
        // dd($nurs_investigation);
        
        return view('hisdb.nursingnote.invChart_pdfmake', compact('pat_mast','datetime','inv_type','inv_cat','nurs_investigation'));
        
    }
    
}