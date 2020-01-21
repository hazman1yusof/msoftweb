<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\util\do_util;

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
            case 'reopen':
                return $this->reopen($request);
            /*case 'posted':
                return $this->posted($request);
            case 'posted_single':
                return $this->posted_single($request);
            case 'reopen_single':
                return $this->reopen($request);*/
            case 'soft_cancel':
                return $this->soft_cancel($request);
            case 'cancel':
                return $this->cancel($request);
            case 'refresh_do':
                return $this->refresh_do($request);
            default:
                return 'error happen..';
        }
    }

    public function get_productcat($itemcode){
        $query = DB::table('material.product')
                ->select('productcat')
                ->where('compcode','=',session('compcode'))
                ->where('itemcode','=',$itemcode)
                ->first();
        
        return $query->productcat;
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $request_no = $this->request_no('DO', $request->delordhd_prdept);
        $recno = $this->recno('PUR','DO');

        DB::beginTransaction();

        $table = DB::table("material.delordhd");

        $array_insert = [
            'trantype' => 'GRN', 
            'docno' => $request_no,
            'recno' => $recno,
            'compcode' => session('compcode'),
            'unit' => session('unit'),
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
                $totalAmount = $this->save_dt_from_othr_po($request->referral,$recno,$request->delordhd_srcdocno);

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
                'unit' => session('unit'),
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
                    $totalAmount = $this->save_dt_from_othr_po($request->referral,$request->delordhd_recno,$request->delordhd_srcdocno);

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

            
            // $Stock_flag_obj = DB::table('material.delorddt')
            //     ->where('recno', '=', $request->recno)
            //     ->where('compcode', '=' ,session('compcode'))
            //     ->where('pricecode', '<>', 'MS');

            // $Stock_flag = $Stock_flag_obj->exists();

            $delorddt_obj = DB::table('material.delorddt')
                ->where('delorddt.compcode','=',session('compcode'))
                ->where('delorddt.unit','=',session('unit'))
                ->where('delorddt.recno','=',$request->recno)
                ->where('delorddt.recstatus','!=','DELETE')
                ->get();

            //check kalu dia stock
            $Stock_flag = false;
            foreach ($delorddt_obj as $value) {

                //product from delorddt.itemcode
                //if product.groupcode = "stock" then stockflag = iv
                //if product.groupcode = "asset" then stockflag = asset
                //if product.groupcode = "other" then stockflag = other
                $Stock_flag = DB::table('material.product')
                    ->where('compcode','=', $value->compcode)
                    ->where('unit','=', $value->unit)
                    ->where('groupcode','=', "Stock")
                    ->where('itemcode','=', $value->itemcode)
                    ->exists();

                if($Stock_flag) break;

            }

                //2. pastu letak dkt ivtxnhd

            if($Stock_flag){
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
                        'remarks'=>$delordhd_obj->remarks,
                        'unit' =>$delordhd_obj->unit
                    ]);
            }
            

            //--- 2. loop delorddt untuk masuk dalam ivtxndt ---//

                //1.amik productcat dari table product
            // $productcat_obj = DB::table('material.delorddt')
            //     ->select('product.productcat')
            //     ->join('material.product', function($join) use ($request){
            //         $join = $join->on('delorddt.itemcode', '=', 'product.itemcode');
            //         $join = $join->on('delorddt.uomcode', '=', 'product.uomcode');
            //     })
            //     ->where('delorddt.compcode','=',session('compcode'))
            //     ->where('delorddt.unit','=',session('unit'))
            //     ->where('delorddt.recno','=',$request->recno)
            //     ->first();


                //2. start looping untuk delorddt
            foreach ($delorddt_obj as $value) {
                
                $productcat = $value->productcat;

                $value->expdate = $this->null_date($value->expdate);

                //3. dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
                $convfactorPOUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.pouom','=','uom.uomcode')
                    ->where('delorddt.unit','=',session('unit'))
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$request->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorPOUOM = $convfactorPOUOM_obj->convfactor;

                $convfactorUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.uomcode','=','uom.uomcode')
                    ->where('delorddt.unit','=',session('unit'))
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$request->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorUOM = $convfactorUOM_obj->convfactor;

                $txnqty = $value->qtydelivered * ($convfactorPOUOM / $convfactorUOM);
                $netprice = $value->netunitprice * ($convfactorUOM / $convfactorPOUOM);

                //4. start insert dalam ivtxndt

                $product_obj = DB::table('material.product')
                    ->where('compcode','=', $value->compcode)
                    ->where('unit','=', $value->unit)
                    ->where('itemcode','=', $value->itemcode)
                    ->first();

                if($product_obj->groupcode == "Stock" ){
                //--- 2.5. masuk dalam intxndt ---//
                    do_util::ivtxndt_ins($value,$txnqty,$netprice,$delordhd_obj,$productcat);

                //--- 3. posting stockloc ---///
                    do_util::stockloc_ins($value,$txnqty,$netprice);

                //--- 4. posting stock Exp ---//
                    do_util::stockExp_ins($value,$txnqty,$netprice);

                //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//
                    do_util::product_ins($value,$txnqty,$netprice);

                //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//
                    do_util::update_po($value,$txnqty,$netprice);
                }

            //--- 6. posting GL ---//
                do_util::postingGL($value,$delordhd_obj,$productcat);

            //--- 7. posting GL gst punya---//
                do_util::postingGL_GST($value,$delordhd_obj); 

            //---- 8. update po kalu ada srcdocno ---//
                if($delordhd_obj->srcdocno != 0){



                    $podt_obj = DB::table('material.purorddt')
                        ->where('compcode','=',session('compcode'))
                        ->where('itemcode','=',$value->itemcode)
                        ->where('prdept','=',$value->prdept)
                        ->where('purordno','=',$value->srcdocno)
                        ->where('lineno_','=',$value->polineno);

                    $podt_obj_lama = $podt_obj->first();
                    $this->checkIfPOposted($value); //dd('test');

                    $jumlah_qtydelivered = $podt_obj_lama->qtydelivered + $value->qtydelivered;
                    $qtyoutstand = $podt_obj_lama->qtyorder - $jumlah_qtydelivered;

                    if($jumlah_qtydelivered > $podt_obj_lama->qtyorder){
                        throw new \Exception("Quantity delivered exceed quantity order");
                    }

                    $podt_obj->update([
                        'qtydelivered' => $jumlah_qtydelivered,
                        'qtyoutstand' => $qtyoutstand
                    ]);

                    //update qtyoutstand utk suma DO pulak 
                    $delordhd = DB::table('material.delorddt')
                        ->where('compcode','=',session('compcode'))
                        ->where('itemcode','=',$value->itemcode)
                        ->where('prdept','=',$delordhd_obj->prdept)
                        ->where('srcdocno','=',$delordhd_obj->srcdocno)
                        ->update([
                            'qtyoutstand' => $qtyoutstand
                        ]);

                }

            } // habis looping untuk delorddt

            //--- 8. change recstatus to posted -dd--//
            DB::table('material.delordhd')
                ->where('recno','=',$request->recno)
                ->where('unit','=',session('unit'))
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'POSTED' 
                ]);

            DB::table('material.delorddt')
                ->where('recno','=',$request->recno)
                ->where('unit','=',session('unit'))
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','!=','DELETE')
                ->update([
                    'recstatus' => 'POSTED' 
                ]);
            

            $queries = DB::getQueryLog();
            dump($queries);


            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function reopen(Request $request){

         DB::beginTransaction();

        try{

            //--- 8. change recstatus to cancelled -dd--//
            DB::table('material.delordhd')
                ->where('recno','=',$request->recno)
                ->where('unit','=',session('unit'))
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN' 
                ]);

            DB::table('material.delorddt')
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

            return response('Error'.$e, 500);
        }
    }

    public function soft_cancel(Request $request){

         DB::beginTransaction();

        try{

            //--- 8. change recstatus to cancelled -dd--//
            DB::table('material.delordhd')
                ->where('recno','=',$request->recno)
                ->where('unit','=',session('unit'))
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'CANCELLED' 
                ]);

            DB::table('material.delorddt')
                ->where('recno','=',$request->recno)
                ->where('unit','=',session('unit'))
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','!=','DELETE')
                ->update([
                    'recstatus' => 'CANCELLED' 
                ]);

            DB::commit();
        
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
                    ->where('StockLoc.Year','=', defaultController::toYear($value->trandate))
                    ->where('StockLoc.UomCode','=',$value->uomcode);

                //2.kalu ada stockloc, update 
                if($stockloc_obj->exists()){
                    $stockloc_obj = $stockloc_obj->first();

                //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                    $stockloc_arr = (array)$stockloc_obj;
                    $month = defaultController::toMonth($value->trandate);
                    $QtyOnHand = $stockloc_obj->qtyonhand - $txnqty; 
                    $NetMvQty = $stockloc_arr['netmvqty'.$month] - $txnqty;
                    $NetMvVal = $stockloc_arr['netmvval'.$month] - ($netprice * $txnqty);

                    DB::table('material.StockLoc')
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$value->deldept)
                        ->where('StockLoc.ItemCode','=',$value->itemcode)
                        ->where('StockLoc.Year','=', defaultController::toYear($value->trandate))
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
                    ->where('stockexp.year','=', defaultController::toYear($value->trandate))
                    ->where('stockexp.uomcode','=',$value->uomcode)
                    ->where('stockexp.batchno','=',$value->batchno);
                   // ->where('stockexp.lasttt','=','GRN')
                    // ->first();

                //2.kalu ada Stock Expiry, update

                if($stockexp_obj->exists()){
                    $stockexp_obj = $stockexp_obj->first();
                    $BalQty = $stockexp_obj->balqty - $txnqty;

                    DB::table('material.stockexp')
                        ->where('stockexp.compcode','=',session('compcode'))
                        ->where('stockexp.deptcode','=',$value->deldept)
                        ->where('stockexp.itemcode','=',$value->itemcode)
                        ->where('stockexp.expdate','=',$value->expdate)
                        ->where('stockexp.year','=', defaultController::toYear($value->trandate))
                        ->where('stockexp.uomcode','=',$value->uomcode)
                        ->where('stockexp.batchno','=',$value->batchno)
                     //   ->where('stockexp.lasttt','=','GRN')
                        ->update([
                            'balqty' => $BalQty
                        ]);

                }else{
               
                }

                 //--- 5. cancel to product -> update qtyonhand, avgcost, currprice ---//
                $product_obj = DB::table('material.product')
                    ->where('product.compcode','=',session('compcode'))
                    ->where('product.itemcode','=',$value->itemcode)
                    ->where('product.uomcode','=',$value->uomcode);
                    // ->first();


                if($product_obj->exists()){ // kalu jumpa
                    $product_obj = $product_obj->first();
                    $month = defaultController::toMonth($value->trandate);
                    $OldQtyOnHand = $product_obj->qtyonhand;
                    $currprice = $netprice;
                    $Oldavgcost = $product_obj->avgcost;
                    $OldAmount = $OldQtyOnHand * $Oldavgcost;
                    $NewAmount = $netprice * $txnqty;

                    $newqtyonhand = $OldQtyOnHand - $txnqty;
                    if($newqtyonhand == 0){
                        $newAvgCost = 0;
                    }else{
                        $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand - $txnqty);
                    }

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

                // dd($request->recno);

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
                    ->where('lineno_','=', $value->lineno_)
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
                            ->where('lineno_','=', $value->lineno_)
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

                //---- 8. update po kalu ada srcdocno ---//
                if(!empty($value->srcdocno) || $value->srcdocno != 0){
                    
                    $purordhd = DB::table('material.purordhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('purordno','=',$value->srcdocno)
                        ->first();

                    $po_recno = $purordhd->recno;

                    $podt_obj = DB::table('material.purorddt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$po_recno)
                        ->where('lineno_','=',$value->lineno_);

                    $podt_obj_lama = $podt_obj->first();

                    $jumlah_qtydelivered = $podt_obj_lama->qtydelivered - $value->qtydelivered;
                    $qtyoutstand = $podt_obj_lama->qtyorder + $jumlah_qtydelivered;

                    $podt_obj->update([
                        'qtydelivered' => $jumlah_qtydelivered,
                        'qtyoutstand' => $qtyoutstand
                    ]);


                    //update qtyoutstand utk suma DO pulak
                    $delordhd = DB::table('material.delorddt')
                        ->where('compcode','=',session('compcode'))
                        ->where('itemcode','=',$value->itemcode)
                        ->where('prdept','=',$delordhd_obj->prdept)
                        ->where('srcdocno','=',$delordhd_obj->srcdocno)
                        ->update([
                            'qtyoutstand' => $qtyoutstand
                        ]);

                }

            } // habis looping untuk delorddt

            //--- 9. change recstatus to cancelled ---//

            $delordhd = DB::table('material.delordhd')
                        ->where('recno','=',$request->recno)
                        ->where('compcode','=',session('compcode'));

            $delordhd_obj = $delordhd->first();

            $purordhd = DB::table('material.purordhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('purordno','=',$delordhd_obj->srcdocno)
                        ->update(['delordno' => ""]);

            $delordhd->update([
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

            dump(DB::getQueryLog());
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function save_dt_from_othr_po($refer_recno,$recno,$srcdocno){
        $po_dt = DB::table('material.purorddt')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        $po_hd = DB::table('material.purordhd')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->first();

        foreach ($po_dt as $key => $value) {


            $productcat = $this->get_productcat($value->itemcode);

            ///1. insert detail we get from existing purchase order
            $table = DB::table("material.delorddt");
            if($value->qtyorder - $value->qtydelivered > 0){
                $table->insert([
                    'compcode' => session('compcode'), 
                    'recno' => $recno, 
                    'lineno_' => $value->lineno_, 
                    'polineno' => $value->lineno_,
                    'pricecode' => $value->pricecode, 
                    'itemcode' => $value->itemcode, 
                    'uomcode' => $value->uomcode,
                    'pouom' => $value->pouom,  
                    'suppcode' => $po_hd->suppcode,
                    'trandate' => $po_hd->purdate,
                    'deldept' => $po_hd->deldept,
                    'deliverydate' => $po_hd->expecteddate,
                    'qtyorder' => $value->qtyorder, 
                    'qtydelivered' => $value->qtydelivered, 
                    'qtyoutstand' => $value->qtyorder - $value->qtydelivered,
                    'unitprice' => $value->unitprice, 
                    'taxcode' => $value->taxcode, 
                    'perdisc' => $value->perdisc,
                    'amtdisc' => $value->amtdisc, 
                    'amtslstax' => $value->amtslstax, 
                    'netunitprice' => $value->netunitprice,
                    'amount' => $value->amount, 
                    'totamount' => $value->totamount,
                    'productcat' => $productcat,
                    'srcdocno' => $po_hd->purordno,
                    'prdept' => $value->prdept, 
                    'rem_but'=>$value->rem_but,
                    'unit' => session('unit'), 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'A', 
                    'remarks' => $value->remarks
                ]);
            }
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
                //'subamount' => $amount
            ]);

        return $amount;
    }

    public function checkIfPOposted($value){
        $po_hd = DB::table('material.purordhd')
                ->where('purordno', '=', $value->srcdocno)
                ->where('compcode', '=', session('compcode'))
                ->first();
         //dd($po_hd);

        if($po_hd->recstatus == 'CANCELLED'){
            throw new \Exception("Cannot posted, PO is CANCELLED");
        }else if($po_hd->recstatus != 'APPROVED'){
        // dd('test');
            throw new \Exception("Cannot posted, PO not APPROVED yet");
        }
    }

    public function refresh_do(Request $request){
        $do_hd = DB::table('material.delordhd')
                ->where('idno', '=', $request->idno)
                ->first();

        $po_hd = DB::table('material.purordhd')
                ->where('purordno', '=', $do_hd->srcdocno)
                ->first();

        $po_dt = DB::table('material.purorddt')
                ->where('recno', '=', $po_hd->recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        foreach ($po_dt as $key => $value) {

            $do_dt = DB::table('material.delorddt')
                        ->where('polineno', '=', $value->lineno_)
                        ->where('recno', '=', $do_hd->recno);

            if($do_dt->exists()){
                $do_dt
                    ->update([
                        'qtyorder' => $value->qtyorder
                    ]);
            }else{

                $productcat = $this->get_productcat($value->itemcode);

                $table = DB::table("material.delorddt");
                if($value->qtyorder - $value->qtydelivered > 0){
                    $table->insert([
                        'compcode' => session('compcode'), 
                        'recno' => $do_hd->recno, 
                        'lineno_' => $value->lineno_, 
                        'polineno' => $value->lineno_,
                        'pricecode' => $value->pricecode, 
                        'itemcode' => $value->itemcode, 
                        'uomcode' => $value->uomcode,
                        'pouom' => $value->pouom,  
                        'suppcode' => $po_hd->suppcode,
                        'trandate' => $po_hd->purdate,
                        'deldept' => $po_hd->deldept,
                        'deliverydate' => $po_hd->expecteddate,
                        'qtyorder' => $value->qtyorder, 
                        'qtydelivered' => $value->qtydelivered, 
                        'qtyoutstand' => $value->qtyorder - $value->qtydelivered,
                        'unitprice' => $value->unitprice, 
                        'taxcode' => $value->taxcode, 
                        'perdisc' => $value->perdisc,
                        'amtdisc' => $value->amtdisc, 
                        'amtslstax' => $value->amtslstax, 
                        'netunitprice' => $value->netunitprice,
                        'amount' => $value->amount, 
                        'totamount' => $value->totamount,
                        'productcat' => $productcat,
                        'srcdocno' => $value->purordno,
                        'prdept' => $value->prdept, 
                        'rem_but'=>$value->rem_but,
                        'unit' => session('unit'), 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'A', 
                        'remarks' => $value->remarks
                    ]);
                }
            }
        }
    }
}

