<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class PurchaseOrderController extends defaultController
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.purchaseOrder.purchaseOrder');
    }

    public function form(Request $request)
    {   
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

        $purordno = $this->purordno('PO',$request->purordhd_prdept);
        $recno = $this->recno('PUR','PO');
        // $purreqno = $this->purreqno($request->purordhd_purreqno);


        DB::beginTransaction();

        $table = DB::table("material.purordhd");

        $array_insert = [
            // 'trantype' => 'PO', 
            'recno' => $recno,
            'purordno' => $purordno,
            // 'purreqno' => $purreqno,
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now(),
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            if(!empty($request->referral)){
                ////ni kalu dia amik dari pr
                ////amik detail dari pr sana, save dkt po detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_pr($request->referral,$recno);

                $purreqno = $request->purordhd_purreqno;
                $purordno = $request->purordhd_purordno;

                ////dekat po header sana, save balik delordno dkt situ
                DB::table('material.purreqhd')
                ->where('purreqno','=',$purreqno)->where('compcode','=',session('compcode'))
                ->update(['purordno' => $purordno]);

            }

            $responce = new stdClass();
            $responce->purordno = $purordno;
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

        $purreqno = DB::table('material.purordhd')
                    ->select('purreqno')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->purordhd_recno)->first();
       
       if($purreqno->purreqno == $request->purordhd_purreqno){
            // ni edit macam biasa, nothing special
            DB::beginTransaction();

            $table = DB::table("material.purordhd");

            $array_update = [
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now()
            ];

            foreach ($field as $key => $value) {
                $array_update[$value] = $request[$request->field[$key]];
            }

            try {
                //////////where//////////
                $table = $table->where('idno','=',$request->purordhd_idno);
                $table->update($array_update);

                $responce = new stdClass();
                $responce->totalAmount = $request->purordhd_totamount;
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
                if($purreqno->purreqno != '0'){
                    DB::table('material.purreqhd')
                    ->where('purreqno','=', $purreqno->purreqno)->where('compcode','=',session('compcode'))
                    ->update(['purordno' => '0']);
                }

                //2. Delete detail from delorddt
                DB::table('material.purorddt')->where('recno','=',$request->purordhd_recno);

                //3. Update srcdocno_delordhd
                $table = DB::table("material.purordhd");

                $array_update = [
                    'compcode' => session('compcode'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now()
                ];

                foreach ($field as $key => $value) {
                    $array_update[$value] = $request[$request->field[$key]];
                }

                $table = $table->where('idno','=',$request->purordhd_idno);
                $table->update($array_update);

                $totalAmount = $request->purordhd_totamount;
                //4. Update delorddt
                if(!empty($request->referral)){
                    $totalAmount = $this->save_dt_from_othr_pr($request->referral,$request->purordhd_recno);

                    $purreqno = $request->purordhd_purreqno;
                    $purordno = $request->purordhd_purordno;

                    ////dekat pr header sana, save balik purordno dkt situ
                    DB::table('material.purreqhd')
                        ->where('purreqno','=',$purreqno)->where('compcode','=',session('compcode'))
                        ->update(['purordno' => $purordno]);  
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
             //--- 1. copy purordhd masuk dalam delordhd ---//

                //1. amik dari purordhd
            $purordhd_obj = DB::table('material.purordhd')
                ->where('recno', '=', $request->recno)
                ->where('compcode', '=' ,session('compcode'))
                ->first();

                //2. pastu letak dkt delordhd
            DB::table('material.delordhd')
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
           //--- 2. loop purorddt untuk masuk dalam delorddt ---//

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
    //--- 8. change recstatus to posted ---//
} 
            DB::table('material.purordhd')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'POSTED' 
                ]);

            DB::table('material.purorddt')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','!=','DELETE')
                ->update([
                    'recstatus' => 'POSTED' 
                ]);
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
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

      public function purordno($trantype,$dept){
        $seqno = DB::table('material.sequence')
                ->select('seqno')
                ->where('trantype','=',$trantype)->where('dept','=',$dept)->first();

        DB::table('material.sequence')
        ->where('trantype','=',$trantype)->where('dept','=',$dept)
        ->update(['seqno' => intval($seqno->seqno) + 1]);
        
        return $seqno->seqno;

    }

     // public function toGetAllpurreqhd($recno){
     //    $purreqno = DB::table('material.purordhd')
     //            ->select('purreqno')
     //            ->where('recno','=',$recno)->first();

     //    DB::table('material.purordhd')
     //    ->where('recno','=',$recno)
     //    ->update(['purreqno' => intval($purreqno->purreqno) + 1]);
        
     //    return $purreqno->purreqno;

     //    }

    public function save_dt_from_othr_pr($refer_recno,$recno){
        $po_dt = DB::table('material.purreqdt')
                ->select('compcode, recno, lineno_, pricecode, itemcode, uomcode, qtyrequest, unitprice, taxcode,perdisc,amtdisc, amtslstax,amount,recstatus,remarks')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        foreach ($pr_dt as $key => $value) {
            ///1. insert detail we get from existing purchase request
            $table = DB::table("material.purorddt");
            $table->insert([
                'compcode' => session('compcode'), 
                'recno' => $recno, 
                'lineno_' => $value->lineno_, 
                'pricecode' => $value->pricecode, 
                'itemcode' => $value->itemcode, 
                'uomcode' => $value->uomcode, 
                'qtyrequest' => $value->qtyrequest, 
                'unitprice' => $value->unitprice, 
                'taxcode' => $value->taxcode, 
                'perdisc' => $value->perdisc, 
                'amtdisc' => $value->amtdisc, 
                'amtslstax' => $value->amtslstax, 
                'amount' => $value->amount, 
                'adduser' => session('username'), 
                'adddate' => Carbon::now(), 
                'recstatus' => 'A', 
                'remarks' => $value->remarks
            ]);
        }
        ///2. calculate total amount from detail erlier
        $amount = DB::table('material.purorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','<>','DELETE')
                    ->sum('amount');

        ///3. then update to header
        $table = DB::table('material.purorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno);
        $table->update([
                'totamount' => $amount, 
                'subamount' => $amount
            ]);

        return $amount;
    }
}


