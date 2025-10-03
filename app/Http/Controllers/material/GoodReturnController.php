<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class GoodReturnController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.goodReturn.goodReturn');
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
            case 'reopen':
                return $this->reopen($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table_default':
                return $this->get_table_default($request);
            case 'get_table_default_dtl':
                return $this->get_table_default_dtl($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_default(Request $request){
        $table =  DB::table('material.delordhd');
        $table = $table->select($this->fixPost($request->field,"_"))
                    ->whereNull('delordhd.cnno');

        $table = $table->leftJoin('material.supplier', function($join) use ($request){
                        $join = $join->where('supplier.SuppCode','=','delordhd.suppcode')
                                     ->where('supplier.compcode',session('compcode'));

                 });

        $table = $table->leftJoin('material.delordhd as do2', function($join) use ($request){
                        $join = $join->where('do2.compcode',session('compcode'))
                                    ->where('do2.trantype','GRN')
                                    ->on('do2.prdept','delordhd.prdept')
                                    ->on('do2.docno','=','delordhd.srcdocno');
                 });

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $pieces = explode(".", $request->filterVal[$key], 2);
                if($pieces[0] == 'session'){
                    $table = $table->where($request->filterCol[$key],'=',session($pieces[1]));
                }else if($pieces[0] == '<>'){
                    $table = $table->where($request->filterCol[$key],'<>',$pieces[1]);
                }else if($pieces[0] == '>'){
                    $table = $table->where($request->filterCol[$key],'>',$pieces[1]);
                }else if($pieces[0] == '>='){
                    $table = $table->where($request->filterCol[$key],'>=',$pieces[1]);
                }else if($pieces[0] == '<'){
                    $table = $table->where($request->filterCol[$key],'<',$pieces[1]);
                }else if($pieces[0] == '<='){
                    $table = $table->where($request->filterCol[$key],'<=',$pieces[1]);
                }else if($pieces[0] == 'on'){
                    $table = $table->whereColumn($request->filterCol[$key],$pieces[1]);
                }else if($pieces[0] == 'null'){
                    $table = $table->whereNull($request->filterCol[$key]);
                }else if($pieces[0] == 'raw'){
                    $table = $table->where($request->filterCol[$key],'=',DB::raw($pieces[1]));
                }else{
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }
        }

        if(!empty($request->sidx)){

            if(!empty($request->fixPost)){
                $request->sidx = substr_replace($request->sidx, ".", strpos($request->sidx, "_"), strlen("."));
            }
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_table_default_dtl(Request $request){
        $delordhd =  DB::table('material.delordhd')
                        ->where('compcode',session('compcode'))
                        ->where('idno',$request->idno)
                        ->first();


        $table =  DB::table('material.delorddt as dodt');
        $table = $table->select('dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode', 'dodt.pouom', 'dodt.suppcode','dodt.trandate',
        'dodt.deldept','dodt.deliverydate','dodt.qtydelivered','dodt.qtyreturned','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 
        'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks','t.rate')
        ->where('dodt.recno',$delordhd->recno);

        $table = $table->leftJoin('material.productmaster AS p', function($join){
                        $join = $join->on('p.itemcode','=','dodt.itemcode')
                                     ->where('p.compcode',session('compcode'));

                 });

        $table = $table->leftJoin('hisdb.taxmast AS t', function($join) {
                        $join = $join->where('t.compcode',session('compcode'))
                                    ->on('t.taxcode','dodt.taxcode');
                 });

        if(!empty($request->sidx)){
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
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

            // $request_no = $this->request_no('GRT', $request->delordhd_deldept);
            // $recno = $this->recno('PUR','DO');
            // $compcode = session('compcode');

            // // $request_no = 0;
            // // $recno = 0;
            // $compcode = 'DD';

            if(!empty($request->referral)){
                $request_no = $this->request_no('GRT', $request->delordhd_deldept);
                $recno = $this->recno('IV','IT');
                $compcode = session('compcode');
            }else{
                $request_no = 0;
                $recno = 0;
                $compcode = 'DD';
            }

            $table = DB::table("material.delordhd");

            $array_insert = [
                'trantype' => 'GRT', 
                'docno' => $request_no,
                'recno' => $recno,
                'compcode' => $compcode,
                'unit' => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'po_recno' => $request->referral,
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
                ////ni kalu dia amik dari do
                ////amik detail dari do sana, save dkt do detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_do($request->referral,$recno);

                $srcdocno = $request->delordhd_srcdocno;
                $delordno = $request->delordhd_delordno;

                /*////dekat do header sana, save balik delordno dkt situ
                DB::table('material.delordno')
                ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                ->update(['delordno' => $delordno]);*/
            }else{
                throw new Exception("No GRN No.");
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
                    $totalAmount = $this->save_dt_from_othr_do($request->referral,$request->delordhd_recno);

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

            foreach ($request->idno_array as $idno){
                //--- 1. copy delordhd masuk dalam ivtxnhd ---//

                    //1. amik dari delordhd
                $delordhd_obj = DB::table('material.delordhd')
                    ->where('idno', '=', $idno)
                    ->where('compcode', '=' ,session('compcode'))
                    ->first();

                $delordhd_link = DB::table('material.delordhd')
                                ->where('trantype','=','GRN')
                                ->where('docno', '=', $delordhd_obj->srcdocno)
                                ->where('prdept', '=', $delordhd_obj->prdept)
                                ->where('compcode', '=', session('compcode'))
                                ->first();

                $unique_recno = DB::table('material.ivtxnhd')
                                    ->where('compcode',session('compcode'))
                                    ->where('recno',$delordhd_obj->recno)
                                    ->where('trantype',$delordhd_obj->trantype);

                if($unique_recno->exists()){
                    throw new \Exception("ivtxnhd already exists");
                }

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
                        'trandate'=>Carbon::now("Asia/Kuala_Lumpur"), 
                        'trantime'=>Carbon::now("Asia/Kuala_Lumpur"), 
                        'datesupret'=>$delordhd_obj->deliverydate, 
                        'respersonid'=>$delordhd_obj->checkpersonid, 
                        'recstatus'=>$delordhd_obj->recstatus, 
                        'adduser'=>$delordhd_obj->adduser, 
                        'adddate'=>Carbon::now("Asia/Kuala_Lumpur"),
                        'remarks'=>strtoupper($delordhd_obj->remarks)
                    ]);

                //--- 2. loop delorddt untuk masuk dalam ivtxndt ---//
                $delorddt_obj = DB::table('material.delorddt')
                    ->where('delorddt.compcode','=',session('compcode'))
                    ->where('delorddt.recno','=',$delordhd_obj->recno)
                    ->where('delorddt.recstatus','!=','DELETE')
                    ->get();

                    //2. start looping untuk delorddt
                foreach ($delorddt_obj as $value) {
                    if($value->qtyreturned <= 0){
                        continue;
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
                    //     ->where('delorddt.idno','=',$value->idno)
                    //     ->first();
                    // $productcat = $productcat_obj->productcat;
                    $productcat = $this->get_productcat($value->itemcode);
                    
                    $value->expdate = $this->null_date($value->expdate);

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

                    $txnqty = $value->qtyreturned * ($convfactorPOUOM / $convfactorUOM);
                    $netprice = $value->netunitprice * ($convfactorUOM / $convfactorPOUOM);

                    //4. start insert dalam ivtxndt
                    DB::table('material.ivtxndt')
                        ->insert([
                            'compcode' => $value->compcode, 
                            'unit' => session('unit'), 
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
                            'remarks' => strtoupper($value->remarks), 
                            'qtyonhand' => 0, 
                            'batchno' => $value->batchno, 
                            'amount' => round($value->amount, 2), 
                            'trandate' => Carbon::now("Asia/Kuala_Lumpur"), 
                            'trantype' => $delordhd_obj->trantype,
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
                        ->where('StockLoc.Year','=', defaultController::toYear($value->trandate))
                        ->where('StockLoc.UomCode','=',$value->uomcode);

                    //2.kalu ada stockloc, update 
                    if($stockloc_obj->exists()){

                    //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                        $stockloc_arr = (array)$stockloc_obj->first();
                        $month = $this->toMonth($value->trandate);
                        $QtyOnHand = $stockloc_obj->first()->qtyonhand + $txnqty; 
                        $NetMvQty = $stockloc_arr['netmvqty'.$month] + $txnqty;
                        $NetMvVal = $stockloc_arr['netmvval'.$month] + round($value->amount, 2);

                         $stockloc_obj
                            ->update([
                                'QtyOnHand' => $QtyOnHand,
                                'NetMvQty'.$month => $NetMvQty, 
                                'NetMvVal'.$month => $NetMvVal
                            ]);

                    }else{
                    //3.kalu xde stockloc, create stockloc baru

                    }

                    $this->betulkan_stockexp($value->itemcode,$QtyOnHand,$value->deldept);

                    //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//
                    $product_obj = DB::table('material.product')
                        ->where('product.compcode','=',session('compcode'))
                        ->where('product.itemcode','=',$value->itemcode)
                        ->where('product.uomcode','=',$value->uomcode);

                    if($product_obj->first()){ // kalu jumpa
                        // update qtyonhand, avgcost, currprice
                        $product_obj
                            ->update([
                                'qtyonhand' => $QtyOnHand ,
                                // 'avgcost' => $newAvgCost,
                                // 'currprice' => $currprice
                            ]);
                    }

                    $purordhd = DB::table('material.purordhd')
                                    ->where('purordno', '=', $delordhd_link->srcdocno)
                                    ->where('prdept', '=', $delordhd_link->prdept)
                                    ->where('compcode', '=', session('compcode'))
                                    ->first();

                    $purorddt = DB::table('material.purorddt')
                                    ->where('compcode',session('compcode'))
                                    ->where('recno',$purordhd->recno)
                                    ->where('itemcode',$value->itemcode)
                                    ->where('uomcode',$value->uomcode)
                                    ->where('lineno_',$value->lineno_);

                    if($purorddt->exists()){
                        $purorddt = $purorddt->first();
                        $qtyb4 = $purorddt->qtydelivered;
                        $qtyaf = $purorddt->qtydelivered - $txnqty;
                        $qtyou = $purorddt->qtyorder - $qtyaf;

                        DB::table('material.purorddt')
                                    ->where('compcode',session('compcode'))
                                    ->where('recno',$purordhd->recno)
                                    ->where('itemcode',$value->itemcode)
                                    ->where('uomcode',$value->uomcode)
                                    ->where('lineno_',$value->lineno_)
                                    ->update([
                                        'qtydelivered' => $qtyaf,
                                        'qtyoutstand' => $qtyou
                                    ]);
                    }

                } // habis looping untuk delorddt

                //--- 8. change recstatus to posted ---//

                DB::table('material.purordhd')
                    ->where('purordno', '=', $delordhd_link->srcdocno)
                    ->where('prdept', '=', $delordhd_link->prdept)
                    ->where('compcode', '=', session('compcode'))
                    ->update([
                        'recstatus' => 'PARTIAL' 
                    ]);

                DB::table('material.delordhd')
                    ->where('trantype','=','GRT')
                    ->where('recno','=',$delordhd_obj->recno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'postedby' => session('username'),
                        'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'POSTED' 
                    ]);

                DB::table('material.delorddt')
                    ->where('recno','=',$delordhd_obj->recno)
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','!=','DELETE')
                    ->update([
                        'recstatus' => 'POSTED' 
                    ]);
            }
               
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function cancel(Request $request){
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
    }

    public function reopen(Request $request){
        DB::table('material.delordhd')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'postedby' => session('username'),
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN' 
                ]);

            DB::table('material.delorddt')
                ->where('recno','=',$request->recno)
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','!=','DELETE')
                ->update([
                    'recstatus' => 'OPEN' 
                ]);
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

        foreach ($do_dt as $key => $value) {
            ///1. insert detail we get from existing purchase order
            $table = DB::table("material.delorddt");
            $table->insert([
                'compcode' => session('compcode'), 
                'recno' => $recno, 
                'lineno_' => $value->lineno_, 
                'pricecode' => $value->pricecode, 
                'itemcode' => $value->itemcode,
                'uomcode' => $value->uomcode, 
                'pouom' =>$value->pouom,
                'suppcode'=>$value->suppcode,
                'trandate'=>$value->trandate,
                'deldept'=>$value->deldept,
                'deliverydate'=>$value->deliverydate,
                'qtydelivered' => $value->qtydelivered,
                'unitprice' => $value->unitprice, 
                'taxcode' => $value->taxcode, 
                'perdisc' => $value->perdisc, 
                'amtdisc' => $value->amtdisc, 
                'amtslstax' => $value->amtslstax, 
                'amount' => $value->amount, 
                'expdate'=>$value->expdate,
                'batchno'=>$value->batchno,
                'rem_but'=>$value->rem_but,
                'unit' => session('unit'), 
                'adduser' => session('username'), 
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                'recstatus' => 'OPEN', 
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
                //'subamount' => $amount
            ]);

        return $amount;
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
            ->select('dodt.compcode', 'dodt.recno', 'dodt.lineno_', 'dodt.pricecode', 'dodt.itemcode', 'p.description', 'dodt.uomcode', 'dodt.pouom', 'dodt.qtyorder', 'dodt.qtydelivered', 'dodt.qtyreturned','dodt.unitprice', 'dodt.taxcode', 'dodt.perdisc', 'dodt.amtdisc', 'dodt.amtslstax as tot_gst','dodt.netunitprice', 'dodt.totamount','dodt.amount', 'dodt.rem_but AS remarks_button', 'dodt.remarks', 'dodt.recstatus', 'dodt.expdate','dodt.batchno','dodt.unit', 'u.description as uom_desc')
            ->leftJoin('material.productmaster as p', function($join) use ($request){
                        $join = $join->on('dodt.itemcode', '=', 'p.itemcode')
                                ->where('p.compcode','=',session('compcode'));
                    })
            ->leftJoin('material.uom as u', function($join) use ($request){
                        $join = $join->on('dodt.uomcode', '=', 'u.uomcode')
                                ->where('u.compcode','=',session('compcode'));
                    })
            ->where('dodt.compcode','=',session('compcode'))
            ->where('dodt.qtyreturned','!=',0)
            ->where('dodt.recstatus','!=','DELETE')
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

        $cc_acc = [];
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

        $cr_acc=[];
        $db_acc=[];
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

        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm."";

        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('material.goodReturn.goodreturn_pdfmake',compact('delordhd','delorddt','totamt_eng', 'company', 'total_tax', 'total_discamt', 'total_amt','cr_acc','db_acc'));
        
    }

    public function get_productcat($itemcode){
        $query = DB::table('material.product')
                ->select('productcat')
                ->where('compcode','=',session('compcode'))
                ->where('itemcode','=',$itemcode)
                ->first();
        
        return $query->productcat;
    }

    public function betulkan_stockexp($itemcode,$qtyonhand,$deptcode){

        $stockexp = DB::table('material.stockexp')
                        ->where('itemcode',$itemcode)
                        ->where('compcode',session('compcode'))
                        ->where('deptcode',$deptcode);

        if($stockexp->exists()){
            $balqty = $stockexp->sum('balqty');
            $qtyonhand = $qtyonhand;

            if($balqty != $qtyonhand){

                if($qtyonhand>$balqty){
                    $var = $qtyonhand - $balqty;

                    $stockexp_chg = DB::table('material.stockexp')
                                        ->where('compcode',session('compcode'))
                                        ->where('itemcode',$itemcode)
                                        ->where('deptcode',$deptcode)
                                        ->orderBy('idno','desc')
                                        ->first();

                    DB::table('material.stockexp')
                                ->where('idno',$stockexp_chg->idno)
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$itemcode)
                                ->update([
                                    'balqty' => $stockexp_chg->balqty + $var
                                ]);

                    $chg = $stockexp_chg->balqty + $var;

                }else if($qtyonhand<$balqty){
                    $stockexp_chg = DB::table('material.stockexp')
                                        ->where('compcode',session('compcode'))
                                        ->where('itemcode',$itemcode)
                                        ->where('deptcode',$deptcode)
                                        ->orderBy('idno','desc')
                                        ->get();

                    $baki = $qtyonhand;
                    $zerorise = 0;
                    foreach ($stockexp_chg as $obj) {
                        $baki = $baki - $obj->balqty;
                        if($zerorise == 1){
                            DB::table('material.stockexp')
                                ->where('idno',$obj->idno)
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$itemcode)
                                ->update([
                                    'balqty' => 0
                                ]);

                        }else{
                            if($baki == 0){
                                $zerorise = 1;
                                // DB::table('material.stockexp')
                                //     ->where('idno',$obj->idno)
                                //     ->where('compcode','9B')
                                //     ->where('itemcode',$itemcode)
                                //     ->update([
                                //         'balqty' => 0
                                //     ]);

                                // continue;
                            }else if($baki > 0){
                                // DB::table('material.stockexp')
                                //     ->where('idno',$obj->idno)
                                //     ->where('compcode','9B')
                                //     ->where('itemcode',$itemcode)
                                //     ->update([
                                //         'balqty' => 0
                                //     ]);
                            }else if($baki < 0){
                                DB::table('material.stockexp')
                                    ->where('idno',$obj->idno)
                                    ->where('compcode','9B')
                                    ->where('itemcode',$itemcode)
                                    ->update([
                                        'balqty' => $baki + $obj->balqty
                                    ]);
                                $chg = $baki + $obj->balqty;

                                $zerorise = 1;
                            }
                        }
                    }
                }
            }
        }        
    }
}

