<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\util\invtran_util;
use PDF;
use App\Exports\tui_tuo_report_Export;
use Maatwebsite\Excel\Facades\Excel;

class InventoryTransactionController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request){
        $storedept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('storedept',1)
                        ->get();
   
        return view('material.inventoryTransaction.inventoryTransaction',compact('storedept'));
    }

    public function tui_tuo_report_show(Request $request){
   
        return view('material.inventoryTransaction.tui_tuo_report');
    }

    public function form(Request $request){   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'prepared':
                return $this->prepared($request);
            case 'support':
                return $this->support($request);
            case 'verify':
                return $this->verify($request);
            case 'reject':
                return $this->reject($request);
            case 'approved':
                return $this->posted($request);
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

    public function table(Request $request){
        switch($request->action){
            case 'tui_tuo_report':
                return $this->tui_tuo_report($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        //invTran_save

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
                $totalAmount = $this->save_dt_from_othr_ivreq($request->referral,$recno,$request_no);

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

    public function prepared(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno){
                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $ivtmphd = DB::table('material.ivtmphd')
                            ->where('idno','=',$idno)
                            ->first();

                if($ivtmphd->recstatus != 'OPEN'){
                    continue;
                }

                DB::table("material.queueiv")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $ivtmphd->recno,
                        'AuthorisedID' => session('username'),
                        // 'deptcode' => '',
                        'recstatus' => 'PREPARED',
                        'trantype' => 'SUPPORT',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // 3. update status to posted
                DB::table('material.ivtmphd')
                    ->where('idno','=',$idno)
                    ->update([
                        'requestby' => session('username'),
                        'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'PREPARED'
                    ]);

                DB::table("material.ivtmpdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'recstatus' => 'PREPARED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e->getMessage(), 500);
        }
    }

    public function support(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno){
                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $ivtmphd = DB::table('material.ivtmphd')
                            ->where('idno','=',$idno)
                            ->first();

                if($ivtmphd->recstatus != 'PREPARED'){
                    continue;
                }

                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','IV')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','SUPPORT')
                    // ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$ivtmphd->amount);

                if(!$authorise->exists()){
                    throw new \Exception("The user doesnt have authority",500);
                }

                DB::table('material.queueiv')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'AuthorisedID' => session('username'),
                        'recstatus' => 'SUPPORT',
                        'trantype' => 'VERIFIED'
                    ]);

                // 3. update status to posted
                DB::table('material.ivtmphd')
                    ->where('idno','=',$idno)
                    ->update([
                        'requestby' => session('username'),
                        'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'SUPPORT'
                    ]);

                DB::table("material.ivtmpdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'recstatus' => 'SUPPORT',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e->getMessage(), 500);
        }
    }

    public function verify(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno){
                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $ivtmphd = DB::table('material.ivtmphd')
                            ->where('idno','=',$idno)
                            ->first();

                if($ivtmphd->recstatus != 'SUPPORT'){
                    continue;
                }

                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','IV')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','VERIFIED')
                    // ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$ivtmphd->amount);

                if(!$authorise->exists()){
                    throw new \Exception("The user doesnt have authority",500);
                }

                DB::table('material.queueiv')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'AuthorisedID' => session('username'),
                        'recstatus' => 'VERIFIED',
                        'trantype' => 'APPROVED'
                    ]);

                // 3. update status to posted
                DB::table('material.ivtmphd')
                    ->where('idno','=',$idno)
                    ->update([
                        'requestby' => session('username'),
                        'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'VERIFIED'
                    ]);

                DB::table("material.ivtmpdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'recstatus' => 'SUPPORT',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e->getMessage(), 500);
        }
    }

    public function reject(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno){
                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $ivtmphd = DB::table('material.ivtmphd')
                            ->where('idno','=',$idno)
                            ->first();

                if(!in_array($ivtmphd->recstatus, ['PREPARED','SUPPORT','VERIFIED'])){
                    continue;
                }

                $ivtmphd_update = [
                    'recstatus' => 'CANCELLED',
                    'cancelby' => session('username'),
                    'canceldate' => Carbon::now("Asia/Kuala_Lumpur"),
                ];

                if(!empty($request->remarks)){
                    $ivtmphd_update['cancelled_remark'] = $request->remarks;
                }

                // 3. update status to posted
                DB::table('material.ivtmphd')
                    ->where('idno','=',$idno)
                    ->update($ivtmphd_update);

                DB::table("material.ivtmpdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
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

            foreach ($request->idno_array as $idno){
                $ivtmphd = DB::table('material.ivtmphd')
                            ->where('idno','=',$idno)
                            ->first();

                if($ivtmphd->recstatus != 'CANCELLED'){
                    continue;
                }

                $array_update= [
                    'recstatus' => 'OPEN',
                    'requestby' => null,
                    'requestdate' => null,
                    'supportby' => null,
                    'supportdate' => null,
                    'support_remark' => null,
                    'verifiedby' => null,
                    'verifieddate' => null,
                    'verified_remark' => null,
                    'approvedby' => null,
                    'approveddate' => null,
                    'approved_remark' => null,
                ];

                DB::table('material.ivtmphd')
                    ->where('idno','=',$idno)
                    ->update($array_update);

                DB::table("material.ivtmpdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'recstatus' => 'OPEN',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table('material.queueiv')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->delete();

            }

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

                if(!in_array($ivtmphd->recstatus, ['OPEN','VERIFIED'])){
                    continue;
                }

                if($ivtmphd->amount <= 0){
                    throw new \Exception("Header Amount is 0.00", 500);
                }

                DB::table('material.queueiv')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$ivtmphd->recno)
                    ->update([
                        'AuthorisedID' => session('username'),
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE'
                    ]);

                $ivtmpdt_obj = DB::table('material.ivtmpdt')
                        ->where('ivtmpdt.compcode','=',session('compcode'))
                        ->where('ivtmpdt.recno','=',$ivtmphd->recno)
                        ->where('ivtmpdt.recstatus','!=','DELETE')
                        ->where('ivtmpdt.txnqty','>',0);

                if(!$ivtmpdt_obj->exists()){
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
                    continue;
                }


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
                    if($value->txnqty == 0){
                        continue;
                    }

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
                            'unit'    => session('unit'),
                        ]);


                    //-- 4. posting stockloc OUT --//

                    if($ivtmphd->trantype == 'TUO'){
                        invtran_util::posting_TUO($value,$ivtmphd);
                    }else if($ivtmphd->trantype == 'TUI'){
                        invtran_util::posting_TUI($value,$ivtmphd);
                    }else{
                        $trantype_obj = DB::table('material.ivtxntype')
                            ->where('ivtxntype.compcode','=',session('compcode'))
                            ->where('ivtxntype.trantype','=',$ivtmphd->trantype)
                            ->first();

                        if(strtoupper($trantype_obj->isstype) == 'TRANSFER'){
                            $retval = invtran_util::posting_for_transfer($value,$ivtmphd);
                        
                        }else if(strtoupper($trantype_obj->isstype) == 'ADJUSTMENT' || strtoupper($trantype_obj->isstype) == 'LOAN' || strtoupper($trantype_obj->isstype) == 'ISSUE'|| strtoupper($trantype_obj->isstype) == 'WRITE-OFF'){
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

    public function save_dt_from_othr_ivreq($refer_recno,$recno,$request_no){

        $ivtmphd = DB::table('material.ivtmphd')
                        ->where('recno','=',$recno)
                        ->where('compcode', '=', session('compcode'))
                        ->first();
        $txndept = $ivtmphd->txndept;
        $sndrcv = $ivtmphd->sndrcv;
        $year = $this->toYear($ivtmphd->trandate);

        $ivreq_dt = DB::table('material.ivreqdt')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        foreach ($ivreq_dt as $key => $value) {
            ///insert detail from existing inventory request

            $stockloc_txndept = DB::table('material.stockloc')
                            ->where('unit', '=', session('unit'))
                            ->where('compcode', '=', session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('uomcode',$value->uomcode)
                            ->where('year',$year)
                            ->where('deptcode',$txndept)
                            ->first();

            $qtyonhand_sndrcv = 0;
            if(!in_array($ivtmphd->trantype, ['TUO','TUI'])){
                $stockloc_sndrcv = DB::table('material.stockloc')
                            ->where('unit', '=', session('unit'))
                            ->where('compcode', '=', session('compcode'))
                            ->where('itemcode',$value->itemcode)
                            ->where('uomcode',$value->uomcode)
                            ->where('year',$year)
                            ->where('deptcode',$sndrcv);

                if(!$stockloc_sndrcv->exists()){
                    throw new \Exception("Stockloc doesnt exists for item $value->itemcode - $value->uomcode - $year - $sndrcv", 500);
                }
                $stockloc_sndrcv = $stockloc_sndrcv->first();
                $qtyonhand_sndrcv = $stockloc_sndrcv->qtyonhand;
            }

            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->itemcode)
                ->where('cp.uom', '=', $value->uomcode)
                ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
                ->orderBy('cp.effdate','desc');

            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();
                $netprice = $chgprice_obj->costprice;
            }else{
                $netprice = $value->netprice;
            }

            $table = DB::table("material.ivtmpdt");
            $table->insert([
                'compcode' => session('compcode'), 
                'recno' => $recno, 
                'lineno_' => $value->lineno_, 
                'ivreqno' => $refer_recno,
                'reqdept' => $value->reqdept, 
                'ivreqno' => $value->ivreqno,
                'reqlineno' => $value->lineno_,
                'itemcode' => $value->itemcode, 
                'uomcode' => $value->uomcode, 
                'uomcoderecv' => $value->pouom, 
                'txnqty' => 0, 
                'qtyrequest' => $value->qtybalance,
                'netprice' => $netprice, 
                'amount' => 0,
                'recstatus' => 'OPEN',
                'unit' => session('unit'),
                'expdate' => $value->expdate,
                'batchno' => $value->batchno,
                'qtyonhand' => $stockloc_txndept->qtyonhand,
                'qtyonhandrecv'=> $qtyonhand_sndrcv,
               // 'maxqty' => $value->maxqty, 
                'adduser' => session('username'), 
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
            ]);

        }

        $totalAmount = DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

   
        ///4. then update to header
        DB::table('material.ivtmphd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->update([
                'amount' => $totalAmount
            ]);
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

                // if($ivreqhd->recstatus == 'COMPLETED'){
                //     throw new \Exception("Inventory Request document RECNO: ".$ivtmphd->srcdocno." already COMPLETED", 500);
                // }

                $ivreqdt = DB::table('material.ivreqdt')
                            ->where('recno','=',$ivtmphd->srcdocno)
                            ->where('compcode', '=', session('compcode'))
                            ->where('recstatus', '<>', 'DELETE');

                if($ivreqdt->exists()){
                    $ivreqdt = $ivreqdt->get();

                    foreach ($ivreqdt as $key => $value) {

                        $ivtmpdt = DB::table('material.ivtmpdt')
                            ->where('compcode', '=', session('compcode'))
                            ->where('recno','=',$ivtmphd->recno)
                            ->where('reqlineno','=',$value->lineno_);

                        if($ivtmpdt->exists()){
                            $ivtmpdt = $ivtmpdt->first();

                            $qtytxn = $ivtmpdt->txnqty;
                            $qtybalance = $value->qtybalance;

                            $newbalance = intval($qtybalance) - intval($qtytxn);
                            $newtxn = intval($value->qtytxn) + intval($qtytxn);
                            if($newbalance > 0){
                                $status = 'PARTIAL';
                            }else if($newbalance < 0){
                                // throw new \Exception("Inventory Request document RECNO: ".$ivtmphd->srcdocno." on lineno: ".$value->lineno_." exceed Qty balance: ".$qtybalance, 500);
                            }

                            DB::table('material.ivreqdt')
                                ->where('idno','=',$value->idno)
                                ->update([
                                    'recstatus' => $status,
                                    'qtybalance' => $newbalance,
                                    'qtytxn' => $newtxn
                                ]);
                        }
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

    public function showpdf(Request $request){
        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }
        
        $ivtmphd = DB::table('material.ivtmphd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->first();

        $ivtmpdt = DB::table('material.ivtmpdt AS ivdt')
            ->select('ivdt.compcode','ivdt.recno','ivdt.lineno_','ivh.trandate','ivdt.itemcode','p.description', 'ivdt.qtyonhand','ivdt.uomcode', 'ivdt.qtyonhandrecv','ivdt.uomcoderecv','ivdt.txnqty','ivdt.qtyrequest','ivdt.netprice','ivdt.amount','ivdt.expdate','ivdt.batchno')
            ->leftJoin('material.productmaster as p', function($join) use ($request){
                        $join = $join->on('ivdt.itemcode', '=', 'p.itemcode')
                                ->where('p.compcode','=',session('compcode'));
                    })
            ->leftJoin('material.ivtmphd as ivh', function($join) use ($request){
                        $join = $join->on('ivh.recno', '=', 'ivdt.recno')
                                ->where('ivh.compcode','=',session('compcode'));
                    })
            ->where('ivdt.recstatus','!=','DELETE')
            ->where('ivdt.compcode','=',session('compcode'))
            ->where('ivdt.recno','=',$recno)
            ->orderBy('ivdt.lineno_', 'ASC')
            ->get();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        $sndrcv = DB::table('sysdb.department')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$ivtmphd->sndrcv)
            ->first();

        $total_amt = DB::table('material.ivtmpdt')
            ->where('recstatus','!=','DELETE')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->sum('amount');

        // $total_tax = DB::table('material.ivtmpdt')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('recno','=',$recno)
        //     ->sum('amtslstax');
        
        // $total_discamt = DB::table('material.ivtmpdt')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('recno','=',$recno)
        //     ->sum('amtdisc');

        $totamount_expld = explode(".", (float)$ivtmphd->amount);

        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";

        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }

        $cc_acc = [];
        $cr_acc=[];
        $db_acc=[];
        if($ivtmphd->recstatus == 'POSTED'){
            //account
            foreach ($ivtmpdt as $value) {
                $gltran = DB::table('finance.gltran as gl')
                       ->where('gl.compcode',session('compcode'))
                       ->where('gl.auditno',$value->recno)
                       ->where('gl.lineno_',$value->lineno_)
                       ->where('gl.source',$ivtmphd->source)
                       ->where('gl.trantype',$ivtmphd->trantype);

                if(!$gltran->exists()){
                    continue;
                }
                $gltran = $gltran->first();

                $drkey = $gltran->drcostcode.'_'.$gltran->dracc;
                $crkey = $gltran->crcostcode.'_'.$gltran->cracc;

                if(!array_key_exists($drkey,$cc_acc)){
                    $cc_acc[$drkey] = floatval($gltran->amount);
                }else{
                    $curamt = floatval($cc_acc[$drkey]);
                    $cc_acc[$drkey] = $curamt+floatval($gltran->amount);
                }
                if(!array_key_exists($crkey,$cc_acc)){
                    $cc_acc[$crkey] = -floatval($gltran->amount);
                }else{
                    $curamt = floatval($cc_acc[$crkey]);
                    $cc_acc[$crkey] = $curamt-floatval($gltran->amount);
                }
            }

            foreach ($cc_acc as $key => $value) {
                $cc = explode("_",$key)[0];
                $acc = explode("_",$key)[1];
                $cc_desc = '';
                $acc_desc = '';

                $costcenter = DB::table('finance.costcenter')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$cc);

                $glmasref = DB::table('finance.glmasref')
                            ->where('compcode',session('compcode'))
                            ->where('glaccno',$acc);

                if($costcenter->exists()){
                    $cc_desc = $costcenter->first()->description;
                }

                if($glmasref->exists()){
                    $acc_desc = $glmasref->first()->description;
                }

                if(floatval($value) > 0){
                    array_push($db_acc,[$cc,$cc_desc,$acc,$acc_desc,floatval($value),0]);
                }else{
                    array_push($cr_acc,[$cc,$cc_desc,$acc,$acc_desc,0,-floatval($value)]);
                }
            }

        }
        

        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm."";

        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('material.inventoryTransaction.inventoryTransaction_pdfmake',compact('ivtmphd','ivtmpdt', 'company','total_amt','cr_acc','db_acc','sndrcv'));        
    }

    public function tui_tuo_report(Request $request){
        return Excel::download(new tui_tuo_report_Export($request->datefr,$request->dateto), 'Posted_tui_tuo.xlsx');
    }
}

