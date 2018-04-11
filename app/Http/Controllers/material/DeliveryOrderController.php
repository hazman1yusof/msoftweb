<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
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
                ->select('compcode', 'recno', 'delordno', 'deldept', 'trantype', 'docno', 'srcdocno', 'suppcode', 'trandate', 'trantime', 'deliverydate', 'checkpersonid', 'adduser', 'remarks', 'recstatus')
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
                    ->where('compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$request->recno)
                    ->where('delorddt.lineno_','=',$value->lineno_)
                    ->first();
                $convfactorPOUOM = $convfactorPOUOM_obj->convfactor;

                $convfactorUOM_obj = DB::table('material.delorddt')
                    ->select('uom.convfactor')
                    ->join('material.uom','delorddt.uomcode','=','uom.uomcode')
                    ->where('compcode','=',session('compcode'))
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
                            'NetMvQty'.$month = $NetMvQty, 
                            'NetMvVal'.$month = $NetMvVal
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
                if(count($stockloc_obj)){
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

            }




            //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//

            //--- 7. posting GL ---//

            // DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function cancel(Request $request){

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

    public function isGltranExist($ccode,$glcode,$year,$period){
        $pvalue1 = DB::table('finance.glmasdtl')
                ->select("glaccount","actamount".$period)
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('costcode','=',$ccode)
                ->where('glaccount','=',$glcode)
                ->first();

        $this->gltranAmount = $pvalue1["actamount".$period];
        return !empty($pvalue1);
    }

    public function toYear($date){
        $carbon = new Carbon\Carbon($date);
        return $carbon->year;
    }

    public function toMonth($date){
        $carbon = new Carbon\Carbon($date);
        return $carbon->month;
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

