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

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $request_no = $this->request_no($request->trantype, $request->txndept);
        $recno = $this->recno('IV','IT');

        DB::beginTransaction();

        $table = DB::table("material.ivtmphd");

        $array_insert = [
            'source' => 'IV',
            'trantype' => $request->trantype,
            'docno' => $request_no,
            'recno' => $recno,
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value){
            $array_insert[$value] = $request[$request->field[$key]];
        }

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

            return response('Error'.$e, 500);
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

            return response('Error'.$e, 500);
        }
    }

    public function posted(Request $request){

        DB::beginTransaction();

        try {

            //-- 1. transfer from ivtmphd to ivtxnhd --//
            $ivtmphd = DB::table('material.ivtmphd')
                        ->where('idno','=',$request->idno)
                        ->first();

            DB::table("material.IvTxnHd")
                ->insert([
                    'AddDate'  => $ivtmphd->adddate,
                    'AddUser'  => $ivtmphd->adduser,
                    'Amount'   => $ivtmphd->amount,
                    'CompCode' => $ivtmphd->compcode,
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
                    ]);

            //-- 4. posting stockloc OUT --//

                $trantype_obj = DB::table('material.ivtxntype')
                    ->where('ivtxntype.compcode','=',session('compcode'))
                    ->where('ivtxntype.trantype','=',$ivtmphd->trantype)
                    ->first();

                if($trantype_obj->isstype == 'Transfer'){
                    $retval = invtran_util::posting_for_transfer($value,$ivtmphd);
                }else if($trantype_obj->isstype == 'Adjustment' || $trantype_obj->isstype == 'Loan' || $trantype_obj->isstype == 'Issue'){
                    switch ($trantype_obj->crdbfl) {
                        case 'in':
                        case 'In':
                            invtran_util::posting_for_adjustment_in($value,$ivtmphd,$trantype_obj->isstype);
                            break;
                        case 'out':
                        case 'Out':
                            invtran_util::posting_for_adjustment_out($value,$ivtmphd,$trantype_obj->isstype);
                            break;
                        default:
                            # code...
                            break;
                    }
                }

                //--- 7. posting GL ---//

                //amik yearperiod dari delordhd
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
                            'recstatus' => 'A'
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
                            'recstatus' => 'A'
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
                            'recstatus' => 'A'
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
                            'recstatus' => 'A'
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
            

            /*$queries = DB::getQueryLog();
            dump($queries);*/


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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
}

