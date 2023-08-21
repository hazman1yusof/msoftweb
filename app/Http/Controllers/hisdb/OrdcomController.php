<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class OrdcomController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgcode";
    }

    public function table(Request $request){   
       switch($request->action){
            case 'chgcode_table':
                $data = $this->chgcode_table($request);
                break;

            case 'ordcom_table':
                return $this->ordcom_table($request);
                break;

            default:
                $data = 'error happen..';
                break;
        }


        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
    }

    public function show(Request $request){   
        return view('hisdb.ordcom.ordcom');
    }

    public function form(Request $request){   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_chargetrx':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'saveForm_ordcom':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }


            case 'order_entry':

                switch($request->oper){
                    case 'add':
                        return $this->order_entry_add($request);
                    case 'edit':
                        // return $this->order_entry_edit($request);
                    default:
                        return 'error happen..';
                }

            case 'get_table_ordcom':
                return $this->get_table_ordcom($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {
            
            DB::table('hisdb.chargetrx')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'trxtype' => 'OE',
                        'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'chgcode' => $request->chgcode,
                        'instruction' => $request->ins_desc,
                        'doscode' => $request->dos_desc,
                        'frequency' => $request->fre_desc,
                        'drugindicator' => $request->dru_desc,
                        'remarks' => $request->remarks,
                        'billflag' => '0',
                        'unitprce' => $request->unitprice,
                        'amount' => $request->amount,
                        'quantity' => $request->quantity,
                        'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'adduser' => session('username')
                    ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {
            
            DB::table('hisdb.chargetrx')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ordcom,
                        'episno' => $request->episno_ordcom,
                        'trxtype' => $request->trxtype,
                        'docref' => $request->docref,
                        'trxdate' => $request->trxdate,
                        'chgcode' => $request->chgcode,
                        'billcode' => $request->billcode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $request->chg_class,
                        'chgtype' => $request->chgtype,
                        'trxtime' => $request->trxtime,
                        'chggroup' => $request->chggroup,
                        'dracccode' => $request->dracccode,
                        'cracccode' => $request->cracccode,
                        'taxcode' => $request->taxcode,
                      
                        //'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::table('hisdb.chargetrx')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ordcom,
                        'episno' => $request->episno_ordcom,
                        'trxtype' => $request->trxtype,
                        'docref' => $request->docref,
                        'trxdate' => $request->trxdate,
                        'chgcode' => $request->chgcode,
                        'billcode' => $request->billcode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $request->chg_class,
                        'chgtype' => $request->chgtype,
                        'trxtime' => $request->trxtime,
                        'chggroup' => $request->chggroup,
                        'dracccode' => $request->dracccode,
                        'cracccode' => $request->cracccode,
                        'taxcode' => $request->taxcode,
                        
                        //'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();
        try {

            DB::table('hisdb.chargetrx')
                ->where('idno','=',$request->idno)
                ->update([  
                    'recstatus' => 'DEACTIVE',
                    'deluser' => strtoupper(session('username')),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function chgcode_table(Request $request){
        $sysparam = DB::table('sysdb.sysparam')
                        ->select('pvalue1')
                        ->where('source','=','OE')
                        ->where('trantype','=','NURSING')
                        ->first();

        $data = DB::table('hisdb.chgmast as m')
                    ->select('m.chgcode','m.description as desc','m.chggroup','g.grpcode','g.description','p.amt1')
                    ->leftJoin('hisdb.chggroup as g', 'm.chggroup', '=', 'g.grpcode')
                    ->leftJoin('hisdb.chgprice as p', 'm.chgcode', '=', 'p.chgcode')
                    ->whereIn('chggroup', explode( ',', $sysparam->pvalue1 ))
                    ->where('m.recstatus','=','ACTIVE')
                    ->where('m.recstatus','=','ACTIVE')
                    ->where('m.compcode','=',session('compcode'))
                    ->orderBy('m.chggroup', 'desc')
                    ->get();

        return $data;
    }

    public function ordcom_table(Request $request){
        if($request->rows == null){
            $request->rows = 100;
        }

        $table_chgtrx = DB::table('hisdb.chargetrx as trx')
                    ->select('trx.auditno','trx.compcode','trx.idno','trx.mrn','trx.episno','trx.epistype','trx.trxtype','trx.docref','trx.trxdate','trx.chgcode','trx.billcode','trx.costcd','trx.revcd','trx.mmacode','trx.billflag','trx.billdate','trx.billtype','trx.doctorcode','trx.chg_class','trx.unitprce','trx.quantity','trx.amount','trx.trxtime','trx.chggroup','trx.qstat','trx.dracccode','trx.cracccode','trx.arprocess','trx.taxamount','trx.billno','trx.invno','trx.uom','trx.billtime','trx.invgroup','trx.reqdept as deptcode','trx.issdept','trx.invcode','trx.resulttype','trx.resultstatus','trx.inventory','trx.updinv','trx.invbatch','trx.doscode','trx.duration','trx.instruction','trx.discamt','trx.disccode','trx.pkgcode','trx.remarks','trx.frequency','trx.ftxtdosage','trx.addinstruction','trx.qtyorder','trx.ipqueueno','trx.itemseqno','trx.doseqty','trx.freqqty','trx.isudept','trx.qtyissue','trx.durationcode','trx.reqdoctor','trx.unit','trx.agreementid','trx.chgtype','trx.adduser','trx.adddate','trx.lastuser','trx.lastupdate','trx.daytaken','trx.qtydispense','trx.takehomeentry','trx.latechargesentry','trx.taxcode','trx.recstatus','trx.drugindicator','trx.id','trx.patmedication','trx.mmaprice','pt.avgcost as cost_price')
                    ->where('trx.mrn' ,'=', $request->mrn)
                    ->where('trx.episno' ,'=', $request->episno)
                    ->where('trx.compcode','=',session('compcode'))
                    ->where('trx.chggroup',$request->chggroup)
                    ->where('trx.recstatus','<>','DELETE')
                    ->orderBy('trx.adddate', 'desc');

        $table_chgtrx = $table_chgtrx->leftjoin('material.product as pt', function($join) use ($request){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'trx.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'trx.uom');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

        //////////paginate/////////

        $paginate = $table_chgtrx->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_chgtrx->toSql();
        $responce->sql_bind = $table_chgtrx->getBindings();
        return json_encode($responce);
    }

    public function order_entry_add(Request $request){
        
        DB::beginTransaction();
        
        try {
            $recno = $this->recno('OE','IN');

            $chgmast = DB::table("hisdb.chgmast")
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',$request->chgcode)
                    ->where('uom','=',$request->uom)
                    ->first();
            
            $updinv = ($chgmast->invflag == '1')? 1 : 0;

            $insertGetId = DB::table("hisdb.chargetrx")
                    ->insertGetId([
                        'auditno' => $recno,
                        'compcode'  => session('compcode'),
                        'mrn'  => $request->mrn,
                        'episno'  => $request->episno,
                        'trxdate' => $request->trxdate,
                        'chgcode' => $request->chgcode,
                        'billflag' => 0,
                        'chg_class' => $chgmast->chgclass,
                        'unitprce' => $request->unitprce,
                        'quantity' => $request->quantity,
                        'amount' => $request->amount,
                        'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'chggroup' => $chgmast->chggroup,
                        'taxamount' => $request->taxamt,
                        'uom' => $request->uom,
                        'invgroup' => $chgmast->invgroup,
                        'reqdept' => $request->deptcode,
                        'issdept' => $request->deptcode,
                        'invcode' => $chgmast->chggroup,
                        'inventory' => $updinv,
                        'updinv' =>  $updinv,
                        'discamt' => $request->discamt,
                        'qtyorder' => $request->quantity,
                        'qtyissue' => $request->quantity,
                        'unit' => session('unit'),
                        'chgtype' => $chgmast->chgtype,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'qtydispense' => $request->quantity,
                        'taxcode' => $request->taxcode,
                        'recstatus' => 'POSTED',
                        'drugindicator' => $request->drugindicator,
                        'frequency' => $request->frequency,
                        'ftxtdosage' => $request->ftxtdosage,
                        'addinstruction' => $request->addinstruction,
                    ]);
            
            $chargetrx_obj = db::table('hisdb.chargetrx')
                            ->where('compcode',session('compcode'))
                            ->where('id', '=', $insertGetId)
                            ->first();
            
            $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$request->uom)
                            ->where('itemcode','=',$request->chgcode);
            
            if($product->exists()){
                $stockloc = DB::table('material.stockloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$request->uom)
                        ->where('itemcode','=',$request->chgcode)
                        ->where('deptcode','=',$request->deptcode)
                        ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                
                if($stockloc->exists()){
                    $stockloc = $stockloc->first();
                }else{
                    throw new \Exception("Stockloc not exists for item: ".$request->chgcode." dept: ".$request->deptcode." uom: ".$request->uom,500);
                }
                
                $ivdspdt = DB::table('material.ivdspdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$chargetrx_obj->auditno);
                
                if($ivdspdt->exists()){
                    $this->updivdspdt($chargetrx_obj);
                    $this->updgltran($ivdspdt->first()->idno);
                }else{
                    $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                    $this->crtgltran($ivdspdt_idno);
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
    }

    public function updivdspdt($chargetrx_obj){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$chargetrx_obj->auditno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$chargetrx_obj->uom)
            ->where('itemcode','=',$chargetrx_obj->chgcode);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$chargetrx_obj->uom)
            ->where('itemcode','=',$chargetrx_obj->chgcode)
            ->where('deptcode','=',$chargetrx_obj->reqdept)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $product->first()->avgcost; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $product->first()->avgcost;
            $curr_quan = $chargetrx_obj->quantity;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($chargetrx_obj->trxdate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + floatval(floatval($prev_netprice) * floatval($prev_quan)) - floatval(floatval($curr_netprice) * floatval($curr_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$chargetrx_obj->uom)
                                ->where('itemcode','=',$chargetrx_obj->chgcode)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            $product
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('Year','=',defaultController::toYear($chargetrx_obj->trxdate))
                ->where('DeptCode','=',$chargetrx_obj->reqdept)
                ->where('ItemCode','=',$chargetrx_obj->chgcode)
                ->where('UomCode','=',$chargetrx_obj->uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan) - floatval($curr_quan);
                $expdate_obj
                        ->update([
                            'balqty' => $balqty
                        ]);

            }else{
                throw new \Exception("No stockloc");
            }

        }

        $ivdspdt_arr = [
            'txnqty' => $chargetrx_obj->quantity,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$chargetrx_obj->auditno)
            ->update($ivdspdt_arr);
    }

    public function updgltran($ivdspdt_idno){
        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$ivdspdt->recno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($ivdspdt->trandate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            $gltran->update([
                'amount' => $ivdspdt->amount
            ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount + $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $ivdspdt->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$crcostcode)
                    ->where('glaccount','=',$cracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount - $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$ivdspdt->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

        }else{
            throw new \Exception("Gltran doesnt exists");
        }
    }

    public function crtivdspdt($chargetrx_obj){

        $my_uom = $chargetrx_obj->uom;
        $my_chgcode = $chargetrx_obj->chgcode;

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$my_uom)
            ->where('itemcode','=',$my_chgcode);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$my_uom)
            ->where('itemcode','=',$my_chgcode)
            ->where('deptcode','=',$chargetrx_obj->reqdept)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        $curr_netprice = $product->first()->avgcost;
        $curr_quan = $chargetrx_obj->quantity;
        if($stockloc->exists()){
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($chargetrx_obj->trxdate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) - floatval(floatval($curr_netprice) * floatval($curr_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$my_uom)
                                ->where('itemcode','=',$my_chgcode)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            DB::table('material.product')
                ->where('compcode','=',session('compcode'))
                ->where('unit','=',session('unit'))
                ->where('uomcode','=',$my_uom)
                ->where('itemcode','=',$my_chgcode)
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('Year','=',defaultController::toYear($chargetrx_obj->trxdate))
                ->where('DeptCode','=',$chargetrx_obj->reqdept)
                ->where('ItemCode','=',$my_chgcode)
                ->where('UomCode','=',$my_uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $curr_quan;
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
                throw new \Exception("No stockexp");
            }


        }

        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $chargetrx_obj->auditno,//OE IN
            'lineno_' => 1,
            'itemcode' => $chargetrx_obj->chgcode,
            'uomcode' => $chargetrx_obj->uom,
            'txnqty' => $chargetrx_obj->quantity,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'productcat' => $product->first()->productcat,
            'issdept' => $chargetrx_obj->reqdept,
            'reqdept' => $chargetrx_obj->reqdept,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'trantype' => 'DS',
            'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
            'trxaudno' => $chargetrx_obj->auditno,
            'mrn' => $this->givenullifempty($chargetrx_obj->mrn),
            'episno' => $this->givenullifempty($chargetrx_obj->episno),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];


        $insertGetId = DB::table('material.ivdspdt')
                            ->insertGetId($ivdspdt_arr);

        return $insertGetId;
    }

    public function crtgltran($ivdspdt_idno){
        $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode',session('compcode'))
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', session('compcode'))
            ->where('unit','=', session('unit'))
            ->where('itemcode','=', $ivdspdt->itemcode)
            ->first();

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$ivdspdt->reqdept)
            ->first();
        //utk debit accountcode
        $row_cat = DB::table('material.category')
            ->select('stockacct','cosacct')
            ->where('compcode','=',session('compcode'))
            ->where('catcode','=',$product_obj->productcat)
            ->first();

        $drcostcode = $row_dept->costcode;
        $dracc = $row_cat->cosacct;
        $crcostcode = $row_dept->costcode;
        $cracc = $row_cat->stockacct;

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'auditno' => $ivdspdt->recno,//billsum auditno
                'lineno_' => 1,
                'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => 'DS',
                'reference' => $ivdspdt->uomcode,
                'description' => $ivdspdt->itemcode, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $ivdspdt->amount
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $ivdspdt->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $ivdspdt->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcostcode)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $ivdspdt->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$ivdspdt->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

}