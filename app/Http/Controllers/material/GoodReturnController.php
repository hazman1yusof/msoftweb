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
            default:
                return 'error happen..';
        }
    }

    public function get_table_default(Request $request){
        $table =  DB::table('material.delordhd');
        $table = $table->select($this->fixPost($request->field,"_"));

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

            $request_no = $this->request_no('GRT', $request->delordhd_deldept);
            $recno = $this->recno('PUR','DO');
            // $request_no = 0;
            // $recno = 0;
            $compcode = 'DD';

            $table = DB::table("material.delordhd");

            $array_insert = [
                'trantype' => 'GRT', 
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
                    $productcat_obj = DB::table('material.delorddt')
                        ->select('product.productcat')
                        ->join('material.product', function($join) use ($request){
                            $join = $join->on('delorddt.itemcode', '=', 'product.itemcode');
                            $join = $join->on('delorddt.uomcode', '=', 'product.uomcode');
                        })
                        ->where('delorddt.compcode','=',session('compcode'))
                        ->where('product.groupcode','=','Stock')
                        ->where('delorddt.idno','=',$value->idno)
                        ->first();
                    $productcat = $productcat_obj->productcat;
                    
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
                            'amount' => $value->amount, 
                            'trandate' => $value->trandate, 
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
                        $QtyOnHand = $stockloc_obj->first()->qtyonhand - $txnqty; 
                        $NetMvQty = $stockloc_arr['netmvqty'.$month] - $txnqty;
                        $NetMvVal = $stockloc_arr['netmvval'.$month] - ($netprice * $txnqty);

                         $stockloc_obj
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
                    // $stockexp_obj = DB::table('material.stockexp')
                    //     ->where('stockexp.compcode','=',session('compcode'))
                    //     ->where('stockexp.deptcode','=',$value->deldept)
                    //     ->where('stockexp.itemcode','=',$value->itemcode)
                    //     ->where('stockexp.expdate','=',$value->expdate)
                    //     ->where('stockexp.year','=', defaultController::toYear($value->trandate))
                    //     ->where('stockexp.uomcode','=',$value->uomcode)
                    //     ->where('stockexp.batchno','=',$value->batchno);

                    $expdate_obj = DB::table('material.stockexp')
                        ->where('compcode',session('compcode'))
                        ->where('Year','=',defaultController::toYear($value->trandate))
                        ->where('DeptCode','=',$value->deldept)
                        ->where('ItemCode','=',$value->itemcode)
                        ->where('UomCode','=',$value->uomcode)
                        ->orderBy('expdate', 'asc');

                    //2.kalu ada Stock Expiry, update

                    if($expdate_obj->exists()){

                        $expdate_get = $expdate_obj->get();
                        $txnqty_ = $txnqty;
                        $balqty = 0;
                        foreach ($expdate_get as $value2) {
                            $balqty = $value2->balqty;
                            if($txnqty_-$balqty>0){
                                $txnqty_ = $txnqty_-$balqty;
                                DB::table('material.stockexp')
                                    ->where('idno','=',$value2->idno)
                                    ->update([
                                        'balqty' => '0'
                                    ]);
                            }else{
                                $balqty = $balqty-$txnqty_;
                                DB::table('material.stockexp')
                                    ->where('idno','=',$value2->idno)
                                    ->update([
                                        'balqty' => $balqty
                                    ]);
                                break;
                            }
                        }

                    }else{
                   
                        //3.kalu xde Stock Expiry, buat baru
                        $BalQty = -$txnqty;

                        DB::table('material.stockexp')
                            ->insert([
                                'compcode' => session('compcode'), 
                                'unit' => session('unit'), 
                                'deptcode' => $value->deldept, 
                                'itemcode' => $value->itemcode, 
                                'uomcode' => $value->uomcode, 
                                'balqty' => $BalQty, 
                                'adduser' => session('username'), 
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                                'upduser' => session('username'), 
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                                'year' => defaultController::toYear($value->trandate)
                            ]);
                    }

                    //--- 5. posting product -> update qtyonhand, avgcost, currprice ---//
                    $product_obj = DB::table('material.product')
                        ->where('product.compcode','=',session('compcode'))
                        ->where('product.itemcode','=',$value->itemcode)
                        ->where('product.uomcode','=',$value->uomcode);

                    if($product_obj->first()){ // kalu jumpa
                        $month = defaultController::toMonth($value->trandate);
                        $OldQtyOnHand = $product_obj->first()->qtyonhand;
                        $currprice = $netprice;
                        $Oldavgcost = $product_obj->first()->avgcost;
                        $OldAmount = $OldQtyOnHand * $Oldavgcost;
                        $NewAmount = $netprice * $txnqty;

                        $newqtyonhand = $OldQtyOnHand - $txnqty;
                        if($newqtyonhand == 0){
                            $newAvgCost = 0;
                        }else{
                            $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand - $txnqty);
                        }

                        // update qtyonhand, avgcost, currprice
                        $product_obj
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
                            'trantype' => $delordhd_obj->trantype,
                            'reference' => $ivtxnhd_obj->txndept .' '. $ivtxnhd_obj->docno,
                            'description' => $ivtxnhd_obj->sndrcv,
                            'postdate' => $ivtxnhd_obj->trandate,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $row_sysparam->pvalue1,
                            'dracc' => $row_sysparam->pvalue2,
                            'crcostcode' => $row_dept->costcode,
                            'cracc' => $row_cat->stockacct,
                            'amount' => $value->amount,
                            'idno' => $value->itemcode
                        ]);

                    //2. check glmastdtl utk debit, kalu ada update kalu xde create
                    if($this->isGltranExist($row_sysparam->pvalue1,$row_sysparam->pvalue2,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$row_sysparam->pvalue1)
                            ->where('glaccount','=',$row_sysparam->pvalue2)
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
                                'costcode' => $row_sysparam->pvalue1,
                                'glaccount' => $row_sysparam->pvalue2,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                    //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
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
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $row_dept->costcode,
                                'glaccount' => $row_cat->stockacct,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => -$value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
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
                                'drcostcode' => $queryACC->pvalue1, 
                                'dracc' => $queryACC->pvalue2,
                                'crcostcode' => $drcostcode_,
                                'cracc' => $dracc_,
                                'amount' => $value->amtslstax,
                                'idno' => $value->itemcode
                            ]);

                        //2. check glmastdtl utk debit, kalu ada update kalu xde create
                        if($this->isGltranExist($queryACC->pvalue1, $queryACC->pvalue2, $yearperiod->year,$yearperiod->period)){
                            DB::table('finance.glmasdtl')
                                ->where('compcode','=',session('compcode'))
                                ->where('costcode','=',$queryACC->pvalue1)
                                ->where('glaccount','=',$queryACC->pvalue2)
                                ->where('year','=',$yearperiod->year)
                                ->update([
                                    'upduser' => session('username'),
                                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                    'actamount'.$yearperiod->period => $value->amtslstax + $this->gltranAmount,
                                    'recstatus' => 'ACTIVE'
                                ]);
                        }else{
                            DB::table('finance.glmasdtl')
                                ->insert([
                                    'compcode' => session('compcode'),
                                    'costcode' => $queryACC->pvalue1,
                                    'glaccount' => $queryACC->pvalue2,
                                    'year' => $yearperiod->year,
                                    'actamount'.$yearperiod->period => $value->amtslstax,
                                    'adduser' => session('username'),
                                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                    'recstatus' => 'ACTIVE'
                                ]);
                        }

                        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                        if($this->isGltranExist($drcostcode_, $dracc_, $yearperiod->year,$yearperiod->period)){
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
                            DB::table('finance.glmasdtl')
                                ->insert([
                                    'compcode' => session('compcode'),
                                    'costcode' => $drcostcode_,
                                    'glaccount' => $dracc_,
                                    'year' => $yearperiod->year,
                                    'actamount'.$yearperiod->period => -$value->amtslstax,
                                    'adduser' => session('username'),
                                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                    'recstatus' => 'ACTIVE'
                                ]);
                        }
                    }

                } // habis looping untuk delorddt

                //--- 8. change recstatus to posted ---//

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
                'adduser' => session('username'), 
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                'recstatus' => 'ACTIVE', 
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
}

