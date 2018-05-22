<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class DeliveryOrderController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.deliveryOrder.deliveryOrder');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        // return $this->request_no('GRN','2FL');
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

        $request_no = $this->request_no('GRN', $request->delordhd_deldept);
        $recno = $this->recno('PUR','DO');

        DB::beginTransaction();

        $table = DB::table("material.delordhd");

        $array_insert = [
            'trantype' => 'GRN', 
            'docno' => $request_no,
            'recno' => $recno,
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            if(!empty($request->referral)){
                ////ni kalu dia amik dari po
                ////amik detail dari po sana, save dkt do detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_po($request->referral,$recno);

                $srcdocno = $request->delordhd_srcdocno;
                $delordno = $request->delordhd_delordno;

                ////dekat po header sana, save balik delordno dkt situ
                DB::table('material.purordhd')
                ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                ->update(['delordno' => $delordno]);
            }

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

    public function edit(Request $request){
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $srcdocno = DB::table('material.delordhd')
                    ->select('srcdocno')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->delordhd_recno)->first();
        
        if($srcdocno->srcdocno == $request->delordhd_srcdocno){
            // ni edit macam biasa, nothing special
            DB::beginTransaction();

            $table = DB::table("material.delordhd");

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
                $table = $table->where('idno','=',$request->delordhd_idno);
                $table->update($array_update);

                $responce = new stdClass();
                $responce->totalAmount = $request->delordhd_totamount;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response('Error'.$e, 500);
            }
        }else{
            DB::beginTransaction();

            try{
                // ni edit kalu copy utk do dari existing po
                //1. update po.delordno lama jadi 0, kalu do yang dulu pon copy existing po 
                if($srcdocno->srcdocno != '0'){
                    DB::table('material.purordhd')
                    ->where('purordno','=', $srcdocno->srcdocno)->where('compcode','=',session('compcode'))
                    ->update(['delordno' => '0']);
                }

                //2. Delete detail from delorddt
                DB::table('material.delorddt')->where('recno','=',$request->delordhd_recno);

                //3. Update srcdocno_delordhd
                $table = DB::table("material.delordhd");

                $array_update = [
                    'compcode' => session('compcode'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                foreach ($field as $key => $value) {
                    $array_update[$value] = $request[$request->field[$key]];
                }

                $table = $table->where('idno','=',$request->delordhd_idno);
                $table->update($array_update);

                $totalAmount = $request->delordhd_totamount;
                //4. Update delorddt
                if(!empty($request->referral)){
                    $totalAmount = $this->save_dt_from_othr_po($request->referral,$request->delordhd_recno);

                    $srcdocno = $request->delordhd_srcdocno;
                    $delordno = $request->delordhd_delordno;

                    ////dekat po header sana, save balik delordno dkt situ
                    DB::table('material.purordhd')
                        ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                        ->update(['delordno' => $delordno]);
                }

                $responce = new stdClass();
                $responce->totalAmount = $totalAmount;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response('Error'.$e, 500);
            }
        }

    }

    public function del(Request $request){

    }

    public function posted(Request $request){
        DB::beginTransaction();

        try{

            //--- 1. copy delordhd masuk dalam ivtxnhd ---//

                //1. amik dari delordhd
            $delordhd_obj = DB::table('material.delordhd')
                ->where('recno', '=', $request->recno)
                ->where('compcode', '=' ,session('compcode'))
                ->first();

                //2. pastu letak dkt ivtxnhd
            DB::table('material.ivtxnhd')
                ->insert([
                    'compcode'=>$delordhd_obj->compcode, 
                    'recno'=>$delordhd_obj->recno, 
                    'reference'=>$delordhd_obj->delordno, 
                    'source'=>'IV', 
                    'txndept'=>$delordhd_obj->deldept, 
                    'trantype'=>$delordhd_obj->trantype, 
                    'docno'=>$delordhd_obj->docno, 
                    'srcdocno'=>$delordhd_obj->srcdocno, 
                    'sndrcv'=>$delordhd_obj->suppcode, 
                    'sndrcvtype'=>'Supplier', 
                    'trandate'=>$delordhd_obj->trandate, 
                    'trantime'=>$delordhd_obj->trantime, 
                    'datesupret'=>$delordhd_obj->deliverydate, 
                    'respersonid'=>$delordhd_obj->checkpersonid, 
                    'recstatus'=>$delordhd_obj->recstatus, 
                    'adduser'=>$delordhd_obj->adduser, 
                    'adddate'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'remarks'=>$delordhd_obj->remarks
                ]);

            //--- 2. loop delorddt untuk masuk dalam ivtxndt ---//

                //1.amik productcat dari table product
            $productcat_obj = DB::table('material.delorddt')
                ->select('product.productcat')
                ->join('material.product', function($join) use ($request){
                    $join = $join->on('delorddt.itemcode', '=', 'product.itemcode');
                    $join = $join->on('delorddt.uomcode', '=', 'product.uomcode');
                })
                ->where('delorddt.compcode','=',session('compcode'))
                ->where('product.groupcode','=','Stock')
                ->where('delorddt.recno','=',$request->recno)
                ->first();
            $productcat = $productcat_obj->productcat;

            $delorddt_obj = DB::table('material.delorddt')
                ->where('delorddt.compcode','=',session('compcode'))
                ->where('delorddt.recno','=',$request->recno)
                ->where('delorddt.recstatus','!=','DELETE')
                ->get();

                //2. start looping untuk delorddt
            foreach ($delorddt_obj as $value) {

                //3. dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
                $convfactorPOUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.pouom','=','uom.uomcode')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$request->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorPOUOM = $convfactorPOUOM_obj->convfactor;

                $convfactorUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.uomcode','=','uom.uomcode')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$request->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorUOM = $convfactorUOM_obj->convfactor;

                $txnqty = $value->qtydelivered * ($convfactorPOUOM / $convfactorUOM);
                $netprice = $value->netunitprice * ($convfactorUOM / $convfactorPOUOM);

                //4. start insert dalam ivtxndt
                DB::table('material.ivtxndt')
                    ->insert([
                        'compcode' => $value->compcode, 
                        'recno' => $value->recno, 
                        'lineno_' => $value->lineno_, 
                        'itemcode' => $value->itemcode, 
                        'uomcode' => $value->uomcode, 
                        'txnqty' => $txnqty, 
                        'netprice' => $netprice, 
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
                        'remarks' => $value->remarks, 
                        'qtyonhand' => 0, 
                        'batchno' => $value->batchno, 
                        'amount' => $value->amount, 
                        'trandate' => $value->trandate, 
                        'deptcode' => $value->deldept, 
                        'gstamount' => $value->amtslstax, 
                        'totamount' => $value->totamount
                    ]);

            //--- 3. posting stockloc ---///
                //1. amik stockloc
                $stockloc_obj = DB::table('material.StockLoc')
                    ->where('StockLoc.CompCode','=',session('compcode'))
                    ->where('StockLoc.DeptCode','=',$value->deldept)
                    ->where('StockLoc.ItemCode','=',$value->itemcode)
                    ->where('StockLoc.Year','=', $this->toYear($value->trandate))
                    ->where('StockLoc.UomCode','=',$value->uomcode)
                    ->first();

                //2.kalu ada stockloc, update 
                if(count($stockloc_obj)){

                //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                    $stockloc_arr = (array)$stockloc_obj;
                    $month = $this->toMonth($value->trandate);
                    $QtyOnHand = $stockloc_obj->qtyonhand + $txnqty; 
                    $NetMvQty = $stockloc_arr['netmvqty'.$month] + $txnqty;
                    $NetMvVal = $stockloc_arr['netmvval'.$month] + ($netprice * $txnqty);

                    DB::table('material.StockLoc')
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$value->deldept)
                        ->where('StockLoc.ItemCode','=',$value->itemcode)
                        ->where('StockLoc.Year','=', $this->toYear($value->trandate))
                        ->where('StockLoc.UomCode','=',$value->uomcode)
                        ->update([
                            'QtyOnHand' => $QtyOnHand,
                            'NetMvQty'.$month => $NetMvQty, 
                            'NetMvVal'.$month => $NetMvVal
                        ]);

                }else{
                //3.kalu xde stockloc, create stockloc baru

                }

            //--- 4. posting stock enquiry ---//
                //1. amik Stock Expiry
                $stockexp_obj = DB::table('material.stockexp')
                    ->where('stockexp.compcode','=',session('compcode'))
                    ->where('stockexp.deptcode','=',$value->deldept)
                    ->where('stockexp.itemcode','=',$value->itemcode)
                    ->where('stockexp.expdate','=',$value->expdate)
                    ->where('stockexp.year','=', $this->toYear($value->trandate))
                    ->where('stockexp.uomcode','=',$value->uomcode)
                    ->where('stockexp.batchno','=',$value->batchno)
                    ->where('stockexp.lasttt','=','GRN')
                    ->first();

                //2.kalu ada Stock Expiry, update

                if(count($stockexp_obj)){
                    $BalQty = $stockexp_obj->balqty + $txnqty;

                    DB::table('material.stockexp')
                        ->where('stockexp.compcode','=',session('compcode'))
                        ->where('stockexp.deptcode','=',$value->deldept)
                        ->where('stockexp.itemcode','=',$value->itemcode)
                        ->where('stockexp.expdate','=',$value->expdate)
                        ->where('stockexp.year','=', $this->toYear($value->trandate))
                        ->where('stockexp.uomcode','=',$value->uomcode)
                        ->where('stockexp.batchno','=',$value->batchno)
                        ->where('stockexp.lasttt','=','GRN')
                        ->update([
                            'balqty' => $BalQty
                        ]);

                }else{
                //3.kalu xde Stock Expiry, buat baru
                    $BalQty = $txnqty;

                    DB::table('material.stockexp')
                        ->insert([
                            'compcode' => session('compcode'), 
                            'deptcode' => $value->deldept, 
                            'itemcode' => $value->itemcode, 
                            'uomcode' => $value->uomcode, 
                            'expdate' => $value->expdate, 
                            'batchno' => $value->batchno, 
                            'balqty' => $BalQty, 
                            'adduser' => $value->adduser, 
                            'adddate' => $value->adddate, 
                            'upduser' => $value->upduser, 
                            'upddate' => $value->upddate, 
                            'lasttt' => 'GRN', 
                            'year' => $this->toYear($value->trandate)
                        ]);
                }

                 //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//
                $product_obj = DB::table('material.product')
                    ->where('product.compcode','=',session('compcode'))
                    ->where('product.itemcode','=',$value->itemcode)
                    ->where('product.uomcode','=',$value->uomcode)
                    ->first();

                if(count($product_obj)){ // kalu jumpa
                    $month = $this->toMonth($value->trandate);
                    $OldQtyOnHand = $product_obj->qtyonhand;
                    $currprice = $netprice;
                    $Oldavgcost = $product_obj->avgcost;
                    $OldAmount = $OldQtyOnHand * $Oldavgcost;
                    $NewAmount = $netprice * $txnqty;

                    $newqtyonhand = $OldQtyOnHand + $txnqty;
                    $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);

                    // update qtyonhand, avgcost, currprice
                    $product_obj = DB::table('material.product')
                        ->where('product.compcode','=',session('compcode'))
                        ->where('product.itemcode','=',$value->itemcode)
                        ->where('product.uomcode','=',$value->uomcode)
                        ->update([
                            'qtyonhand' => $newqtyonhand ,
                            'avgcost' => $newAvgCost,
                            'currprice' => $currprice
                        ]);

                }

                //--- 6. posting GL ---//

                //amik ivtxnhd
                $ivtxnhd_obj = DB::table('material.ivtxnhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->first();

                //amik yearperiod dari delordhd
                $yearperiod = $this->getyearperiod($ivtxnhd_obj->trandate);

                //amik department,category dgn sysparam pvalue1 dgn pvalue2
                //utk debit costcode
                $row_dept = DB::table('sysdb.department')
                    ->select('costcode')
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$ivtxnhd_obj->txndept)
                    ->first();
                //utk debit accountcode
                $row_cat = DB::table('material.category')
                    ->select('stockacct')
                    ->where('compcode','=',session('compcode'))
                    ->where('catcode','=',$productcat)
                    ->first();
                //utk credit costcode dgn accountocde
                $row_sysparam = DB::table('sysdb.sysparam')
                    ->select('pvalue1','pvalue2')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','ACC')
                    ->first();

                //1. buat gltran
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => $value->compcode,
                        'adduser' => $value->adduser,
                        'adddate' => $value->adddate,
                        'auditno' => $value->recno,
                        'lineno_' => $value->lineno_,
                        'source' => 'IV',
                        'trantype' => 'GRN',
                        'reference' => $ivtxnhd_obj->txndept .' '. $ivtxnhd_obj->docno,
                        'description' => $ivtxnhd_obj->sndrcv,
                        'postdate' => $ivtxnhd_obj->trandate,
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $row_dept->costcode,
                        'dracc' => $row_cat->stockacct,
                        'crcostcode' => $row_sysparam->pvalue1,
                        'cracc' => $row_sysparam->pvalue2,
                        'amount' => $value->amount,
                        'idno' => $value->itemcode
                    ]);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                if($this->isGltranExist($row_dept->costcode,$row_cat->stockacct,$yearperiod->year,$yearperiod->period)){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$row_dept->costcode)
                        ->where('glaccount','=',$row_cat->stockacct)
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
                            'costcode' => $row_dept->costcode,
                            'glaccount' => $row_cat->stockacct,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'A'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                if($this->isGltranExist($row_sysparam->pvalue1,$row_sysparam->pvalue2,$yearperiod->year,$yearperiod->period)){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$row_sysparam->pvalue1)
                        ->where('glaccount','=',$row_sysparam->pvalue2)
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
                            'costcode' => $row_sysparam->pvalue1,
                            'glaccount' => $row_sysparam->pvalue2,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => -$value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'A'
                        ]);
                }


                //--- 7. posting GL gst punya---//

                if($value->amtslstax > 0){
                    $queryACC = DB::table('sysdb.sysparam')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','ACC')
                        ->first();

                    //nak pilih debit costcode dgn acc berdasarkan supplier gstid
                    $querysupp = DB::table('material.supplier')
                        ->where('compcode','=',session('compcode'))
                        ->where('suppcode','=',$ivtxnhd_obj->sndrcv)
                        ->first();

                    //kalu xde guna GST-PL, kalu ada guna GST-BS
                    if($querysupp->GSTID == ''){
                        $queryGSTPL = DB::table('sysdb.sysparam')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','GST')
                            ->where('trantype','=','PL')
                            ->first();

                        $drcostcode_ = $queryGSTPL->pvalue1;
                        $dracc_ = $queryGSTPL->pvalue2;
                    }else{
                        $queryGSTBS = DB::table('sysdb.sysparam')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','GST')
                            ->where('trantype','=','BS')
                            ->first();

                        $drcostcode_ = $queryGSTBS->pvalue1;
                        $dracc_ = $queryGSTBS->pvalue2;
                    }

                    //1. buat gltran utk GST
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => $value->compcode,
                            'adduser' => $value->adduser,
                            'adddate' => $value->adddate,
                            'auditno' => $value->recno,
                            'lineno_' => $value->lineno_,
                            'source' => 'IV',
                            'trantype' => 'GST',
                            'reference' => $ivtxnhd_obj->txndept .' '. $ivtxnhd_obj->docno,
                            'description' => $ivtxnhd_obj->sndrcv,
                            'postdate' => $ivtxnhd_obj->trandate,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $drcostcode_,
                            'dracc' => $dracc_,
                            'crcostcode' => $queryACC->pvalue1,
                            'cracc' => $queryACC->pvalue2,
                            'amount' => $value->amtslstax,
                            'idno' => $value->itemcode
                        ]);

                    //2. check glmastdtl utk debit, kalu ada update kalu xde create
                    if($this->isGltranExist($drcostcode_,$dracc_,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$drcostcode_)
                            ->where('glaccount','=',$dracc_)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $value->amtslstax + $this->gltranAmount,
                                'recstatus' => 'A'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $drcostcode_,
                                'glaccount' => $dracc_,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $value->amtslstax,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'A'
                            ]);
                    }

                    //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                    if($this->isGltranExist($queryACC->pvalue1,$queryACC->pvalue2,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$queryACC->pvalue1)
                            ->where('glaccount','=',$queryACC->pvalue2)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount - $value->amtslstax,
                                'recstatus' => 'A'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $queryACC->pvalue1,
                                'glaccount' => $queryACC->pvalue2,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => -$value->amtslstax,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'A'
                            ]);
                    }
                }

            } // habis looping untuk delorddt

            //--- 8. change recstatus to posted ---//

            DB::table('material.delordhd')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'POSTED' 
                ]);

            DB::table('material.delorddt')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','!=','DELETE')
                ->update([
                    'recstatus' => 'POSTED' 
                ]);
            

            $queries = DB::getQueryLog();
            dd($queries);
            // DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function cancel(Request $request){
         DB::beginTransaction();

        try{

            //--- 1. loop delorddt untuk masuk dalam ivtxndt ---//

                //1.amik productcat dari table product
            $productcat_obj = DB::table('material.delorddt')
                ->select('product.productcat')
                ->join('material.product', function($join) use ($request){
                    $join = $join->on('delorddt.itemcode', '=', 'product.itemcode');
                    $join = $join->on('delorddt.uomcode', '=', 'product.uomcode');
                })
                ->where('delorddt.compcode','=',session('compcode'))
                ->where('product.groupcode','=','Stock')
                ->where('delorddt.recno','=',$request->recno)
                ->first();
            $productcat = $productcat_obj->productcat;

            $delorddt_obj = DB::table('material.delorddt')
                ->where('delorddt.compcode','=',session('compcode'))
                ->where('delorddt.recno','=',$request->recno)
                ->where('delorddt.recstatus','!=','DELETE')
                ->get();

                //2. start looping untuk delorddt
            foreach ($delorddt_obj as $value) {

                //3. dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
                $convfactorPOUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.pouom','=','uom.uomcode')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$request->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorPOUOM = $convfactorPOUOM_obj->convfactor;

                $convfactorUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.uomcode','=','uom.uomcode')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$request->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorUOM = $convfactorUOM_obj->convfactor;

                $txnqty = $value->qtydelivered * ($convfactorPOUOM / $convfactorUOM);
                $netprice = $value->netunitprice * ($convfactorUOM / $convfactorPOUOM);

                //4. update dalam ivtxnhd  ivtxndt
                DB::table('material.ivtxnhd')
                    ->where('ivtxnhd.compcode', '=', session('compcode'))
                    ->where('ivtxnhd.recno', '=', $request->recno)
                    ->update(['recstatus' => 'CANCELLED']);

                DB::table('material.ivtxndt')
                    ->where('ivtxndt.compcode', '=', session('compcode'))
                    ->where('ivtxndt.recno', '=', $request->recno)
                    ->update([
                        'netprice' => $netprice,
                        'recstatus' =>'CANCELLED'
                    ]);    


                //--- 3. cancel to stockloc ---///
                //1. amik stockloc
                $stockloc_obj = DB::table('material.StockLoc')
                    ->where('StockLoc.CompCode','=',session('compcode'))
                    ->where('StockLoc.DeptCode','=',$value->deldept)
                    ->where('StockLoc.ItemCode','=',$value->itemcode)
                    ->where('StockLoc.Year','=', $this->toYear($value->trandate))
                    ->where('StockLoc.UomCode','=',$value->uomcode)
                    ->first();

                //2.kalu ada stockloc, update 
                if(count($stockloc_obj)){

                //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                    $stockloc_arr = (array)$stockloc_obj;
                    $month = $this->toMonth($value->trandate);
                    $QtyOnHand = $stockloc_obj->qtyonhand - $txnqty; 
                    $NetMvQty = $stockloc_arr['netmvqty'.$month] - $txnqty;
                    $NetMvVal = $stockloc_arr['netmvval'.$month] - ($netprice * $txnqty);

                    DB::table('material.StockLoc')
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$value->deldept)
                        ->where('StockLoc.ItemCode','=',$value->itemcode)
                        ->where('StockLoc.Year','=', $this->toYear($value->trandate))
                        ->where('StockLoc.UomCode','=',$value->uomcode)
                        ->update([
                            'QtyOnHand' => $QtyOnHand,
                            'NetMvQty'.$month => $NetMvQty, 
                            'NetMvVal'.$month => $NetMvVal
                        ]);

                }else{
                //3.kalu xde stockloc, create stockloc baru

                }

                //--- 4. cancel to stock enquiry ---//
                //1. amik Stock Expiry
                $stockexp_obj = DB::table('material.stockexp')
                    ->where('stockexp.compcode','=',session('compcode'))
                    ->where('stockexp.deptcode','=',$value->deldept)
                    ->where('stockexp.itemcode','=',$value->itemcode)
                    ->where('stockexp.expdate','=',$value->expdate)
                    ->where('stockexp.year','=', $this->toYear($value->trandate))
                    ->where('stockexp.uomcode','=',$value->uomcode)
                    ->where('stockexp.batchno','=',$value->batchno)
                    ->where('stockexp.lasttt','=','GRN')
                    ->first();

                //2.kalu ada Stock Expiry, update

                if(count($stockexp_obj)){
                    $BalQty = $stockexp_obj->balqty - $txnqty;

                    DB::table('material.stockexp')
                        ->where('stockexp.compcode','=',session('compcode'))
                        ->where('stockexp.deptcode','=',$value->deldept)
                        ->where('stockexp.itemcode','=',$value->itemcode)
                        ->where('stockexp.expdate','=',$value->expdate)
                        ->where('stockexp.year','=', $this->toYear($value->trandate))
                        ->where('stockexp.uomcode','=',$value->uomcode)
                        ->where('stockexp.batchno','=',$value->batchno)
                        ->where('stockexp.lasttt','=','GRN')
                        ->update([
                            'balqty' => $BalQty
                        ]);

                }else{
               
                }

                 //--- 5. cancel to product -> update qtyonhand, avgcost, currprice ---//
                $product_obj = DB::table('material.product')
                    ->where('product.compcode','=',session('compcode'))
                    ->where('product.itemcode','=',$value->itemcode)
                    ->where('product.uomcode','=',$value->uomcode)
                    ->first();

                if(count($product_obj)){ // kalu jumpa
                    $month = $this->toMonth($value->trandate);
                    $OldQtyOnHand = $product_obj->qtyonhand;
                    $currprice = $netprice;
                    $Oldavgcost = $product_obj->avgcost;
                    $OldAmount = $OldQtyOnHand * $Oldavgcost;
                    $NewAmount = $netprice * $txnqty;

                    $newqtyonhand = $OldQtyOnHand - $txnqty;
                    $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand + $txnqty);

                    // update qtyonhand, avgcost, currprice
                    $product_obj = DB::table('material.product')
                        ->where('product.compcode','=',session('compcode'))
                        ->where('product.itemcode','=',$value->itemcode)
                        ->where('product.uomcode','=',$value->uomcode)
                        ->update([
                            'qtyonhand' => $newqtyonhand ,
                            'avgcost' => $newAvgCost,
                            'currprice' => $currprice
                        ]);

                }

                //--- 6. rollover GL ---//

                //amik ivtxnhd
                $ivtxnhd_obj = DB::table('material.ivtxnhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->first();

                //amik yearperiod dari delordhd
                $yearperiod = $this->getyearperiod($ivtxnhd_obj->trandate);

                //amik department,category dgn sysparam pvalue1 dgn pvalue2
                //utk debit costcode
                $row_dept = DB::table('sysdb.department')
                    ->select('costcode')
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$ivtxnhd_obj->txndept)
                    ->first();
                //utk debit accountcode
                $row_cat = DB::table('material.category')
                    ->select('stockacct')
                    ->where('compcode','=',session('compcode'))
                    ->where('catcode','=',$productcat)
                    ->first();
                //utk credit costcode dgn accountocde
                $row_sysparam = DB::table('sysdb.sysparam')
                    ->select('pvalue1','pvalue2')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','ACC')
                    ->first();

                //1. delete gltran
                DB::table('finance.gltran')
                    ->where('auditno','=', $request->recno)
                    ->where('lineno_','=', $request->lineno_)
                    ->where('source','=','IV')
                    ->where('trantype','=','GRN')
                    ->delete();

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                if($this->isGltranExist($row_dept->costcode,$row_cat->stockacct,$yearperiod->year,$yearperiod->period)){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$row_dept->costcode)
                        ->where('glaccount','=',$row_cat->stockacct)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $this->gltranAmount - $value->amount,
                            'recstatus' => 'A'
                        ]);
                }else{
                    
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                if($this->isGltranExist($row_sysparam->pvalue1,$row_sysparam->pvalue2,$yearperiod->year,$yearperiod->period)){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$row_sysparam->pvalue1)
                        ->where('glaccount','=',$row_sysparam->pvalue2)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $this->gltranAmount + $value->amount,
                            'recstatus' => 'A'
                        ]);
                }else{
                   
                }


                //--- 7. cancel GL gst punya---//

                if($value->amtslstax > 0){
                    $queryACC = DB::table('sysdb.sysparam')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','ACC')
                        ->first();

                    //nak pilih debit costcode dgn acc berdasarkan supplier gstid
                    $querysupp = DB::table('material.supplier')
                        ->where('compcode','=',session('compcode'))
                        ->where('suppcode','=',$ivtxnhd_obj->sndrcv)
                        ->first();

                    //kalu xde guna GST-PL, kalu ada guna GST-BS
                    if($querysupp->GSTID == ''){
                        $queryGSTPL = DB::table('sysdb.sysparam')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','GST')
                            ->where('trantype','=','PL')
                            ->first();

                        $drcostcode_ = $queryGSTPL->pvalue1;
                        $dracc_ = $queryGSTPL->pvalue2;
                    }else{
                        $queryGSTBS = DB::table('sysdb.sysparam')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','GST')
                            ->where('trantype','=','BS')
                            ->first();

                        $drcostcode_ = $queryGSTBS->pvalue1;
                        $dracc_ = $queryGSTBS->pvalue2;
                    }

                    //1. delete gltran utk GST
                        DB::table('finance.gltran')
                            ->where('auditno','=', $request->recno)
                            ->where('lineno_','=', $request->lineno_)
                            ->where('source','=','IV')
                            ->where('trantype','=','GST')
                            ->delete();


                    //2. check glmastdtl utk debit, kalu ada update kalu xde create
                    if($this->isGltranExist($drcostcode_,$dracc_,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$drcostcode_)
                            ->where('glaccount','=',$dracc_)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount - $value->amtslstax,
                                'recstatus' => 'A'
                            ]);
                    }else{
                        
                    }

                    //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                    if($this->isGltranExist($queryACC->pvalue1,$queryACC->pvalue2,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$queryACC->pvalue1)
                            ->where('glaccount','=',$queryACC->pvalue2)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount + $value->amtslstax,
                                'recstatus' => 'A'
                            ]);
                    }else{
                       
                    }
                }

            } // habis looping untuk delorddt

            //--- 8. change recstatus to cancelled ---//

            DB::table('material.delordhd')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'CANCELLED' 
                ]);

            DB::table('material.delorddt')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','!=','DELETE')
                ->update([
                    'recstatus' => 'CANCELLED' 
                ]);

            // dd(DB::getQueryLog());
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function request_no($trantype,$dept){
        $seqno = DB::table('material.sequence')
                ->select('seqno')
                ->where('trantype','=',$trantype)->where('dept','=',$dept)->first();

        DB::table('material.sequence')
        ->where('trantype','=',$trantype)->where('dept','=',$dept)
        ->update(['seqno' => intval($seqno->seqno) + 1]);
        
        return $seqno->seqno;
    }

    public function recno($source,$trantype){
        $pvalue1 = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('source','=',$source)->where('trantype','=',$trantype)->first();

        DB::table('sysdb.sysparam')
        ->where('source','=',$source)->where('trantype','=',$trantype)
        ->update(['pvalue1' => intval($pvalue1->pvalue1) + 1]);
        
        return $pvalue1->pvalue1;
    }

    //nak check glmasdtl exist ke tak utk sekian costcode, glaccount, year, period
    //kalu jumpa dia return true, pastu simpan actamount{month} dkn global variable gltranAmount
    public function isGltranExist($ccode,$glcode,$year,$period){
        $pvalue1 = DB::table('finance.glmasdtl')
                ->select("glaccount","actamount".$period)
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('costcode','=',$ccode)
                ->where('glaccount','=',$glcode)
                ->first();
        if(!count($pvalue1)){
            return false;
        }else{
            $pvalue1 = (array)$pvalue1;

            $this->gltranAmount = $pvalue1["actamount".$period];
            return true;
        }
    }

    public function toYear($date){
        $carbon = new Carbon($date);
        return $carbon->year;
    }

    public function toMonth($date){
        $carbon = new Carbon($date);
        return $carbon->month;
    }

    public function getyearperiod($date){
        $period = DB::table('sysdb.period')
            ->where('compcode','=',session('compcode'))
            ->get();

        $seldate = new DateTime($date);

        foreach ($period as $value) {
            $arrvalue = (array)$value;

            $year= $value->year;
            $period=0;

            for($x=1;$x<=12;$x++){
                $period = $x;

                $datefr = new DateTime($arrvalue['datefr'.$x]);
                $dateto = new DateTime($arrvalue['dateto'.$x]);
                if (($datefr <= $seldate) &&  ($dateto >= $seldate)){
                    $responce = new stdClass();
                    $responce->year = $year;
                    $responce->period = $period;
                    return $responce;
                }
            }
        }
    }

    public function save_dt_from_othr_po($refer_recno,$recno){
        $po_dt = DB::table('material.purorddt')
                ->select('compcode, recno, lineno_, pricecode, itemcode, uomcode, qtyorder, qtydelivered, unitprice, taxcode,perdisc,amtdisc, amtslstax,amount,recstatus,remarks')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        foreach ($po_dt as $key => $value) {
            ///1. insert detail we get from existing purchase order
            $table = DB::table("material.delorddt");
            $table->insert([
                'compcode' => session('compcode'), 
                'recno' => $recno, 
                'lineno_' => $value->lineno_, 
                'pricecode' => $value->pricecode, 
                'itemcode' => $value->itemcode, 
                'uomcode' => $value->uomcode, 
                'qtytag' => 0, 
                'qtyorder' => $value->qtyorder, 
                'qtydelivered' => $value->qtydelivered, 
                'unitprice' => $value->unitprice, 
                'taxcode' => $value->taxcode, 
                'perdisc' => $value->perdisc, 
                'amtdisc' => $value->amtdisc, 
                'amtslstax' => $value->amtslstax, 
                'amount' => $value->amount, 
                'adduser' => session('username'), 
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                'recstatus' => 'A', 
                'remarks' => $value->remarks
            ]);
        }
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
                'totamount' => $amount, 
                'subamount' => $amount
            ]);

        return $amount;
    }
}

