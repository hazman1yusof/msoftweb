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

            $curryear = Carbon::now("Asia/Kuala_Lumpur")->format('Y');
            $curryear = 2025;

            $stockloc = DB::table('material.stockloc')
                            ->where('year',$curryear)
                            ->where('compcode',session('compcode'));

            if((strtoupper($request->dept_from) == 'ZZZ' && strtoupper($request->dept_to) == 'ZZZ')){

            }else{
                $stockloc = $stockloc->whereBetween('deptcode',[$request->dept_from,$request->dept_to]);
            }

            if(strtoupper($request->item_from) == 'ZZZ' && strtoupper($request->item_to) == 'ZZZ'){

            }else{
                $stockloc = $stockloc->whereBetween('itemcode',[$request->item_from,$request->item_to]);
            }

            $counter=0;
            foreach ($stockloc->get() as $key => $value){
                $exists = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$value->deptcode)
                            ->where('itemcode',$value->itemcode)
                            ->where('uomcode',$value->uomcode)
                            ->where('year',$request->year)
                            ->where('unit',$value->unit)
                            // ->where('unit',session('unit'))
                            ->exists();

                if($exists){
                    continue;
                }else{
                    $counter++;
                    DB::table('material.stockloc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'deptcode' => $value->deptcode,
                            'itemcode' => $value->itemcode,
                            'uomcode' => $value->uomcode,
                            'year' => $request->year,
                            'stocktxntype' => $value->stocktxntype,
                            'disptype' => $value->disptype,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => $value->recstatus,
                            'unit' => $value->unit,
                        ]);
                }
            }

            DB::commit();

            $responce = new stdClass();
            $responce->counter = $counter;
            echo json_encode($responce);

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

            $lastyear = $lastyear;//newyear

            $stockloc = DB::table('material.stockloc')
                            ->where('year',$request->year)//ni baru last year
                            ->where('compcode',session('compcode'));

            if(strtoupper($request->dept_from) == 'ZZZ' && strtoupper($request->dept_to) == 'ZZZ'){

            }else{
                $stockloc = $stockloc->whereBetween('deptcode',[$request->dept_from,$request->dept_to]);
            }

            if(strtoupper($request->item_from) == 'ZZZ' && strtoupper($request->item_to) == 'ZZZ'){

            }else{
                $stockloc = $stockloc->whereBetween('itemcode',[$request->item_from,$request->item_to]);
            }
            
            $counter=0;
            foreach ($stockloc->get() as $key => $value){
                $stockloc_ = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$value->deptcode)
                            ->where('itemcode',$value->itemcode)
                            ->where('uomcode',$value->uomcode)
                            ->where('year',$lastyear)
                            ->where('unit',$value->unit);

                if(!$stockloc_->exists()){
                    continue;
                }else{
                    $counter++;

                    $array_obj = (array)$value;
                    $get_bal = $this->get_bal($array_obj);

                    DB::table('material.stockloc')
                        ->where('compcode',session('compcode'))
                        ->where('deptcode',$value->deptcode)
                        ->where('itemcode',$value->itemcode)
                        ->where('uomcode',$value->uomcode)
                        ->where('year',$lastyear)
                        ->where('unit',$value->unit)
                        // ->where('unit',session('unit'))
                        ->update([
                            'openbalqty' => $get_bal->open_balqty,
                            'openbalval' => $get_bal->open_balval,
                            'qtyonhand' => $value->qtyonhand,
                            'minqty' => $value->minqty,
                            'maxqty' => $value->maxqty,
                            'reordlevel' => $value->reordlevel,
                            'reordqty' => $value->reordqty,
                        ]);
                }
            }

            // $counter_exp=0;
            // foreach ($stockloc->get() as $key => $value){
            //     //yg tahun baru
            //     $exists = DB::table('material.stockexp')
            //                 ->where('stockexp.compcode','=',session('compcode'))
            //                 ->where('stockexp.unit',$request->unit)
            //                 // ->where('stockexp.unit','=',session('unit'))
            //                 ->where('stockexp.deptcode','=',$value->deptcode)
            //                 ->where('stockexp.itemcode','=',$value->itemcode)
            //                 ->where('stockexp.uomcode','=',$value->uomcode)
            //                 ->where('stockexp.year','=', $lastyear)
            //                 ->exists();

            //     if($exists){
            //         continue;
            //     }else{
            //         //yg tahun lepas
            //         $stockexp_lama = DB::table('material.stockexp')
            //                         ->where('stockexp.compcode','=',session('compcode'))
            //                         ->where('stockexp.unit',$request->unit)
            //                         // ->where('stockexp.unit','=',session('unit'))
            //                         ->where('stockexp.deptcode','=',$value->deptcode)
            //                         ->where('stockexp.itemcode','=',$value->itemcode)
            //                         ->where('stockexp.uomcode','=',$value->uomcode)
            //                         ->where('stockexp.year','=', $request->year);

            //         if($stockexp_lama->exists()){
            //             foreach ($stockexp_lama->get() as $obj_exp) {
            //                 $counter_exp++;
            //                 DB::table('material.stockexp')
            //                     ->insert([
            //                         'compcode' => session('compcode'), 
            //                         'unit' => $obj_exp->unit, 
            //                         'deptcode' => $obj_exp->deptcode, 
            //                         'itemcode' => $obj_exp->itemcode, 
            //                         'uomcode' => $obj_exp->uomcode, 
            //                         'expdate' => $obj_exp->expdate, 
            //                         'batchno' => $obj_exp->batchno, 
            //                         'balqty' => $obj_exp->balqty, 
            //                         'adduser' => $obj_exp->adduser, 
            //                         'adddate' => $obj_exp->adddate, 
            //                         'upduser' => $obj_exp->upduser, 
            //                         'upddate' => $obj_exp->upddate, 
            //                        // 'lasttt' => 'GRN', 
            //                         'year' => $lastyear
            //                     ]);
            //             }
            //         }else{
            //             $counter_exp++;
            //             DB::table('material.stockexp')
            //                     ->insert([
            //                         'compcode' => session('compcode'), 
            //                         'unit' => $value->unit, 
            //                         'deptcode' => $value->deptcode, 
            //                         'itemcode' => $value->itemcode, 
            //                         'uomcode' => $value->uomcode, 
            //                         'balqty' => $value->qtyonhand, 
            //                         'adduser' => session('username'), 
            //                         'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //                        // 'lasttt' => 'GRN', 
            //                         'year' => $lastyear
            //                     ]);
            //         }
            //     }
            // }

            DB::commit();

            $responce = new stdClass();
            $responce->counter = $counter;
            // $responce->counter_exp = $counter_exp;
            echo json_encode($responce);

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

