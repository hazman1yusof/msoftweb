<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\stockBalance_xlsExport;
use App\Exports\stockSheet_xlsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class YearEndController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function yearEnd(Request $request)
    {   
        return view('material.yearEnd.yearEndStock');
    }

    public function yearEndProcess(Request $request)
    {   
        return view('material.yearEnd.yearEndStockProcess');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'yearEnd_form':
                return $this->yearEnd_form($request);
            case 'yearEndProcess_form':
                return $this->yearEndProcess_form($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            default:
                return 'error happen..';
        }
    }

    public function yearEnd_form(Request $request){
        DB::beginTransaction();

        try {
            $stockloc = DB::table('material.stockloc')
                        ->where('compcode',session('compcode'))
                        ->where('deptcode','PCS')
                        ->orderBy('idno','DESC');

            if(!$stockloc->exists()){
                throw new \Exception("No stockloc for PCS");
            }

            $lastyear = $stockloc->first()->year;

            $stockloc = DB::table('material.stockloc')
                            ->where('year',$lastyear)
                            ->where('compcode',session('compcode'));

            if(strtoupper($request->dept_from) == 'ZZZ' && strtoupper($request->dept_to) == 'ZZZ'){

            }else{
                $stockloc = $stockloc->whereBetween('deptcode',[$request->dept_from,$request->dept_to]);
            }

            if(strtoupper($request->item_from) == 'ZZZ' && strtoupper($request->item_to) == 'ZZZ'){

            }else{
                $stockloc = $stockloc->whereBetween('itemcode',[$request->item_from,$request->item_to]);
            }

            foreach ($stockloc->get() as $key => $value){
                $exists = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$value->deptcode)
                            ->where('itemcode',$value->itemcode)
                            ->where('uomcode',$value->uomcode)
                            ->where('year',$request->year)
                            ->where('unit',session('unit'))
                            ->exists();

                if($exists){
                    continue;
                }else{
                    DB::table('material.stockloc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'deptcode' => $value->deptcode,
                            'itemcode' => $value->itemcode,
                            'uomcode' => $value->uomcode,
                            'year' => $request->year,
                            'stocktxntype' => $request->stocktxntype,
                            'disptype' => $request->disptype,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => $value->recstatus,
                            'unit' => session('unit'),
                        ]);
                }
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e, 500);
        }
    }

    public function yearEndProcess_form(Request $request){
       
        DB::beginTransaction();

        try {
            $lastyear = DB::table('material.stockloc')
                        ->where('compcode',session('compcode'))
                        ->max('year');

            $lastyear = $lastyear;

            $stockloc = DB::table('material.stockloc')
                            ->where('year',$request->year)
                            ->where('compcode',session('compcode'));

            if(strtoupper($request->dept_from) == 'ZZZ' && strtoupper($request->dept_to) == 'ZZZ'){

            }else{
                $stockloc = $stockloc->whereBetween('deptcode',[$request->dept_from,$request->dept_to]);
            }

            if(strtoupper($request->item_from) == 'ZZZ' && strtoupper($request->item_to) == 'ZZZ'){

            }else{
                $stockloc = $stockloc->whereBetween('itemcode',[$request->item_from,$request->item_to]);
            }

            foreach ($stockloc->get() as $key => $value){
                $stockloc = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$value->deptcode)
                            ->where('itemcode',$value->itemcode)
                            ->where('uomcode',$value->uomcode)
                            ->where('year',$lastyear)
                            ->where('unit',session('unit'));

                if(!$stockloc->exists()){
                    continue;
                }else{

                    $array_obj = (array)$value;
                    $get_bal = $this->get_bal($array_obj);

                    DB::table('material.stockloc')
                        ->where('compcode',session('compcode'))
                        ->where('deptcode',$value->deptcode)
                        ->where('itemcode',$value->itemcode)
                        ->where('uomcode',$value->uomcode)
                        ->where('year',$lastyear)
                        ->where('unit',session('unit'))
                        ->update([
                            'openbalqty' => $get_bal->open_balqty,
                            'openbalval' => $get_bal->open_balval,
                        ]);
                }
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e, 500);
        }
    }

    public function get_bal($array_obj){
        $open_balqty = $array_obj['openbalqty'];
        $open_balval = $array_obj['openbalval'];

        for ($from = 1; $from <= 12; $from++) { 
            $open_balqty = $open_balqty + $array_obj['netmvqty'.$from];
            $open_balval = $open_balval + $array_obj['netmvval'.$from];
        }

        $responce = new stdClass();
        $responce->open_balqty = $open_balqty;
        $responce->open_balval = $open_balval;
        return $responce;
    }
}

