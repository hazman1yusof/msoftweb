<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;

class StocklocController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        $year = DB::table('sysdb.period')->select('year')->where('compcode','=','9A')->orderBy('year','desc')->get();
        return view('material.Stock Location.stockLoc',compact('year'));
    }

    public function form(Request $request)
    {  
        $request->noduplicate='yes';
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }
}