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
        $year = DB::table('sysdb.period')->select('year')->where('compcode','=',session('compcode'))->orderBy('year','desc')->get();
        return view('material.Stock Location.stockLoc',compact('year'));
    }

    public function form(Request $request)
    {  
        try {
            switch($request->oper){
                case 'add':
                    $duplicate = DB::table('material.stockloc')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('deptcode','=',$request->deptcode)
                                    ->where('itemcode','=',$request->itemcode)
                                    ->where('uomcode','=',$request->uomcode)
                                    ->where('year','=',$request->year)
                                    ->where('unit','=',$request->unit);

                    if($duplicate->exists()){
                        throw new \Exception("Itemcode ".$request->itemcode." with department ".$request->deptcode." duplicate");
                    }

                    return $this->defaultAdd($request);
                case 'edit':
                    return $this->defaultEdit($request);
                case 'del':
                    return $this->defaultDel($request);
                default:
                    return 'error happen..';
            }

        }catch (\Exception $e) {
            
            return response($e->getMessage(), 500);
        }
        
    }
}