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
            case 'reopen':
                return $this->reopen($request);
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

        // $request_no = $this->request_no('SR', $request->reqdept);
        // $recno = $this->recno('PUR','SR');

        $request_no = 0;
        $recno = 0;
        $compcode = 'DD';

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
            'compcode' => $compcode,
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
    
            $responce = new stdClass();
            $responce->ivreqno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;
            $responce->adduser = session('username');
            $responce->adddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
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
            $responce->upduser = session('username');
            $responce->upddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
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

            foreach ($request->idno_array as $idno){

                $ivreqhd = DB::table('material.ivreqhd')
                                ->where('idno','=',$idno)
                                ->first();

                if($ivreqhd->recstatus == 'POSTED'){
                    continue;
                }

                DB::table('material.ivreqhd')
                    ->where('idno','=',$idno)
                    ->update([
                        'postedby' => session('username'),
                        'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'POSTED' 
                    ]);

                DB::table('material.ivreqdt')
                    ->where('recno','=',$ivreqhd->recno)
                    ->where('unit','=',session('unit'))
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','!=','DELETE')
                    ->update([
                        'recstatus' => 'POSTED' 
                    ]);

            }    

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();            
            return response($e->getMessage(), 500);
        }

    }

    public function cancel(Request $request){
        DB::beginTransaction();

        try {
            foreach ($request->idno_array as $idno){

                $ivreqhd = DB::table('material.ivreqhd')
                    ->where('idno','=',$idno)
                    ->first();

                if($ivreqhd->recstatus == 'CANCELLED'){
                    continue;
                }

                DB::table('material.ivreqhd')
                    ->where('idno','=',$idno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'cancelby' => session('username'),
                        'canceldate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'CANCELLED' 
                    ]);

            }    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 500);
        }
    }

    public function reopen(Request $request){

        DB::beginTransaction();

        try{

            DB::table('material.ivreqhd')
                ->where('recno','=',$request->recno)
                ->where('unit','=',session('unit'))
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN' 
                ]);

            DB::table('material.ivreqdt')
                ->where('recno','=',$request->recno)
                ->where('unit','=',session('unit'))
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','!=','DELETE')
                ->update([
                    'recstatus' => 'OPEN' 
                ]);
              
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
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

    public function showpdf(Request $request){
        $idno = $request->idno;
        if(!$idno){
            abort(404);
        }
        
        $ivreqhd = DB::table('material.ivreqhd as ivhd')
                    ->select('ivhd.compcode','ivhd.source','ivhd.trantype','ivhd.reqdept','ivhd.reqtodept','ivhd.recno','ivhd.ivreqno','ivhd.reqdt','ivhd.reqtype','ivhd.reqpersonid','ivhd.amount','ivhd.remarks','ivhd.recstatus','ivhd.adduser','ivhd.adddate','ivhd.upduser','ivhd.upddate','ivhd.cancelby','ivhd.canceldate','ivhd.reopenby','ivhd.reopendate','ivhd.authpersonid','ivhd.authdate','ivhd.unit','ivhd.postedby','ivhd.postdate')
                    ->where('ivhd.compcode','=',session('compcode'))
                    ->where('ivhd.idno','=',$idno)
                    ->first();
        
        $ivreqdt = DB::table('material.ivreqdt AS ivdt', 'material.productmaster AS p', 'material.uom as u')
                    ->select('podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.pricecode', 'podt.itemcode', 'p.description', 'podt.uomcode', 'podt.pouom', 'podt.qtyorder', 'podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc', 'podt.amtslstax as tot_gst','podt.netunitprice', 'podt.totamount','podt.amount', 'podt.rem_but AS remarks_button', 'podt.remarks', 'podt.recstatus', 'podt.unit', 'u.description as uom_desc')
                    ->leftJoin('material.uom as u', function ($join){
                        $join = $join->on('u.uomcode', '=', 'podt.pouom')
                                    ->where('u.compcode','=',session('compcode'));
                    })
                    ->leftJoin('material.productmaster as p', function ($join){
                        $join = $join->on('p.itemcode', '=', 'podt.itemcode')
                                    ->where('p.compcode','=',session('compcode'));
                                    // ->where('p.unit','=',session('unit'));
                    })
                    ->where('podt.compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->get();
                    
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $supplier = DB::table('material.supplier')
                    ->where('compcode','=',session('compcode'))
                    ->where('SuppCode','=',$purordhd->suppcode)
                    ->first();
        
        $deldept = DB::table('material.deldept')
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$purordhd->deldept)
                    ->first();
        
        $total_tax = DB::table('material.purorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->sum('amtslstax');
        
        $total_discamt = DB::table('material.purorddt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->sum('amtdisc');
        
        $totamount_expld = explode(".", (float)$purordhd->totamount);
        
        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1]). "CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('material.inventoryRequest.inventoryRequest_pdfmake',compact('purordhd','purorddt','totamt_eng', 'company', 'supplier','deldept', 'total_tax', 'total_discamt'));
    }
}

