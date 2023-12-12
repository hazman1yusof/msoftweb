<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;

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
        $request->unit = session('unit');
        try {
            switch($request->oper){
                case 'add':
                    return $this->add($request);
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

    public function add(Request $request){

        DB::beginTransaction();
        
        try {

            $duplicate = DB::table('material.stockloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('deptcode','=',$request->deptcode)
                            ->where('itemcode','=',$request->itemcode)
                            ->where('uomcode','=',$request->uomcode)
                            ->where('year','=',$request->year)
                            ->where('unit','=',session('unit'));

            if($duplicate->exists()){
                throw new \Exception("Itemcode ".$request->itemcode." with department ".$request->deptcode." duplicate");
            }

            DB::table('material.stockloc')
                ->insert([
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'deptcode' => $request->deptcode,
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'year' => $request->year,
                    'stocktxntype' => $request->stocktxntype,
                    'frozen' => $request->frozen,
                    'disptype' => $request->disptype,
                    'minqty' => $request->minqty,
                    'maxqty' => $request->maxqty,
                    'reordlevel' => $request->reordlevel,
                    'reordqty' => $request->reordqty,
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
}