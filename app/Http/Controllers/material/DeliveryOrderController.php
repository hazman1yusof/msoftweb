<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\util\do_util;
use PDF;

class DeliveryOrderController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $purdept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('purdept',1)
                        ->get();

        return view('material.deliveryOrder.deliveryOrder',compact('purdept'));
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

        DB::beginTransaction();

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        try {

            if(!empty($request->referral)){
                $request_no = $this->request_no('GRN', $request->delordhd_prdept);
                $recno = $this->recno('PUR','DO');
                $compcode = session('compcode');
            }else{
                $request_no = 0;
                $recno = 0;
                $compcode = 'DD';
            }

            $table = DB::table("material.delordhd");

            $array_insert = [
                'trantype' => 'GRN', 
                'docno' => $request_no,
                'recno' => $recno,
                'compcode' => $compcode,
                'unit' => session('unit'),
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

            $delordno_chk = DB::table('material.delordhd')
                                ->where('compcode','=',session('compcode'))
                                ->where('delordno','=',$request->delordhd_delordno);

            if($delordno_chk->exists()){
                throw new \Exception("Delordno : '".$request->delordhd_delordno."' already exists, insert other no");
            }

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            if(!empty($request->referral)){
                ////ni kalu dia amik dari po
                ////amik detail dari po sana, save dkt do detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_po($request->referral,$recno,$request->delordhd_srcdocno);

                $srcdocno = $request->delordhd_srcdocno;
                $delordno = $request->delordhd_delordno;

                ////dekat po header sana, save balik delordno dkt situ
                // DB::table('material.purordhd')
                //     ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                //     ->update(['delordno' => $delordno]);
            }

            $responce = new stdClass();
            $responce->docno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;
            $responce->adduser = session('username');
            $responce->adddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
            echo json_encode($responce);

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
        $srcdocno = DB::table('material.delordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->delordhd_recno);

        if(!$srcdocno->exists()){
            $srcdocno = DB::table('material.delordhd')
                    ->where('compcode','=','DD')
                    ->where('recno','=',$request->delordhd_recno);
        }

        $srcdocno = $srcdocno->first();
        
        if($srcdocno->srcdocno == $request->delordhd_srcdocno){
            // ni edit macam biasa, nothing special
            DB::beginTransaction();

            $table = DB::table("material.delordhd");

            $array_update = [
                'unit' => session('unit'),
                'compcode' => $srcdocno->compcode,
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
            ];

            foreach ($field as $key => $value) {
                if(is_string($request[$request->field[$key]])){
                    $array_update[$value] = strtoupper($request[$request->field[$key]]);
                }else{
                    $array_update[$value] = $request[$request->field[$key]];
                }
            }

            try {
                //////////where//////////
                $table = $table->where('idno','=',$request->delordhd_idno);
                $table->update($array_update);

                $responce = new stdClass();
                $responce->totalAmount = $request->delordhd_totamount;
                $responce->upduser = session('username');
                $responce->upddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response($e, 500);
            }
        }else{
            DB::beginTransaction();

            try{
                // ni edit kalu copy utk do dari existing po
                //1. update po.delordno lama jadi 0, kalu do yang dulu pon copy existing po 
                if($srcdocno->srcdocno != '0'){
                    DB::table('material.purordhd')
                    ->where('purordno','=', $srcdocno->srcdocno)
                    ->where('compcode','=',session('compcode'))
                    ->update(['delordno' => '0']);
                }

                //2. Delete detail from delorddt
                DB::table('material.delorddt')
                    ->where('recno','=',$request->delordhd_recno)
                    ->delete();

                //3. Update srcdocno_delordhd
                $table = DB::table("material.delordhd");

                $array_update = [
                    'compcode' => $srcdocno->compcode,
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
                    // DB::table('material.purordhd')
                    //     ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                    //     ->update(['delordno' => $delordno]);
                }

                $responce = new stdClass();
                $responce->totalAmount = $totalAmount;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response($e->getMessage(), 500);
            }
        }

    }

    public function del(Request $request){

    }

    public function posted(Request $request){
        DB::beginTransaction();

        try{
            foreach ($request->idno_array as $idno){
                //--- 1. copy delordhd masuk dalam ivtxnhd ---//

                    //1. amik dari delordhd

                $delordhd = DB::table('material.delordhd')
                    ->where('idno', '=', $idno);

                $delordhd_obj = $delordhd->first();

                $deldept_unit = DB::table('sysdb.department')
                                ->where('compcode',session('compcode'))
                                ->where('deptcode',$delordhd_obj->deldept)
                                ->first();

                $deldept_unit = $deldept_unit->sector;
                
                if($delordhd_obj->recstatus != 'OPEN'){
                    continue;
                }

                $delorddt_obj = DB::table('material.delorddt')
                    ->where('delorddt.compcode','=',session('compcode'))
                    // ->where('delorddt.unit','=',session('unit'))
                    ->where('delorddt.recno','=',$delordhd_obj->recno)
                    ->where('delorddt.recstatus','!=','DELETE')
                    ->get();

                //check stockloc frozen == yes/no
                $stockloc_chk = false;
                foreach ($delorddt_obj as $value) {

                    $stockloc_chk = DB::table('material.stockloc')
                        ->where('unit','=',$deldept_unit)
                        ->where('compcode','=',$value->compcode)
                        ->where('deptcode','=',$value->deldept)
                        ->where('itemcode','=',$value->itemcode)
                        ->where('uomcode','=',$value->uomcode)
                        ->where('year', '=',defaultController::toYear($value->trandate))
                        ->where('unit','=',$value->unit)
                        ->where('frozen','=','1')
                        ->exists();

                    if($stockloc_chk){
                        throw new \Exception("Itemcode is frozen due to stock take. Can't be posted");
                    }
                }
 
                //check kalu dia stock
                $Stock_flag = false;
                foreach ($delorddt_obj as $value) {

                    //product from delorddt.itemcode
                    //if product.groupcode = "stock" then stockflag = iv
                    //if product.groupcode = "asset" then stockflag = asset
                    //if product.groupcode = "other" then stockflag = other
                    $Stock_flag = DB::table('material.product')
                        ->where('compcode','=', $value->compcode)
                        ->where('unit','=', $deldept_unit)
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
                            'unit' =>session('unit')
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
                    if($value->qtydelivered == 0){
                        continue;
                    }
                    
                    $productcat = $value->productcat;

                    $value->expdate = $this->null_date($value->expdate);

                    //3. dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
                    $convfactorPOUOM_obj = DB::table('material.delorddt')
                        ->select('uom.convfactor')
                        ->join('material.uom','delorddt.pouom','=','uom.uomcode')
                        // ->where('delorddt.unit','=',session('unit'))
                        ->where('delorddt.compcode','=',session('compcode'))
                        ->where('delorddt.recno','=',$delordhd_obj->recno)
                        ->where('delorddt.lineno_','=',$value->lineno_)
                        ->first();
                    $convfactorPOUOM = $convfactorPOUOM_obj->convfactor;

                    $convfactorUOM_obj = DB::table('material.delorddt')
                        ->select('uom.convfactor')
                        ->join('material.uom','delorddt.uomcode','=','uom.uomcode')
                        // ->where('delorddt.unit','=',session('unit'))
                        ->where('delorddt.compcode','=',session('compcode'))
                        ->where('delorddt.recno','=',$delordhd_obj->recno)
                        ->where('delorddt.lineno_','=',$value->lineno_)
                        ->first();
                    $convfactorUOM = $convfactorUOM_obj->convfactor;

                    $txnqty = $value->qtydelivered * ($convfactorPOUOM / $convfactorUOM);
                    $netprice = $value->netunitprice * ($convfactorUOM / $convfactorPOUOM);

                    //4. start insert dalam ivtxndt
                    if(in_array($value->pricecode, ['IV','BO'])){
                        $unit_ = $deldept_unit;
                    }else{
                        $unit_ = 'ALL';
                    }

                    $product_obj = DB::table('material.product')
                        ->where('compcode','=', $value->compcode)
                        ->where('unit','=', $unit_)
                        ->where('itemcode','=', $value->itemcode)
                        ->first();

                    if(strtoupper($product_obj->groupcode) == "STOCK" ){
                        //--- 2.5. masuk dalam intxndt ---//
                        do_util::ivtxndt_ins($value,$txnqty,$netprice,$delordhd_obj,$productcat);

                        //--- 3. posting stockloc ---///
                        do_util::stockloc_ins($value,$txnqty,$netprice,$unit_);

                        //--- 4. posting stock Exp ---//
                        do_util::stockExp_ins($value,$txnqty,$netprice,$unit_);

                        //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//
                        do_util::product_ins($value,$txnqty,$netprice,$unit_);

                        //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//
                        do_util::update_po($value,$txnqty,$netprice);
                    }

                    //--- 6. posting GL ---//
                    do_util::postingGL($value,$delordhd_obj,$productcat,$unit_);

                    //--- 7. posting GL gst punya---//
                    do_util::postingGL_GST($value,$delordhd_obj); 

                    //---- 8. update po kalu ada srcdocno ---//
                    if(!empty($delordhd_obj->srcdocno)){

                        $this->checkIfPOposted($delordhd_obj);

                        $status = 'COMPLETED';

                        $podt_obj = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$value->itemcode)
                            ->where('prdept','=',$value->prdept)
                            ->where('purordno','=',$value->srcdocno)
                            ->where('lineno_','=',$value->polineno);

                        $podt_obj_lama = $podt_obj->first();

                        $jumlah_qtydelivered = Floatval($podt_obj_lama->qtydelivered) + Floatval($value->qtydelivered);
                        $qtyoutstand = Floatval($podt_obj_lama->qtyorder) - Floatval($jumlah_qtydelivered);

                        if($qtyoutstand > 0){
                            $status = 'PARTIAL';
                        }

                        if($jumlah_qtydelivered > $podt_obj_lama->qtyorder){
                            throw new \Exception("Quantity delivered exceed quantity order");
                        }

                        DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$value->itemcode)
                            ->where('prdept','=',$value->prdept)
                            ->where('purordno','=',$value->srcdocno)
                            ->where('lineno_','=',$value->polineno)
                            ->update([
                                'qtydelivered' => $jumlah_qtydelivered,
                                'qtyoutstand' => $qtyoutstand,
                                'recstatus' => $status
                            ]);

                        //update qtyoutstand utk suma DO pulak 
                        DB::table('material.delorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$value->itemcode)
                            ->where('prdept','=',$delordhd_obj->prdept)
                            ->where('srcdocno','=',$delordhd_obj->srcdocno)
                            ->update([
                                'qtyoutstand' => $qtyoutstand
                            ]);

                    }

                } // habis looping untuk delorddt

            }

            //--- 8. change recstatus to posted -dd--//
            $this->chg_recstatus_do_then_po($delordhd_obj);

            // $queries = DB::getQueryLog();
            // dump($queries);


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

            foreach ($request->idno_array as $idno){


                $delordhd = DB::table('material.delordhd')
                    ->where('idno', '=', $idno);

                $delordhd_obj = $delordhd->first();

                //--- 8. change recstatus to cancelled -dd--//
                DB::table('material.delordhd')
                    ->where('recno','=',$delordhd_obj->recno)
                    ->where('unit','=',session('unit'))
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'recstatus' => 'CANCELLED' 
                    ]);

                DB::table('material.delorddt')
                    ->where('recno','=',$delordhd_obj->recno)
                    ->where('unit','=',session('unit'))
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','!=','DELETE')
                    ->update([
                        'recstatus' => 'CANCELLED' 
                    ]);

            }
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

            $delordhd = DB::table('material.delordhd')
                        ->where('idno','=',$request->idno);

            $delordhd_obj = $delordhd->first();

            if($delordhd_obj->recstatus != 'POSTED'){
                throw new \Exception("Cannot delete unposted DO");
            }



                //1.amik productcat dari table product
            // $productcat_obj = DB::table('material.delorddt')
            //     ->select('product.productcat')
            //     ->join('material.product', function($join) use ($request){
            //         $join = $join->on('delorddt.itemcode', '=', 'product.itemcode');
            //         $join = $join->on('delorddt.uomcode', '=', 'product.uomcode');
            //     })
            //     ->where('delorddt.compcode','=',session('compcode'))
            //     ->where('product.groupcode','=','Stock')
            //     ->where('delorddt.recno','=',$request->recno)
            //     ->first();
            // $productcat = $productcat_obj->productcat;

            $delorddt_obj = DB::table('material.delorddt')
                ->where('delorddt.compcode','=',session('compcode'))
                ->where('delorddt.unit','=',session('unit'))
                ->where('delorddt.recno','=',$delordhd_obj->recno)
                ->where('delorddt.recstatus','!=','DELETE')
                ->get();

            //2. start looping untuk delorddt
            foreach ($delorddt_obj as $value) {

                //3. dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
                $convfactorPOUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.pouom','=','uom.uomcode')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$value->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorPOUOM = $convfactorPOUOM_obj->convfactor;

                $convfactorUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.uomcode','=','uom.uomcode')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$value->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorUOM = $convfactorUOM_obj->convfactor;

                $txnqty = $value->qtydelivered * ($convfactorPOUOM / $convfactorUOM);
                $netprice = $value->netunitprice * ($convfactorUOM / $convfactorPOUOM);

                //4. update dalam ivtxnhd  ivtxndt
                DB::table('material.ivtxnhd')
                    ->where('ivtxnhd.compcode', '=', session('compcode'))
                    ->where('ivtxnhd.recno', '=', $value->recno)
                    ->update(['recstatus' => 'CANCELLED']);

                DB::table('material.ivtxndt')
                    ->where('ivtxndt.compcode', '=', session('compcode'))
                    ->where('ivtxndt.recno', '=', $value->recno)
                    ->update([
                        // 'netprice' => $netprice,
                        'recstatus' =>'CANCELLED'
                    ]);

                $ivtxndt = DB::table('material.ivtxndt')
                            ->where('ivtxndt.compcode', '=', session('compcode'))
                            ->where('ivtxndt.unit', '=', session('unit'))
                            ->where('ivtxndt.recno', '=', $value->recno)
                            ->where('ivtxndt.lineno_', '=', $value->lineno_)
                            ->first();

                DB::table('material.ivtxndt')
                    ->insert([
                        'compcode' => session('compcode'), 
                        'unit' => session('unit'), //ikut unit delorddt
                        'recno' => $ivtxndt->recno, 
                        'lineno_' => $ivtxndt->lineno_, 
                        'itemcode' => $ivtxndt->itemcode, 
                        'uomcode' => $ivtxndt->uomcode, 
                        'txnqty' => $ivtxndt->txnqty, 
                        'netprice' => $ivtxndt->netprice, 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'), 
                        'productcat' => $ivtxndt->productcat, 
                        'draccno' => $ivtxndt->draccno, 
                        'drccode' => $ivtxndt->drccode, 
                        'craccno' => $ivtxndt->craccno, 
                        'crccode' => $ivtxndt->crccode, 
                        'expdate' => $ivtxndt->expdate, 
                        'remarks' => $ivtxndt->remarks, 
                        'qtyonhand' => 0, 
                        'batchno' => $ivtxndt->batchno, 
                        'amount' => $ivtxndt->amount, 
                        'trandate' => $ivtxndt->trandate, 
                        'trantype' => 'GRC',
                        'deptcode' => $ivtxndt->deptcode, 
                        'gstamount' => $ivtxndt->gstamount, 
                        'totamount' => $ivtxndt->totamount
                    ]);

                //--- 3. cancel to stockloc ---///
                //1. amik stockloc
                $stockloc_obj = DB::table('material.StockLoc')
                    ->where('StockLoc.unit','=',$value->unit)
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
                        ->where('StockLoc.unit','=',$value->unit)
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
                    ->where('stockexp.unit','=',session('unit'))
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
                        ->where('stockexp.unit','=',session('unit'))
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

                //--- 6. rollover GL ---// roolover gl macam salah

                //amik ivtxnhd
                $ivtxnhd_obj = DB::table('material.ivtxnhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$value->recno)
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
                    ->where('catcode','=',$ivtxndt->productcat)
                    ->first();
                //utk credit costcode dgn accountocde
                $row_sysparam = DB::table('sysdb.sysparam')
                    ->select('pvalue1','pvalue2')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','ACC')
                    ->first();

                //1. delete gltran
                $gltran_obj = DB::table('finance.gltran')
                        ->where('auditno','=', $value->recno)
                        ->where('lineno_','=', $value->lineno_)
                        ->where('source','=','IV')
                        ->where('trantype','=','GRN')
                        ->first();

                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'auditno' => $gltran_obj->auditno,
                        'lineno_' => $gltran_obj->lineno_,
                        'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                        'trantype' => 'GRC',
                        'reference' => $gltran_obj->reference,
                        'description' => $gltran_obj->description, 
                        'postdate' => $gltran_obj->postdate,
                        'year' => $gltran_obj->year,
                        'period' => $gltran_obj->period,
                        'drcostcode' => $gltran_obj->drcostcode,
                        'dracc' => $gltran_obj->dracc,
                        'crcostcode' => $gltran_obj->crcostcode,
                        'cracc' => $gltran_obj->cracc,
                        'amount' => -floatval($gltran_obj->amount),
                        'idno' => $gltran_obj->idno
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
                            'actamount'.$yearperiod->period => $this->gltranAmount - $value->amount,
                            'recstatus' => 'ACTIVE'
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
                            'recstatus' => 'ACTIVE'
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
                            ->where('auditno','=', $value->recno)
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
                                'recstatus' => 'ACTIVE'
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
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                       
                    }
                }

                //---- 8. update po kalu ada srcdocno ---//
                if(!empty($delordhd_obj->srcdocno) || $delordhd_obj->srcdocno != 0){
                    
                    $purordhd = DB::table('material.purordhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('purordno','=',$delordhd_obj->srcdocno)
                        ->first();

                    $po_recno = $purordhd->recno;

                    $podt_obj = DB::table('material.purorddt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$po_recno)
                        ->where('lineno_','=',$value->lineno_);

                    $podt_obj_lama = $podt_obj->first();

                    $jumlah_qtydelivered = Floatval($podt_obj_lama->qtydelivered) - Floatval($value->qtydelivered);
                    $qtyoutstand = Floatval($podt_obj_lama->qtyorder) + Floatval($jumlah_qtydelivered);

                    $podt_obj->update([
                        'qtydelivered' => $jumlah_qtydelivered,
                        'qtyoutstand' => $qtyoutstand
                    ]);


                    // update qtyoutstand utk suma DO pulak
                    // $delordhd = DB::table('material.delorddt')
                    //     ->where('compcode','=',session('compcode'))
                    //     ->where('itemcode','=',$value->itemcode)
                    //     ->where('prdept','=',$delordhd_obj->prdept)
                    //     ->where('srcdocno','=',$delordhd_obj->srcdocno)
                    //     ->update([
                    //         'qtyoutstand' => $qtyoutstand
                    //     ]);

                }

            } // habis looping untuk delorddt


            if(!empty($delordhd_obj->srcdocno) || $delordhd_obj->srcdocno != 0){
                $purordhd = DB::table('material.purordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('purordno','=',$delordhd_obj->srcdocno)
                    ->update(['delordno' => ""]);
            }

            //--- 9. change recstatus to cancelled ---//

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

            return response($e->getMessage(), 500);
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
                    'qtydelivered' => 0, 
                    'qtyoutstand' => $value->qtyorder - $value->qtydelivered,
                    'unitprice' => $value->unitprice, 
                    'taxcode' => $value->taxcode, 
                    'perdisc' => $value->perdisc,
                    'amtdisc' => $value->amtdisc, 
                    'amtslstax' => $value->amtslstax, 
                    'netunitprice' => $value->netunitprice,
                    'amount' => 0, 
                    'totamount' => 0,
                    'productcat' => $productcat,
                    'srcdocno' => $po_hd->purordno,
                    'prdept' => $value->prdept, 
                    'rem_but'=>$value->rem_but,
                    'unit' => session('unit'), 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN', 
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

    public function checkIfPOposted($delordhd){
        $po_hd = DB::table('material.purordhd')
                ->where('purordno', '=', $delordhd->srcdocno)
                ->where('prdept', '=', $delordhd->prdept)
                ->where('compcode', '=', session('compcode'))
                ->first();

        switch ($po_hd->recstatus) {
            case 'CANCELLED':
                throw new \Exception("Cannot posted, PO is CANCELLED");
                break;

            case 'OPEN':
            case 'REQUEST':
            case 'SUPPORT':
            case 'VERIFIED':
                throw new \Exception("Cannot posted, PO still not APPROVED");
                break;

            case 'COMPLETED':
                throw new \Exception("Cannot posted, PO already COMPLETED");
                break;

            case 'APPROVED':
            case 'PARTIAL':
                break;
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
                        'recstatus' => 'ACTIVE', 
                        'remarks' => $value->remarks
                    ]);
                }
            }
        }
    }

    public function chg_recstatus_do_then_po($delordhd_obj){
        $do_hd = DB::table('material.delordhd')
                ->where('recno', '=', $delordhd_obj->recno)
                ->where('compcode', '=' ,session('compcode'))
                ->first();

        $po_hd = DB::table('material.purordhd')
                ->where('prdept', '=', $do_hd->prdept)
                ->where('purordno', '=', $do_hd->srcdocno)
                ->where('compcode', '=' ,session('compcode'))
                ->first();

        DB::table('material.delordhd')
                ->where('recno', '=', $delordhd_obj->recno)
                ->where('compcode', '=' ,session('compcode'))
                ->update(['recstatus'  => 'POSTED']);

        DB::table('material.delorddt')
                ->where('recno', '=', $delordhd_obj->recno)
                ->where('compcode', '=', session('compcode'))
                ->update(['recstatus'  => 'POSTED']);

        if(!empty($do_hd->srcdocno)){
            $podt_obj = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno',$po_hd->recno)
                            ->where('recstatus','!=','COMPLETED');
                            // ->where('purordno','=',$do_hd->srcdocno)
                            // ->where('prdept','=',$do_hd->prdept);

            if($podt_obj->exists()){
                $recstatus = 'PARTIAL';
            }else{
                $recstatus = 'COMPLETED';
            }

            DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('purordno', '=', $do_hd->srcdocno)
                ->where('prdept', '=', $do_hd->prdept)
                ->update(['recstatus'  => $recstatus]);
        }
    }

    public function showpdf(Request $request){
        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }
        
        $delordhd = DB::table('material.delordhd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->first();

        $delorddt = DB::table('material.delorddt AS dodt')
            ->select('dodt.compcode', 'dodt.recno', 'dodt.lineno_', 'dodt.pricecode', 'dodt.itemcode', 'p.description', 'dodt.uomcode', 'dodt.pouom', 'dodt.qtyorder', 'dodt.qtydelivered','dodt.unitprice', 'dodt.taxcode', 'dodt.perdisc', 'dodt.amtdisc', 'dodt.amtslstax as tot_gst','dodt.netunitprice', 'dodt.totamount','dodt.amount', 'dodt.rem_but AS remarks_button', 'dodt.remarks', 'dodt.recstatus', 'dodt.batchno', 'dodt.expdate','dodt.unit','dodt.kkmappno','u.description as uom_desc')
            ->leftJoin('material.productmaster as p', function($join) use ($request){
                        $join = $join->on('dodt.itemcode', '=', 'p.itemcode')
                                ->where('p.compcode','=',session('compcode'));
                    })
            ->leftJoin('material.uom as u', function($join) use ($request){
                        $join = $join->on('dodt.uomcode', '=', 'u.uomcode')
                                ->where('u.compcode','=',session('compcode'));
                    })
            ->where('dodt.compcode','=',session('compcode'))
            ->where('dodt.recno','=',$recno)
            ->get();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $total_amt = DB::table('material.delorddt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->sum('totamount');

        $total_tax = DB::table('material.delorddt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->sum('amtslstax');
        
        $total_discamt = DB::table('material.delorddt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->sum('amtdisc');

        $totamount_expld = explode(".", (float)$delordhd->totamount);

        $cc_acc=[];
        $cr_acc=[];
        $db_acc=[];
        if($delordhd->recstatus == 'POSTED'){
            foreach ($delorddt as $value) {
                $gltran = DB::table('finance.gltran as gl')
                       ->where('gl.compcode',session('compcode'))
                       ->where('gl.auditno',$value->recno)
                       ->where('gl.lineno_',$value->lineno_)
                       ->where('gl.source','IV')
                       ->where('gl.trantype',$delordhd->trantype)
                       ->first();

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
        
        return view('material.deliveryOrder.deliveryOrder_pdfmake',compact('delordhd','delorddt','totamt_eng', 'company', 'total_tax', 'total_discamt', 'total_amt','cr_acc','db_acc'));
        
    }
}

