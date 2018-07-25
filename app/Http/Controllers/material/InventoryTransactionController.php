<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

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
            'trantype' => $request->trantype,
            'docno' => $request_no,
            'recno' => $recno,
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        foreach ($field as $key => $ivtmphd){
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function del(Request $request){

    }

    public function posted(Request $request){

        DB::beginTransaction();

        try {

            //1. transfer from ivtmphd to ivtxnhd
            $ivtmphd = DB::table('material.ivtmphd')
                        ->where('idno','=',$request->idno)
                        ->first();

            $table = DB::table("material.IvTxnHd");
                $table->insert([
                    'AddDate'  => $ivtmphd->adddate,
                    'AddUser'  => $ivtmphd->AddUser,
                    'Amount'   => $ivtmphd->amount,
                    'CompCode' => $ivtmphd->CompCode,
                    'DateActRet'   => $ivtmphd->DateActRet,
                    'DateSupRet'   => $ivtmphd->DateSupRet,
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

            //2. transfer from ivtmpdt to ivtxndt
            $ivtmpdt_obj = DB::table('material.ivtmpdt')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$ivtmphd->recno)
                    ->where('delorddt.recstatus','!=','DELETE')
                    ->get();

            foreach ($ivtmpdt_obj as $value) {

                DB::table('material.ivtxndt')
                    ->insert([
                        'compcode' => $value->compcode, 
                        'recno' => $value->recno, 
                        'lineno_' => $value->lineno_, 
                        'itemcode' => $value->itemcode, 
                        'uomcode' => $value->uomcode, 
                        'txnqty' => $value->txnqty, 
                        'netprice' => $value->netprice, 
                        'adduser' => $value->adduser, 
                        'adddate' => $value->adddate, 
                        'upduser' => $value->upduser, 
                        'upddate' => $value->upddate, 
                        'productcat' => $productcat, 
                        'draccno' => $value->draccno, 
                        'drccode' => $value->drccode, 
                        'craccno' => $value->craccno, 
                        'crccode' => $value->crccode, 
                        'expdate' => $value->expdate, 
                        'qtyonhand' => $value->qtyonhand,  
                        'batchno' => $value->batchno, 
                        'amount' => $value->amount, 
                    ]);

            //3. posting stockloc OUT
                //1. amik stockloc
                $stockloc_obj = DB::table('material.StockLoc')
                    ->where('StockLoc.CompCode','=',session('compcode'))
                    ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
                    ->where('StockLoc.ItemCode','=',$value->itemcode)
                    ->where('StockLoc.Year','=', $this->toYear($ivtmphd->trandate))
                    ->where('StockLoc.UomCode','=',$value->uomcodetrdept);

                $stockloc_first = $stockloc_obj->first();

                //2.kalu ada stockloc, update 
                if(count($stockloc_first)){

                //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                    $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
                    $month = $this->toMonth($ivtmphd->trandate);
                    $QtyOnHand = $stockloc_obj->qtyonhand - $value->txnqty; 
                    $NetMvQty = $stockloc_arr['netmvqty'.$month] - $value->txnqty;
                    $NetMvVal = $stockloc_arr['netmvval'.$month] - ($value->netprice * $value->txnqty);

                    $stockloc_obj
                        ->update([
                            'QtyOnHand' => $QtyOnHand,
                            'NetMvQty'.$month => $NetMvQty, 
                            'NetMvVal'.$month => $NetMvVal
                        ]);

                //4. tolak expdate
                    $expdate_obj = DB::table('material.stockexp')
                        ->where('expdate','<=',$value->expdate)
                        ->orderBy('expdate', 'asc')
                        ->get();

                    $txnqty_ = $value->txnqty;
                    foreach ($expdate_obj as $value) {
                        $balqty = $value->balqty;
                        if($txnqty_-$balqty>0){
                            $txnqty_ = $txnqty_-$balqty;
                            DB::table('material.stockexp')
                                ->where('id','=',$value->id)
                                ->update([
                                    'balqty' => '0'
                                ]);
                        }else{
                            $balqty = $balqty-$txnqty_;
                            DB::table('material.stockexp')
                                ->where('id','=',$value->id)
                                ->update([
                                    'balqty' => $balqty
                                ]);
                            break;
                        }
                    }

                }else{
                    //ni utk kalu xde stockloc
                    throw new \Exception("stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".$this->toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcodetrdept);
                }

            //4. posting stockloc IN
                //1. amik convfactor
                $convfactor_obj = DB::table('material.uom')
                    ->select('convfactor')
                    ->where('uomcode','=',$value->uomcoderecv)
                    ->where('compcode','=',session('compcode'))
                    ->first();
                $convfactor_uomcoderecv = $convfactor_obj->convfactor;

                $convfactor_obj = DB::table('material.uom')
                    ->select('convfactor')
                    ->where('uomcode','=',$value->uomcodetrdept)
                    ->where('compcode','=',session('compcode'))
                    ->first();
                $convfactor_uomcodetrdept = $convfactor_obj->convfactor;

                //2. tukar txnqty dgn netprice berdasarkan convfactor
                $txnqty = $value->txnqty * ($convfactor_uomcodetrdept / $convfactor_uomcoderecv);
                $netprice = $value->netprice * ($convfactor_uomcoderecv / $convfactor_uomcodetrdept);

                //3. amik stockloc
                $stockloc_obj = DB::table('material.StockLoc')
                    ->where('StockLoc.CompCode','=',session('compcode'))
                    ->where('StockLoc.DeptCode','=',$ivtmphd->sndrcv)
                    ->where('StockLoc.ItemCode','=',$value->itemcode)
                    ->where('StockLoc.Year','=', $this->toYear($ivtmphd->trandate))
                    ->where('StockLoc.UomCode','=',$value->uomcoderecv);

                $stockloc_first = $stockloc_obj->first();

                //4.kalu ada stockloc, update 
                if(count($stockloc_first)){

                //5. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                    $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
                    $month = $this->toMonth($ivtmphd->trandate);
                    $QtyOnHand = $stockloc_obj->qtyonhand + $txnqty; 
                    $NetMvQty = $stockloc_arr['netmvqty'.$month] + $txnqty;
                    $NetMvVal = $stockloc_arr['netmvval'.$month] + ($netprice * $txnqty);

                    $stockloc_obj
                        ->update([
                            'QtyOnHand' => $QtyOnHand,
                            'NetMvQty'.$month => $NetMvQty, 
                            'NetMvVal'.$month => $NetMvVal
                        ]);

                //6. tolak expdate
                    $expdate_obj = DB::table('material.stockexp')
                        ->where('Year','>',$this->toYear($value->trandate))
                        ->where('DeptCode','>',$ivtmphd->sndrcv)
                        ->where('ItemCode','>',$value->itemcode)
                        ->where('UomCode','>',$value->uomcoderecv)
                        ->where('BatchNo','>',$value->BatchNo)
                        ->where('expdate','<=',$value->ExpDate)
                        ->orderBy('expdate', 'asc');

                    $expdate_first = $expdate_obj->first();
                    $balqty_new = $expdate_first->balqty + $txnqty;

                    $expdate_obj->update([
                        'balqty' => $balqty_new
                    ]);

                }else{ 
                    //ni utk kalu xde stockloc
                    throw new \Exception("stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->sndrcv." | year: ".$this->toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcoderecv);
                }
            }

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

