<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\util\invtran_util;

class InventoryRequestController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.inventoryRequest.inventoryRequest');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'cancel':
                return $this->cancel($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $request_no = $this->request_no('SR', $request->reqdept);
        $recno = $this->recno('PUR','SR');

        DB::beginTransaction();

        $table = DB::table("material.ivreqhd");

        $array_insert = [
            'source' => 'PUR',
            'trantype' => 'SR',
            'ivreqno' => $request_no,
            'recno' => $recno,
            'reqdept' => strtoupper($request->reqdept),
            'reqtodept' => strtoupper($request->reqtodept),
            'reqtype' => strtoupper($request->reqtype),
            'reqdt' => $request->reqdt,
            'amount' => $request->amount,
            'remarks' => strtoupper($request->remarks),
            'reqpersonid' => session('username'),           
            'compcode' => session('compcode'),
            'unit'    => session('unit'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN'
        ];

        // foreach ($field as $key => $value){
        //     $array_insert[$value] = $request[$request->field[$key]];
        // }

        try {

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            // if(!empty($request->referral)){
            //     ////ni kalu dia amik dari do
            //     ////amik detail dari do sana, save dkt do detail, amik total amount
            //     $totalAmount = $this->save_dt_from_othr_do($request->referral,$recno);

            //     $srcdocno = $request->delordhd_srcdocno;
            //     $delordno = $request->delordhd_delordno;*/

            //     ////dekat do header sana, save balik delordno dkt situ
            //     DB::table('material.delordno')
            //     ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
            //     ->update(['delordno' => $ivtmphd
            // }

            $responce = new stdClass();
            $responce->ivreqno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }

    }

    public function edit(Request $request){
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        DB::beginTransaction();

        $table = DB::table("material.ivreqhd");

        $array_update = [
          
            'reqdept' => strtoupper($request->reqdept),
            'reqtodept' => strtoupper($request->reqtodept),
            'reqtype' => strtoupper($request->reqtype),
            'reqdt' => $request->reqdt,
            'amount' => $request->amount,
            'remarks' => strtoupper($request->remarks),
            'reqpersonid' => session('username'),           
            'compcode' => session('compcode'),
            'unit'    => session('unit'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        // foreach ($field as $key => $value) {
        //     $array_update[$value] = $request[$request->field[$key]];
        // }

        try {
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            $responce = new stdClass();
           // $responce->totalAmount = $request->delordhd_totamount;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e->getMessage(), 500);
        }
    }

    public function posted(Request $request){

        DB::beginTransaction();

        try {

            DB::table('material.ivreqhd')
                ->where('recno','=',$ivreqhd->recno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'POSTED' 
                ]);
            

            /*$queries = DB::getQueryLog();
            dump($queries);*/


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e->getMessage(), 500);
        }

    }

    public function cancel(Request $request){
        
    }

    public function save_dt_from_othr_do($refer_recno,$recno){
        $do_dt = DB::table('material.delorddt')
                ->select('compcode', 'recno', 'lineno_', 'pricecode', 'itemcode', 'uomcode','pouom',
                    'suppcode','trandate','deldept','deliverydate','qtydelivered','unitprice', 'taxcode', 
                    'perdisc', 'amtdisc', 'amtslstax', 'amount','expdate','batchno','rem_but','remarks')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        // foreach ($do_dt as $key => $ivtmphd){
        //     ///1. insert detail we get from existing purchase order
        //     $table = DB::table("material.delorddt");
        //     $table->insert([
        //         'compcode' => $ivtmphd
        //         'recno' => $ivtmphd
        //         'lineno_' => $ivtmphd
        //         'pricecode' => $ivtmphd
        //         'itemcode' => $ivtmphd
        //         'uomcode' => $ivtmphd
        //         'pouom' => $ivtmphd
        //         'suppcode'=> $ivtmphd
        //         'trandate'=> $ivtmphd
        //         'deldept'=> $ivtmphd
        //         'deliverydate'=> $ivtmphd
        //         'qtydelivered' => $ivtmphd
        //         'unitprice' => $ivtmphd
        //         'taxcode' => $ivtmphd
        //         'perdisc' => $ivtmphd
        //         'amtdisc' => $ivtmphd
        //         'amtslstax' => $ivtmphd
        //         'amount' => $ivtmphd
        //         'expdate'=> $ivtmphd
        //         'batchno'=> $ivtmphd
        //         'rem_but'=> $ivtmphd
        //         'adduser' => $ivtmphd
        //         'adddate' => $ivtmphd
        //         'recstatus' => $ivtmphd
        //         'remarks' => $ivtmphd
        //     ]);
        // }
        ///2. calculate total amount from detail earlier
        $amount = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','<>','DELETE')
                    ->sum('amount');

        ///3. then update to header
        $table = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno);
        $table->update([
                'amount' => $ivtmphd
                //'subamount' => $ivtmphd
            ]);

        return $amount;
    }

    public function check_sequence_backdated($ivtmphd){

        $sequence_obj = DB::table('material.sequence')
                ->where('trantype','=',$ivtmphd->trantype)
                ->where('dept','=',$ivtmphd->txndept);

        if(!$sequence_obj->exists()){
            throw new \Exception("sequence doesnt exists", 500);
        }

        $sequence = $sequence_obj->first();

        $date = Carbon::parse($ivtmphd->trandate);
        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        if($diff > intval($sequence->backday)){
            throw new \Exception("backdated sequence exceed ".$sequence->backday.' days', 500);
        }

    }
}

