<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\util\invtran_util;

class InventoryTransactionController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.inventoryTransaction.inventoryTransaction');
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

        DB::beginTransaction();
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        // $request_no = $this->request_no($request->trantype, $request->txndept);
        // $recno = $this->recno('IV','IT');

        try {

            if(!empty($request->referral)){
                $request_no = $this->request_no($request->trantype, $request->txndept);
                $recno = $this->recno('IV','IT');
                $compcode = session('compcode');
            }else{
                $request_no = 0;
                $recno = 0;
                $compcode = 'DD';
            }

            $table = DB::table("material.ivtmphd");

            $array_insert = [
                'source' => 'IV',
                'trantype' => $request->trantype,
                'docno' => $request_no,
                'recno' => $recno,
                'trantime' => $request->trantime,
                'compcode' => $compcode,
                'unit'    => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN'
            ];

            foreach ($field as $key => $value) {
                if(is_string($request[$request->field[$key]])){
                    $array_insert[$value] = strtoupper($request[$request->field[$key]]);
                }else{
                    $array_insert[$value] = $request[$request->field[$key]];
                }
            }

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            if(!empty($request->referral)){
                ////ni kalu dia amik dari ivreq
                ////amik detail dari ivreq sana, save dkt ivreqdt, amik total amount
                $totalAmount = $this->save_dt_from_othr_ivreq($request->referral,$recno);

                $ivreqno = $request->ivreqno;

                // ////dekat do header sana, save balik delordno dkt situ
                // DB::table('material.delordno')
                // ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                // ->update(['delordno' => $ivtmphd
            }

            $responce = new stdClass();
            $responce->docno = $request_no;
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

        $table = DB::table("material.ivtmphd");

        $array_update = [
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        foreach ($field as $key => $value) {
            $array_update[$value] = $request[$request->field[$key]];
        }

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


            foreach ($request->idno_array as $idno){
                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $ivtmphd = DB::table('material.ivtmphd')
                            ->where('idno','=',$idno)
                            ->first();

                $this->check_sequence_backdated($ivtmphd);

                DB::table("material.IvTxnHd")
                    ->insert([
                        'AddDate'  => $ivtmphd->adddate,
                        'AddUser'  => $ivtmphd->adduser,
                        'Amount'   => $ivtmphd->amount,
                        'CompCode' => $ivtmphd->compcode,
                        'unit'     => $ivtmphd->unit,
                        'DateActRet'   => $ivtmphd->dateactret,
                        'DateSupRet'   => $ivtmphd->datesupret,
                        'DocNo'    => $ivtmphd->docno,
                        'IvReqNo'  => $ivtmphd->ivreqno,
                        'RecNo'    => $ivtmphd->recno,
                        'RecStatus'    => $ivtmphd->recstatus,
                        'Reference'    => $ivtmphd->reference,
                        'Remarks'  => $ivtmphd->remarks,
                        'ResPersonId'  => $ivtmphd->respersonid,
                        'SndRcv'   => $ivtmphd->sndrcv,
                        'SndRcvType'   => $ivtmphd->sndrcvtype,
                        'Source'   => $ivtmphd->source,
                        'SrcDocNo' => $ivtmphd->srcdocno,
                        'TranDate' => $ivtmphd->trandate,
                        'TranTime' => $ivtmphd->trantime,
                        'TranType' => $ivtmphd->trantype,
                        'TxnDept'  => $ivtmphd->txndept,
                        'UpdDate'  => $ivtmphd->upddate,
                        'UpdTime'  => $ivtmphd->updtime,
                        'UpdUser'  => $ivtmphd->upduser
                    ]);

                //-- 2. transfer from ivtmpdt to ivtxndt --//
                $ivtmpdt_obj = DB::table('material.ivtmpdt')
                        ->where('ivtmpdt.compcode','=',session('compcode'))
                        ->where('ivtmpdt.recno','=',$ivtmphd->recno)
                        ->where('ivtmpdt.recstatus','!=','DELETE')
                        ->get();

                $this->need_upd_ivreqdt($idno);

                foreach ($ivtmpdt_obj as $value) {

                    $obj_acc = invtran_util::get_acc($value,$ivtmphd);

                    $craccno = $obj_acc->craccno;
                    $crccode = $obj_acc->crccode;
                    $draccno = $obj_acc->draccno;
                    $drccode = $obj_acc->drccode;

                    DB::table('material.ivtxndt')
                        ->insert([
                            'compcode' => $value->compcode, 
                            'recno' => $value->recno, 
                            'lineno_' => $value->lineno_, 
                            'itemcode' => $value->itemcode, 
                            'uomcode' => $value->uomcode,
                            'uomcoderecv' => $value->uomcoderecv,  
                            'txnqty' => $value->txnqty, 
                            'netprice' => $value->netprice, 
                            'adduser' => $value->adduser, 
                            'adddate' => $value->adddate, 
                            'upduser' => $value->upduser, 
                            'upddate' => $value->upddate, 
                            'TranType' => $ivtmphd->trantype,
                            'deptcode'  => $ivtmphd->txndept,
                            // 'productcat' => $productcat, 
                            'draccno' => $draccno, 
                            'drccode' => $drccode, 
                            'craccno' => $craccno, 
                            'crccode' => $crccode, 
                            'expdate' => $value->expdate, 
                            'qtyonhand' => $value->qtyonhand,
                            'qtyonhandrecv' => $value->qtyonhandrecv,  
                            'batchno' => $value->batchno, 
                            'amount' => $value->amount, 
                            'trandate' => $ivtmphd->trandate,
                            'sndrcv' => $ivtmphd->sndrcv,
                        ]);


                    //-- 4. posting stockloc OUT --//

                    $trantype_obj = DB::table('material.ivtxntype')
                        ->where('ivtxntype.compcode','=',session('compcode'))
                        ->where('ivtxntype.trantype','=',$ivtmphd->trantype)
                        ->first();

                    if(strtoupper($trantype_obj->isstype) == 'TRANSFER'){
                        $retval = invtran_util::posting_for_transfer($value,$ivtmphd);
                    
                    }else if(strtoupper($trantype_obj->isstype) == 'ADJUSTMENT' || strtoupper($trantype_obj->isstype) == 'LOAN' || strtoupper($trantype_obj->isstype) == 'ISSUE'){
                        switch (strtoupper($trantype_obj->crdbfl)) {
                            case 'IN':
                                invtran_util::posting_for_adjustment_in($value,$ivtmphd,$trantype_obj->isstype);
                                break;
                            case 'OUT':
                                invtran_util::posting_for_adjustment_out($value,$ivtmphd,$trantype_obj->isstype);
                                break;
                            default:
                                # code...
                                break;
                        }
                    }

                    //--- 7. posting GL ---//

                    //amik yearperiod
                    $yearperiod = $this->getyearperiod($ivtmphd->trandate);
     
                    //1. buat gltran
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => $value->compcode,
                            'adduser' => $value->adduser,
                            'adddate' => $value->adddate,
                            'auditno' => $value->recno,
                            'lineno_' => $value->lineno_,
                            'source' => $ivtmphd->source,
                            'trantype' => $ivtmphd->trantype,
                            'reference' => $ivtmphd->txndept .' '. $ivtmphd->docno,
                            'description' => $ivtmphd->sndrcv,
                            'postdate' => $ivtmphd->trandate,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $drccode,
                            'dracc' => $draccno,
                            'crcostcode' => $crccode,
                            'cracc' => $craccno,
                            'amount' => $value->amount,
                            'idno' => $value->itemcode
                        ]);

                    //2. check glmastdtl utk debit, kalu ada update kalu xde create
                    if($this->isGltranExist($drccode,$draccno,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$drccode)
                            ->where('glaccount','=',$draccno)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $value->amount + $this->gltranAmount,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $drccode,
                                'glaccount' => $draccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                    //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                    if($this->isGltranExist($crccode,$craccno,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$crccode)
                            ->where('glaccount','=',$craccno)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount - $value->amount,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $crccode,
                                'glaccount' => $craccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => -$value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                }

                //--- 8. change recstatus to posted ---//

                DB::table('material.ivtmphd')
                    ->where('recno','=',$ivtmphd->recno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'postedby' => session('username'),
                        'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'POSTED' 
                    ]);

                DB::table('material.ivtmpdt')
                    ->where('recno','=',$ivtmphd->recno)
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','!=','DELETE')
                    ->update([
                        'recstatus' => 'POSTED' 
                    ]);
            }
            

            $queries = DB::getQueryLog();
            dump($queries);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e->getMessage(), 500);
        }

    }

    public function cancel(Request $request){
        
    }

    public function save_dt_from_othr_ivreq($refer_recno,$recno){
        $ivreq_dt = DB::table('material.ivreqdt')
                ->select('compcode', 'recno', 'lineno_', 'itemcode', 'uomcode', 'pouom',
                'maxqty', 'qtyonhand', 'qtyrequest', 'qtybalance', 'qtytxn', 'qohconfirm', 'reqdept', 'ivreqno',
                'recstatus','netprice')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

                foreach ($ivreq_dt as $key => $value) {
                    ///insert detail from existing inventory request
                    $table = DB::table("material.ivtmpdt");
                    $table->insert([
                        'compcode' => session('compcode'), 
                        'recno' => $recno, 
                        'lineno_' => $value->lineno_, 
                        'reqdept' => $value->reqdept, 
                        'ivreqno' => $value->ivreqno,
                        'reqlineno' => $value->lineno_,
                        'itemcode' => $value->itemcode, 
                        'uomcode' => $value->uomcode, 
                        'uomcoderecv' => $value->pouom, 
                        'txnqty' => $value->qtytxn, 
                        'qtyrequest' => $value->qtyrequest, 
                        'qtybalance' => $value->qtybalance, 
                        'netprice' => $value->netprice, 
                       // 'maxqty' => $value->maxqty, 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now(), 
                        'recstatus' => 'ACTIVE', 
                    ]);
        
                    
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

    public function need_upd_ivreqdt($idno){
        $ivtmphd = DB::table('material.ivtmphd')
                    ->where('idno','=',$idno)
                    ->first();

        if(!empty($ivtmphd->srcdocno)){

            $status = 'COMPLETED';

            $ivreqhd = DB::table('material.ivreqhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$ivtmphd->srcdocno);

            if($ivreqhd->exists()){
                $ivreqhd = $ivreqhd->first();
                $ivreqdt = DB::table('material.ivreqdt')
                            ->where('ivreqno','=',$ivreqhd->ivreqno)
                            ->where('compcode', '=', session('compcode'))
                            ->where('recstatus', '<>', 'DELETE');

                if($ivreqdt->exists()){
                    $ivreqdt = $ivreqdt->get();

                    foreach ($ivreqdt as $key => $value) {

                        $ivtmpdt = DB::table('material.ivtmpdt')
                            ->where('compcode', '=', session('compcode'))
                            ->where('recno','=',$ivtmphd->recno)
                            ->where('reqlineno','=',$value->lineno_)
                            ->first();

                        $qtytxn = $ivtmpdt->txnqty;
                        $qtybalance = $value->qtybalance;

                        $newbalance = intval($qtybalance) - intval($qtytxn);
                        if($newbalance > 0){
                            $status = 'PARTIAL';
                        }

                        DB::table('material.ivreqdt')
                            ->where('idno','=',$value->idno)
                            ->update([
                                'recstatus' => $status,
                                'qtybalance' => $newbalance
                            ]);
                    }
                    
                    DB::table('material.ivreqhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$ivtmphd->srcdocno)
                        ->update([
                            'recstatus' => $status
                        ]);

                }else{
                    return;
                }
            }else{
                return;
            }
        }
    }
}

