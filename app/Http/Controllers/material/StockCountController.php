<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\StockTakeExport;
use App\Imports\StockCountImport;
use Maatwebsite\Excel\Facades\Excel;

class StockCountController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.stockCount.stockCount');
    }

    public function table(Request $request)
    {   
        // DB::enableQueryLog();
        switch($request->action){
            case 'get_table_range':
                return $this->get_table_range($request);
            case 'get_rackno':
                return $this->get_rackno($request);
            case 'import_excel':
                return $this->import_excel($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        // DB::enableQueryLog();

        if($request->action == "import_excel"){
            return $this->import_excel_upload($request);
        }

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
            case 'edit_all':
                return $this->edit_all($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){//ni takguna lorr

        DB::beginTransaction();
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $request_no = $this->request_no('PHYCNT', $request->srcdept);
        $recno = $this->recno('IV','PHYCNT');

        try {

            $dept = DB::table('sysdb.department')
                        ->where('compcode',session('compcode'))
                        ->where('deptcode',$request->srcdept)
                        ->first();

            $unit = $dept->sector;
            $include_expiry = false;

            $table = DB::table("material.phycnthd");

            $array_insert = [
                'docno' => $request_no,
                'recno' => $recno,
                'srcdept' => $request->srcdept,
                'itemfrom' => $request->itemfrom,
                'itemto' => $request->itemto,
                'frzdate' => Carbon::now("Asia/Kuala_Lumpur"),//freeze date
                'frztime' => Carbon::now("Asia/Kuala_Lumpur")->format('h:i:s'),//freeze time
                'phycntdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'phycnttime' => Carbon::now("Asia/Kuala_Lumpur"),
                'respersonid' => session('username'), //freeze user
                'remarks' => strtoupper($request->remarks),
                'rackno' => $request->rackno,
                'compcode' => session('compcode'),
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
            $phycnthd =  DB::table('material.phycnthd')
                            ->where('idno',$idno)
                            ->first();

            $stockloc = DB::table('material.stockloc as s');

            if($include_expiry){
                $stockloc = $stockloc->select('s.itemcode','s.uomcode','p.avgcost','s.qtyonhand','se.expdate','se.batchno');
            }else{
                $stockloc = $stockloc->select('s.itemcode','s.uomcode','p.avgcost','s.qtyonhand');
            }

            $stockloc = $stockloc->leftjoin('material.product as p', function($join) use ($request){
                            $join = $join->on('p.itemcode', '=', 's.itemcode');
                            $join = $join->on('p.uomcode', '=', 's.uomcode');
                            $join = $join->where('p.compcode', '=', session('compcode'));
                            $join = $join->where('p.unit', '=', $unit);
                        });

            if($include_expiry){
                $stockloc = $stockloc->leftjoin('material.stockexp as se', function($join) use ($request){
                                $join = $join->on('se.itemcode', '=', 's.itemcode');
                                $join = $join->on('se.deptcode', '=', 's.deptcode');
                                $join = $join->on('se.uomcode', '=', 's.uomcode');
                                $join = $join->where('se.compcode', '=', session('compcode'));
                                $join = $join->where('se.unit', '=', $unit);
                                $join = $join->on('se.year', '=', 's.year');
                            });
            }

            if(!empty($request->rackno)){
                $stockloc = $stockloc->where('rackno',$request->rackno);
            }

            if(empty($request->itemto)){
                $request->itemto = 'ZZZ';
            }

            $stockloc =  $stockloc
                            ->where('s.compcode',session('compcode'))
                            ->where('s.deptcode',$request->srcdept)
                            ->whereBetween('s.itemcode',[$request->itemfrom,$request->itemto])
                            ->get();

            foreach ($stockloc as $key => $value){

                if($include_expiry){
                    $expdate = $value->expdate;
                    $batchno = $value->batchno;
                }else{
                    $expdate = null;
                    $batchno = null;
                }

                DB::table('material.phycntdt')
                    ->insert([
                        'compcode' => session('compcode'),
                        'srcdept' => $phycnthd->srcdept,
                        'phycntdate' => $phycnthd->phycntdate,
                        'phycnttime' => $phycnthd->phycnttime,
                        'lineno_' => $key,
                        'itemcode' => $value->itemcode,
                        'uomcode' => $value->uomcode,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'unitcost' => $value->avgcost,
                        'thyqty' => $value->qtyonhand,
                        'recno' => $phycnthd->recno,
                        'expdate' => $expdate,
                        'frzdate' => $phycnthd->frzdate,
                        'frztime' => $phycnthd->frztime,
                        'batchno' => $batchno,
                    ]);

               // update frozen = yes at stockloc
                DB::table('material.stockloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('itemcode','=',$value->itemcode)
                    ->where('deptcode','=',$phycnthd->srcdept)
                    ->update([
                        'frozen' => '1',
                    ]);

            }

            $responce = new stdClass();
            $responce->docno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->frzdate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d');
            $responce->frztime = Carbon::now("Asia/Kuala_Lumpur")->format('h:i:s');
            $responce->respersonid = session('username');
            $responce->adduser = session('username');
            $responce->adddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d h:i:s');
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e, 500);
        }
    }

    public function edit_all(Request $request){
        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $obj){

                if($vrqty != 0 && empty($obj['remark'])){
                    throw new \Exception("Remark needed if quantity has variance! itemcode ".$obj['itemcode']." on line no ".$obj['lineno_'], 500);
                }

                DB::table("material.phycntdt")
                    ->where('compcode',session('compcode'))
                    ->where('idno',$obj['idno'])
                    ->update([
                        'phyqty' => $obj['phyqty'],
                        'thyqty' => $obj['thyqty'],
                        'vrqty' => $obj['vrqty'],
                        // 'dspqty' =>  floatval($obj['phyqty']) - floatval($obj['thyqty']),
                        'remark' => $obj['remark'],
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

    public function posted(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno){

                $needheader = false;
                $full_amount = 0;
                $phycntdate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d');
                $phycnttime = Carbon::now("Asia/Kuala_Lumpur")->format('h:i:s');

                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $phycnthd_obj = DB::table('material.phycnthd')
                                ->where('idno','=',$idno)
                                ->first();

                if($phycnthd_obj->recstatus == 'POSTED'){
                    throw new \Exception("Stock Count Already POSTED!");
                }

                $frzdate = $phycnthd_obj->frzdate;
                $frztime = $phycnthd_obj->frztime;

                $unit_ = DB::table('sysdb.department')
                                ->where('compcode',session('compcode'))
                                ->where('deptcode',$phycnthd_obj->srcdept)
                                ->first();

                $unit_ = $unit_->sector;

                // $this->check_sequence_backdated($ivtmphd);

                //-- 2. transfer from ivtmpdt to ivtxndt --//
                $phycntdt_obj = DB::table('material.phycntdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$phycnthd_obj->recno);

                if($phycntdt_obj->exists()){
                    $needheader = true;
                }
                $phycntdt_obj = $phycntdt_obj->get();
                // $this->need_upd_ivreqdt($idno);

                foreach ($phycntdt_obj as $value) {
                    $vrqty =  floatval($value->phyqty) - floatval($value->thyqty);
                    if(floatval($vrqty) == 0){

                        DB::table('material.stockloc')
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',$unit_)
                                ->where('itemcode','=',$value->itemcode)
                                // ->where('uomcode','=',$value->uomcode)
                                ->where('deptcode','=',$value->srcdept)
                                ->where('year', '=', Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                                ->update([
                                    'frozen' => '0',
                                ]);

                        continue;
                    }

                    $ivdspdt = DB::table('material.ivdspdt')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                ->where('issdept',$value->srcdept)
                                ->where('trandate','>=',$frzdate)
                                ->where('updtime','>=',$frztime);

                    if($ivdspdt->exists()){
                        $dspqty = DB::table('material.ivdspdt')
                                    ->where('compcode',session('compcode'))
                                    ->where('itemcode',$value->itemcode)
                                    ->where('issdept',$value->srcdept)
                                    ->where('trandate','>=',$frzdate)
                                    ->where('updtime','>=',$frztime)
                                    ->sum('txnqty');
                    }else{
                        $dspqty = 0;
                    }
                    $vrqty =  floatval($value->phyqty) - floatval($value->thyqty) + Floatval($value->dspqty);

                    $obj_acc = $this->get_acc($value,$phycnthd_obj);

                    $craccno = $obj_acc->craccno;
                    $crccode = $obj_acc->crccode;
                    $draccno = $obj_acc->draccno;
                    $drccode = $obj_acc->drccode;
                    $amount = floatval($vrqty) * floatval($value->unitcost);
                    $full_amount = floatval($full_amount) + floatval($amount);

                    DB::table('material.ivtxndt')
                        ->insert([
                            'compcode' => session('compcode'), 
                            'unit' => session('unit'), 
                            'recno' => $value->recno, 
                            'lineno_' => $value->lineno_, 
                            'itemcode' => $value->itemcode, 
                            'uomcode' => $value->uomcode,
                            'uomcoderecv' => $value->uomcode,  
                            'txnqty' => $vrqty, 
                            'netprice' => $value->unitcost, 
                            'adduser' => session('username'), 
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                            'upduser' => session('username'), 
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'TranType' => 'PHYCNT',
                            'deptcode'  => $value->srcdept,
                            // 'productcat' => $productcat, 
                            'draccno' => $draccno, 
                            'drccode' => $drccode, 
                            'craccno' => $craccno, 
                            'crccode' => $crccode, 
                            'expdate' => $value->expdate, 
                            'qtyonhand' => $value->phyqty,
                            // 'qtyonhandrecv' => $value->phyqty,  
                            'batchno' => $value->batchno, 
                            'amount' => $amount, 
                            'gstamount' => 0.00, 
                            'totamount' => $amount, 
                            'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'sndrcv' => $value->srcdept,
                        ]);


                    //-- 4. posting stockloc OUT --//

                    //1. amik stockloc
                    $stockloc_obj = DB::table('material.StockLoc')
                        ->where('StockLoc.unit','=',$unit_)
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$value->srcdept)
                        ->where('StockLoc.ItemCode','=',$value->itemcode)
                        ->where('StockLoc.Year','=', defaultController::toYear($phycntdate))
                        ->where('StockLoc.UomCode','=',$value->uomcode);

                    $stockloc_first = $stockloc_obj->first();

                    //2.kalu ada stockloc, update 
                    if($stockloc_obj->exists()){

                    //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                        $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
                        $month = defaultController::toMonth($phycntdate);
                        $QtyOnHand = $stockloc_first->qtyonhand + $vrqty; 
                        $NetMvQty = $stockloc_arr['netmvqty'.$month] + floatval($vrqty);
                        $NetMvVal = $stockloc_arr['netmvval'.$month] + $amount;

                        DB::table('material.StockLoc')
                            ->where('StockLoc.unit','=',$unit_)
                            ->where('StockLoc.CompCode','=',session('compcode'))
                            ->where('StockLoc.DeptCode','=',$value->srcdept)
                            ->where('StockLoc.ItemCode','=',$value->itemcode)
                            ->where('StockLoc.Year','=', defaultController::toYear($phycntdate))
                            ->where('StockLoc.UomCode','=',$value->uomcode)
                            ->update([
                                'QtyOnHand' => $QtyOnHand,
                                'NetMvQty'.$month => $NetMvQty, 
                                'NetMvVal'.$month => $NetMvVal
                            ]);

                    //4. tambah expdate, kalu ada batchno
                        $expdate_obj = DB::table('material.stockexp')
                            ->where('compcode','=',session('compcode'))
                            ->where('unit','=',$unit_)
                            ->where('Year','=',defaultController::toYear($phycntdate))
                            ->where('DeptCode','=',$value->srcdept)
                            ->where('ItemCode','=',$value->itemcode)
                            ->where('UomCode','=',$value->uomcode)
                            ->where('BatchNo','=',$value->batchno);

                        if($value->expdate == NULL){ //ni kalu expdate dia xde @ NULL
                            $expdate_obj
                                // ->where('expdate','=',$value->expdate)
                                ->orderBy('expdate', 'asc');
                        }else{ // ni kalu expdate dia exist
                             $expdate_obj
                                ->where('expdate','<=',$value->expdate)
                                ->orderBy('expdate', 'asc');
                        }

                        $expdate_first = $expdate_obj->first();

                        if($expdate_obj->exists()){
                            
                            // if($expdate_obj->count() > 1){
                            //     $year_stockexp = defaultController::toYear($phycntdate);
                            //     dd("lagi besr dari satu, year $year_stockexp , dept $value->srcdept , itemcode $value->itemcode , uomcode $value->uomcode , batchno $value->batchno , expdate $value->expdate");

                            //     DB::table('material.stockexp')
                            //         ->where('Year','=',defaultController::toYear($phycntdate))
                            //         ->where('DeptCode','=',$value->srcdept)
                            //         ->where('ItemCode','=',$value->itemcode)
                            //         ->where('UomCode','=',$value->uomcode)
                            //         ->where('BatchNo','=',$value->batchno)
                            //         ->where('idno','!=',$expdate_first->idno)
                            //         ->delete();
                            // }

                            $balqty_new = $expdate_first->balqty + floatval($vrqty) - $dspqty;

                            DB::table('material.stockexp')
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',$unit_)
                                ->where('Year','=',defaultController::toYear($phycntdate))
                                ->where('DeptCode','=',$value->srcdept)
                                ->where('ItemCode','=',$value->itemcode)
                                ->where('UomCode','=',$value->uomcode)
                                ->where('BatchNo','=',$value->batchno)
                                ->where('idno','=',$expdate_first->idno)
                                    ->update([
                                    'balqty' => $balqty_new
                                ]);
                        }else{ 
                            DB::table('material.stockexp')
                                ->insert([
                                    'compcode' => session('compcode'),
                                    'unit' => $unit_,
                                    'Year' => defaultController::toYear($phycntdate),
                                    'DeptCode' => $value->srcdept,
                                    'ItemCode' => $value->itemcode,
                                    'UomCode' => $value->uomcode,
                                    'BatchNo' => $value->batchno,
                                    'expdate' => $value->expdate,
                                    'balqty' => $QtyOnHand
                                ]);
                        }

                    }else{
                        //ni utk kalu xde stockloc
                        throw new \Exception("Stockloc not exist for item: ".$value->itemcode." | deptcode: ".$value->srcdept." | year: ".defaultController::toYear($phycntdate)." | uomcode: ".$value->uomcode);
                    }

                    //-- 6. posting product -> update qtyonhand, avgcost, currprice --//

                    $product_obj = DB::table('material.product')
                        ->where('product.unit','=',$unit_)
                        ->where('product.compcode','=',session('compcode'))
                        ->where('product.itemcode','=',$value->itemcode)
                        ->where('product.uomcode','=',$value->uomcode);

                    if($product_obj->exists()){ // kalu jumpa
                        $product_obj = $product_obj->first();

                        $month = defaultController::toMonth($phycntdate);
                        $txnqty = $vrqty;

                        $OldQtyOnHand = $product_obj->qtyonhand;
                        $Oldavgcost = $product_obj->avgcost;
                        $OldAmount = $OldQtyOnHand * $Oldavgcost;
                        $NewAmount = $Oldavgcost * $txnqty;

                        $newqtyonhand = $OldQtyOnHand + $txnqty;
                        // $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);


                        // update qtyonhand, avgcost, currprice
                        $product_obj = DB::table('material.product')
                            ->where('product.unit','=',$unit_)
                            ->where('product.compcode','=',session('compcode'))
                            ->where('product.itemcode','=',$value->itemcode)
                            ->where('product.uomcode','=',$value->uomcode)
                            ->update([
                                'qtyonhand' => $newqtyonhand,
                                // 'avgcost' => $newAvgCost,
                            ]);

                    }else{
                        //ni utk kalu xde product
                        throw new \Exception("Product not exist for item: ".$value->itemcode." | deptcode: ".$value->srcdept." |uomcode: ".$value->uomcode);
                    }

                    //--- 7. posting GL ---//

                    //amik yearperiod
                    $yearperiod = $this->getyearperiod($phycntdate);
     
                    //1. buat gltran
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => session('compcode'),
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'auditno' => $value->recno,
                            'lineno_' => $value->lineno_,
                            'source' => 'IV',
                            'trantype' => 'PHYCNT',
                            'reference' => $value->srcdept .' '. $value->recno,
                            'description' => $value->srcdept,
                            'postdate' => $phycntdate,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $drccode,
                            'dracc' => $draccno,
                            'crcostcode' => $crccode,
                            'cracc' => $craccno,
                            'amount' => $amount,
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
                                'actamount'.$yearperiod->period => $amount + $this->gltranAmount,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $drccode,
                                'glaccount' => $draccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $amount,
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
                                'actamount'.$yearperiod->period => $this->gltranAmount - $amount,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $crccode,
                                'glaccount' => $craccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => -$amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                    DB::table('material.stockloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('unit','=',$unit_)
                            ->where('itemcode','=',$value->itemcode)
                            ->where('uomcode','=',$value->uomcode)
                            ->where('deptcode','=',$value->srcdept)
                            ->where('year', '=', $yearperiod->year)
                            ->update([
                                'frozen' => '0',
                            ]);

                    DB::table('material.phycntdt')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$value->idno)
                            ->update([
                                'dspqty' => $dspqty,
                            ]);
                }

                //--- 8. change recstatus to posted ---//
                if($needheader){
                    DB::table("material.IvTxnHd")
                        ->insert([
                            'AddDate'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'AddUser'  => session('username'),
                            'Amount'   => $full_amount,
                            'CompCode' => session('compcode'),
                            'unit'     => session('unit'),
                            // 'DateActRet'   => $ivtmphd->dateactret,
                            // 'DateSupRet'   => $ivtmphd->datesupret,
                            // 'DocNo'    => $ivtmphd->docno,
                            // 'IvReqNo'  => $ivtmphd->ivreqno,
                            'RecNo'    => $phycnthd_obj->recno,
                            'RecStatus'    => 'POSTED',
                            // 'Reference'    => $ivtmphd->reference,
                            'Remarks'  => $phycnthd_obj->remarks,
                            'ResPersonId'  => $phycnthd_obj->respersonid,
                            // 'SndRcv'   => $phycnthd_obj->srcdept,
                            // 'SndRcvType'   => $ivtmphd->sndrcvtype,
                            'Source'   => 'IV',
                            // 'SrcDocNo' => $ivtmphd->srcdocno,
                            'TranDate' => $phycntdate,
                            'TranTime' => $phycnttime,
                            'TranType' => 'PHYCNT',
                            'TxnDept'  => $phycnthd_obj->srcdept,
                            'UpdDate'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'UpdTime'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'UpdUser'  => session('username')
                        ]);

                    DB::table('material.phycnthd')
                            ->where('idno','=',$idno)
                            ->update([
                                'recstatus' => 'POSTED',
                                'phycntdate' => $phycntdate,
                                'phycnttime' => $phycnttime,
                                'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                                'upduser'  => session('username'),
                            ]);

                    DB::table('material.phycntdt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$phycnthd_obj->recno)
                            ->update([
                                'phycntdate' => $phycntdate,
                                'phycnttime' => $phycnttime,
                                'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                                'upduser'  => session('username'),
                            ]);


                }
            }
            // $queries = DB::getQueryLog();
            // dump($queries);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e, 500);
        }
    }

    public function cancel(Request $request){
        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno){
                $phycntdate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d');
                $phycnttime = Carbon::now("Asia/Kuala_Lumpur")->format('h:i:s');

                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $phycnthd_obj = DB::table('material.phycnthd')
                                ->where('idno','=',$idno)
                                ->first();

                $dept = DB::table('sysdb.department')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$phycnthd_obj->srcdept)
                            ->first();

                $unit = $dept->sector;

                // $this->check_sequence_backdated($ivtmphd);

                //-- 2. transfer from ivtmpdt to ivtxndt --//
                $phycntdt_obj = DB::table('material.phycntdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$phycnthd_obj->recno);

                $phycntdt_obj = $phycntdt_obj->get();
                $yearperiod = $this->getyearperiod($phycntdate);

                foreach ($phycntdt_obj as $value) {

                    DB::table('material.stockloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('unit','=',$unit)
                            ->where('itemcode','=',$value->itemcode)
                            ->where('uomcode','=',$value->uomcode)
                            ->where('deptcode','=',$value->srcdept)
                            ->where('year', '=', $yearperiod->year)
                            ->update([
                                'frozen' => '0',
                            ]);
                }

                DB::table('material.phycnthd')
                        ->where('idno','=',$idno)
                        ->update([
                            'recstatus' => 'CANCELLED',
                            'phycntdate' => $phycntdate,
                            'phycnttime' => $phycnttime,
                            'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'upduser'  => session('username'),
                        ]);

                DB::table('material.phycntdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$phycnthd_obj->recno)
                        ->update([
                            'phycntdate' => $phycntdate,
                            'phycnttime' => $phycnttime,
                            'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'upduser'  => session('username'),
                        ]);


            }
            // $queries = DB::getQueryLog();
            // dump($queries);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e, 500);
        }
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
    
    public function get_table_range(Request $request){
        
        $table = DB::table('debtor.phycntdt AS pd')
                        ->select('compcode','srcdept','phycntdate','phycnttime','lineno_','itemcode','uomcode','adduser','adddate','upduser','upddate','unitcost','phyqty','thyqty','recno','expdate','updtime','stktime','frzdate','frztime','dspqty','batchno');

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });
        
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->filterdate)){
            $table = $table->where('db.entrydate','>',$request->filterdate[0]);
            $table = $table->where('db.entrydate','<',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.invno','like',$request->searchVal[0]);
                    });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                    });
            }
            
        }
        
        if(!empty($request->sidx)){
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    $value_ = substr_replace($value,"db.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('db.idno','DESC');
        }
        
        $paginate = $table->paginate($request->rows);
        
        // foreach ($paginate->items() as $key => $value) {
        //     $apactdtl = DB::table('finance.apactdtl')
        //                 ->where('source','=',$value->apacthdr_source)
        //                 ->where('trantype','=',$value->apacthdr_trantype)
        //                 ->where('auditno','=',$value->apacthdr_auditno);
        
        //     // if($apactdtl->exists()){
        //     //     $value->apactdtl_outamt = $apactdtl->sum('amount');
        //     // }else{
        //     //     $value->apactdtl_outamt = $value->apacthdr_outamount;
        //     // }
        
        //     // $apalloc = DB::table('finance.apalloc')
        //     //             ->select('allocdate')
        //     //             ->where('refsource','=',$value->apacthdr_source)
        //     //             ->where('reftrantype','=',$value->apacthdr_trantype)
        //     //             ->where('refauditno','=',$value->apacthdr_auditno)
        //     //             ->where('recstatus','!=','CANCELLED')
        //     //             ->orderBy('idno', 'desc');
        
        //     // if($apalloc->exists()){
        //     //     $value->apalloc_allocdate = $apalloc->first()->allocdate;
        //     // }else{
        //     //     $value->apalloc_allocdate = '';
        //     // }
        // }
        
        //////////paginate/////////
        
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
    
    public function get_rackno(Request $request){
        
        $table = DB::table('material.stockloc')
                    ->select('rackno')
                    ->where('deptcode', '=', $request->filterVal[0])
                    ->where('recstatus','=','ACTIVE')
                    ->where('compcode','=',session('compcode'))
                    ->where('year', '=', $request->filterVal[3])
                    ->whereNotNull('rackno')
                    ->where('rackno','<>','')
                    ->distinct('rackno');
        
        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            
            $count = array_count_values($searchCol_array);
            // dump($count);
            
            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);
                
                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }
        
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
    
    public function showpdf(Request $request){
        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }
        
        $phycnthd = DB::table('material.phycnthd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->first();

        $dept = DB::table('sysdb.department')
                    ->where('compcode',session('compcode'))
                    ->where('deptcode',$phycnthd->srcdept)
                    ->first();

        $unit = $dept->sector;

        $phycntdt = DB::table('material.phycntdt AS pdt')
            ->select('pdt.idno','pdt.compcode','pdt.srcdept','pdt.phycntdate','pdt.phycnttime','pdt.lineno_','pdt.itemcode','pdt.uomcode','pdt.adduser','pdt.adddate','pdt.upduser','pdt.upddate','pdt.unitcost','pdt.phyqty','pdt.thyqty','pdt.recno','pdt.expdate','pdt.updtime','pdt.stktime','pdt.frzdate','pdt.frztime','pdt.dspqty','pdt.remark','pdt.batchno','p.description')
            ->leftJoin('material.product as p', function($join) use ($request,$unit){
                        $join = $join->on('p.itemcode', '=', 'pdt.itemcode')
                                     ->on('p.uomcode', '=', 'pdt.uomcode')
                                     // ->where('p.unit','=',$unit)
                                     ->where('p.compcode','=',session('compcode'));
                    })
            ->where('pdt.compcode','=',session('compcode'))
            ->where('pdt.recno','=',$recno)
            ->get();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('material.stockCount.stockCount_pdfmake',compact('phycnthd','phycntdt','company'));        
    }

    public function get_acc($value,$phycnthd_obj){

        $productcat_obj = DB::table('material.product')
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode)
            ->first();

        $category_obj = DB::table('material.category')
            ->where('category.compcode','=',session('compcode'))
            ->where('category.catcode','=',$productcat_obj->productcat)
            ->first();

        $dept_obj = DB::table('sysdb.department')
            ->where('department.compcode','=',session('compcode'))
            ->where('department.deptcode','=',$value->srcdept)
            ->first();

        $draccno = $category_obj->stockacct;
        $drccode = $dept_obj->costcode;
        $craccno = $category_obj->expacct;
        $crccode = $dept_obj->costcode;

        $responce = new stdClass();
        $responce->craccno = $craccno;
        $responce->crccode = $crccode;
        $responce->draccno = $draccno;
        $responce->drccode = $drccode;

        return $responce;
    }

    public function showExcel(Request $request){
        return Excel::download(new StockTakeExport($request->recno), 'StockTakeExport.xlsx');
    }

    public function import_excel(Request $request){
        return view('material.stockCount.import_excel'); 
    }

    public function import_excel_upload(Request $request){
        
        DB::beginTransaction();
        try {

            $type = $request->file('file')->getClientMimeType();
            $filename = $request->file('file')->getClientOriginalName();

            $phycnthd = DB::table('material.phycnthd')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$request->recno)
                            ->first();

            $file_path = $request->file('file')->store('attachment', \config('get_config.ATTACHMENT_UPLOAD'));
            DB::table('material.phycntupld')
                ->insert([
                    'compcode' => session('compcode'),
                    'resulttext' => $filename,
                    'attachmentfile' => $file_path,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'auditno' => $phycnthd->recno,
                    'hd_id' => $phycnthd->idno,
                    'hd_dept' => $request->srcdept,
                    'hd_date' => $request->phycntdate,
                    'hd_time' => $request->phycnttime
                ]);

            Excel::import(new StockCountImport($request->recno), request()->file('file'));

            DB::commit();

            $responce = new stdClass();
            $responce->res = 'success';
            $responce->msg = 'Process Sucess';

            echo json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
}

