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
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name')
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();
        
        $datetime = DB::table('nursing.nurs_investigation')
                    // ->select('idno','compcode','mrn','episno','inv_code','inv_cat','entereddate','enteredtime','enteredby','values','adduser','adddate','upduser','upddate','lastuser','lastupdate','computerid','lastcomputerid','ipaddress','lastipaddress')
                    // ->select(DB::raw('DATE_FORMAT(entereddate, "%d/%l/%Y") as date'),DB::raw('TIME(enteredtime) as time'))
                    ->select('entereddate','enteredtime',DB::raw('DATE_FORMAT(entereddate, "%d/%l/%Y") as date'),DB::raw('TIME(enteredtime) as time'))
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno)
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
        // dd($inv_type);
        
        // $inv_type = DB::table('nursing.nurs_invest_type')
        //             ->select('inv_code')
        //             ->where('compcode','=',session('compcode'))
        //             ->orderBy('idno','asc')
        //             ->get();
        // dd($inv_type);
        
        $inv_cat = DB::table('nursing.nurs_invest_cat')
                    ->where('compcode','=',session('compcode'))
                    ->orderBy('idno','asc')
                    ->get();
        // dd($inv_cat);
        
        $nurs_investigation = DB::table('nursing.nurs_investigation')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$mrn)
                            ->where('episno','=',$episno)
                            ->get();
        // dd($nurs_investigation);
        
        // $array_inv = [];
        // $array_values = [];
        // foreach($inv_type as $key => $value){
        //     // $inv_cat = DB::table('nursing.nurs_invest_cat')
        //     //             ->select('inv_code','inv_cat')
        //     //             ->where('compcode','=',session('compcode'))
        //     //             ->where('inv_code','=',$value->inv_code)
        //     //             ->orderBy('idno','asc')
        //     //             ->get();
            
        //     foreach($datetime as $dt_key => $dt_value){
        //         $nurs_inv = DB::table('nursing.nurs_investigation')
        //                     ->select('inv_code','inv_cat','entereddate','enteredtime','values')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$mrn)
        //                     ->where('episno','=',$episno)
        //                     ->where('inv_code','=',$value->inv_code)
        //                     ->where('inv_cat','=',$value->inv_cat)
        //                     // ->where('entereddate','=',$dt_value->date)
        //                     // ->where('enteredtime','=',$dt_value->time)
        //                     ->first();
                
        //         array_push($array_values, $nurs_inv);
        //     }
            
        //     // array_push($array_inv, $inv_cat);
        // }
        // dd($array_values);
        
        return view('hisdb.nursingnote.invChart_pdfmake', compact('pat_mast','datetime','inv_type','inv_cat','nurs_investigation'));
        
    }
    
}