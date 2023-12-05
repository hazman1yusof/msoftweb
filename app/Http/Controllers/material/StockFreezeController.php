<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class StockFreezeController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.stockFreeze.stockFreeze');
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

        DB::beginTransaction();
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $request_no = $this->request_no('PHY', $request->srcdept);
        $recno = $this->recno('IV','PHY');

        try {

            // if(!empty($request->referral)){
            //     $recno = $this->recno('IV','IT');
            //     $compcode = session('compcode');
            // }else{
            //     $request_no = 0;
            //     $recno = 0;
            //     $compcode = 'DD';
            // }

            $table = DB::table("material.phycnthd");

            $array_insert = [
                'docno' => $request_no,
                'recno' => $recno,
                'srcdept' => $request->srcdept,
                'itemfrom' => $request->itemfrom,
                'itemto' => $request->itemto,
                'frzdate' => Carbon::now()->format('Y-m-d'),
                'frztime' => Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s'),
                'phycntdate' => $request->phycntdate,
                'respersonid' => $request->respersonid,
                'remarks' => strtoupper($request->remarks),
                'rackno' => $request->rackno,
                'phycnttime' => $request->phycnttime,
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

            // foreach ($request->data_detail as $key => $value){

            //     $lineno_ = DB::table('material.phycntdt') 
            //         ->where('compcode','=',session('compcode'))
            //         ->where('recno','=',$recno)
            //         ->where('srcdept','=',$request->phycnthd_srcdept)->max('lineno_');

            //     if($lineno_ == null){
            //         $lineno_ = 1;
            //     }else{
            //         $lineno_ = $lineno_+1;
            //     }

            //     DB::table('finance.apalloc')
            //         ->insert([
            //             'compcode' => session('compcode'),
            //             'lineno_' => $lineno_,
            //             'recno' => $recno,
            //             'srcdept' => $request->phycnthd_srcdept,
            //             'phycntdate' => $value['phycntdate'],
            //             'phycnttime' => $value['phycnttime'],
            //             'itemcode' => $value['itemcode'],
            //             'uomcode' => $value['uomcode'],
            //             'unitcost' => $value['unitcost'],
            //             'phyqty' => $value['phyqty'],
            //             'thyqty' => $value['thyqty'],
            //             'expdate' => $value['expdate'],
            //             'batchno' => $value['batchno'],
            //             'adduser' => session('username'),
            //             'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //         ]);

            // }

            $responce = new stdClass();
            $responce->docno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->frzdate = Carbon::now()->format('Y-m-d');
            $responce->frztime = Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s');
            $responce->adduser = session('username');
            $responce->adddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

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

        DB::beginTransaction();

        $table = DB::table("material.phycnthd");

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
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            $responce = new stdClass();
           // $responce->totalAmount = $request->delordhd_totamount;
           $responce->upduser = session('username');
           $responce->upddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

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
                //-- 1. transfer from ivtmphd to ivtxnhd --//
                $ivtmphd = DB::table('material.ivtmphd')
                            ->where('idno','=',$idno)
                            ->first();

                $this->check_sequence_backdated($ivtmphd);

                DB::table("material.IvTxnHd")
                    ->insert([
                        'AddDate'  => $ivtmphd->adddate,
                        'AddUser'  => $ivtmphd->adduser,
                        'Amount'   => $ivtmphd->amount,
                        'CompCode' => $ivtmphd->compcode,
                        'unit'     => $ivtmphd->unit,
                        'DateActRet'   => $ivtmphd->dateactret,
                        'DateSupRet'   => $ivtmphd->datesupret,
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

                //-- 2. transfer from ivtmpdt to ivtxndt --//
                $ivtmpdt_obj = DB::table('material.ivtmpdt')
                        ->where('ivtmpdt.compcode','=',session('compcode'))
                        ->where('ivtmpdt.recno','=',$ivtmphd->recno)
                        ->where('ivtmpdt.recstatus','!=','DELETE')
                        ->get();

                $this->need_upd_ivreqdt($idno);

                foreach ($ivtmpdt_obj as $value) {

                    $obj_acc = invtran_util::get_acc($value,$ivtmphd);

                    $craccno = $obj_acc->craccno;
                    $crccode = $obj_acc->crccode;
                    $draccno = $obj_acc->draccno;
                    $drccode = $obj_acc->drccode;

                    DB::table('material.ivtxndt')
                        ->insert([
                            'compcode' => $value->compcode, 
                            'recno' => $value->recno, 
                            'lineno_' => $value->lineno_, 
                            'itemcode' => $value->itemcode, 
                            'uomcode' => $value->uomcode,
                            'uomcoderecv' => $value->uomcoderecv,  
                            'txnqty' => $value->txnqty, 
                            'netprice' => $value->netprice, 
                            'adduser' => $value->adduser, 
                            'adddate' => $value->adddate, 
                            'upduser' => $value->upduser, 
                            'upddate' => $value->upddate, 
                            'TranType' => $ivtmphd->trantype,
                            'deptcode'  => $ivtmphd->txndept,
                            // 'productcat' => $productcat, 
                            'draccno' => $draccno, 
                            'drccode' => $drccode, 
                            'craccno' => $craccno, 
                            'crccode' => $crccode, 
                            'expdate' => $value->expdate, 
                            'qtyonhand' => $value->qtyonhand,
                            'qtyonhandrecv' => $value->qtyonhandrecv,  
                            'batchno' => $value->batchno, 
                            'amount' => $value->amount, 
                            'trandate' => $ivtmphd->trandate,
                            'sndrcv' => $ivtmphd->sndrcv,
                        ]);


                    //-- 4. posting stockloc OUT --//

                    $trantype_obj = DB::table('material.ivtxntype')
                        ->where('ivtxntype.compcode','=',session('compcode'))
                        ->where('ivtxntype.trantype','=',$ivtmphd->trantype)
                        ->first();

                    if(strtoupper($trantype_obj->isstype) == 'TRANSFER'){
                        $retval = invtran_util::posting_for_transfer($value,$ivtmphd);
                    
                    }else if(strtoupper($trantype_obj->isstype) == 'ADJUSTMENT' || strtoupper($trantype_obj->isstype) == 'LOAN' || strtoupper($trantype_obj->isstype) == 'ISSUE'|| strtoupper($trantype_obj->isstype) == 'WRITE-OFF'){
                        switch (strtoupper($trantype_obj->crdbfl)) {
                            case 'IN':
                                invtran_util::posting_for_adjustment_in($value,$ivtmphd,$trantype_obj->isstype);
                                break;
                            case 'OUT':
                                invtran_util::posting_for_adjustment_out($value,$ivtmphd,$trantype_obj->isstype);
                                break;
                            default:
                                # code...
                                break;
                        }
                    }

                    //--- 7. posting GL ---//

                    //amik yearperiod
                    $yearperiod = $this->getyearperiod($ivtmphd->trandate);
     
                    //1. buat gltran
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => $value->compcode,
                            'adduser' => $value->adduser,
                            'adddate' => $value->adddate,
                            'auditno' => $value->recno,
                            'lineno_' => $value->lineno_,
                            'source' => $ivtmphd->source,
                            'trantype' => $ivtmphd->trantype,
                            'reference' => $ivtmphd->txndept .' '. $ivtmphd->docno,
                            'description' => $ivtmphd->sndrcv,
                            'postdate' => $ivtmphd->trandate,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $drccode,
                            'dracc' => $draccno,
                            'crcostcode' => $crccode,
                            'cracc' => $craccno,
                            'amount' => $value->amount,
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
                                'actamount'.$yearperiod->period => $value->amount + $this->gltranAmount,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $drccode,
                                'glaccount' => $draccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $value->amount,
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
                                'actamount'.$yearperiod->period => $this->gltranAmount - $value->amount,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $crccode,
                                'glaccount' => $craccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => -$value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                }

                //--- 8. change recstatus to posted ---//

                DB::table('material.ivtmphd')
                    ->where('recno','=',$ivtmphd->recno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'postedby' => session('username'),
                        'postdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'POSTED' 
                    ]);

                DB::table('material.ivtmpdt')
                    ->where('recno','=',$ivtmphd->recno)
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','!=','DELETE')
                    ->update([
                        'recstatus' => 'POSTED' 
                    ]);
            }
            

            $queries = DB::getQueryLog();
            dump($queries);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            
            return response($e->getMessage(), 500);
        }

    }

    public function cancel(Request $request){
        
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

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'get_dtl_itemcode':
                return $this->get_dtl_itemcode($request);
            default:
                return 'error happen..';
        }
    }

    public function get_dtl_itemcode(Request $request){
                    
        $phycntdt = DB::table('material.stockloc as s')
                    ->select(
                        's.deptcode AS srcdept',
                        's.itemcode AS itemcode',
                        's.uomcode AS uomcode',
                        'p.description AS desc',
                        'p.currprice AS unitcost',
                        'e.balqty AS thyqty',
                        'e.expdate AS expdate',
                        'e.batchno AS batchno'
                    )
                    ->join('material.product as p', function($join) use ($request){
                                $join = $join->on('p.itemcode', '=', 's.itemcode')
                                    ->on('p.compcode', '=', 's.compcode');
                    })
                    ->join('material.stockexp as e', function($join) use ($request){
                        $join = $join->on('e.itemcode', '=', 's.itemcode')
                            ->on('e.compcode', '=', 's.compcode');
                    })
                    ->where('s.compcode',session('compcode'))
                    ->where('s.deptcode', $request->srcdept)
                    ->whereBetween('s.itemcode', [$request->itemcodefrom, $request->itemcodeto])
                    ->get();

        // $return_array=[];
        // $got_array=[];
        // if($phycntdt->exists()){
        //     foreach ($phycntdt->get() as $obj_phycntdt) {
        //         if(!in_array($obj_phycntdt->itemcode,$got_array)){
                  
        //                 $obj_phycntdt->itemfrom &&  $obj_phycntdt->itemto = $phycntdt->itemcode;
        //                 $obj_phycntdt->srcdept = $phycntdt->srcdept;
        //                 $obj_phycntdt->idno = $phycntdt->idno;
        //                 $obj_phycntdt->uomcode = $phycntdt->uomcode;
        //                 $obj_phycntdt->currprice = $phycntdt->unitcost;
        //                 $obj_phycntdt->balqty = $phycntdt->thyqty;
        //                 $obj_phycntdt->batchno = $phycntdt->batchno;
        //                 $obj_phycntdt->expdate = $phycntdt->expdate;

        //                 if(!in_array($obj_phycntdt, $return_array)){
        //                     array_push($return_array,$obj_phycntdt);
        //                 }
                 
        //         }
        //     }
        // }else{
        //     $return_array = $phycntdt->get();
        // }

        $responce = new stdClass();
        // $responce->rows = $return_array;

        return json_encode($responce);

    }

    // public function get_dtl_itemcode(Request $request){
        
    //     $phycntdt = DB::table('material.stockloc as s')
    //         ->select(
    //             's.deptcode AS srcdept',
    //             's.itemcode AS itemcode',
    //             's.uomcode AS uomcode',
    //             'p.description AS desc',
    //             'p.currprice AS unitcost',
    //             'e.balqty AS thyqty',
    //             'e.expdate AS expdate',
    //             'e.batchno AS batchno'
    //         )
    //         ->join('material.product as p', function($join) use ($request){
    //                     $join = $join->on('p.itemcode', '=', 's.itemcode')
    //                         ->on('p.compcode', '=', 's.compcode');
    //         })
    //         ->join('material.stockexp as e', function($join) use ($request){
    //             $join = $join->on('e.itemcode', '=', 's.itemcode')
    //                 ->on('e.compcode', '=', 's.compcode');
    //         })
    //         ->where('s.compcode',session('compcode'))
    //         ->where('s.deptcode', $request->srcdept)
    //         ->whereBetween('s.itemcode', [$request->itemcodefrom, $request->itemcodeto]);

    //         $return_array=[];
    //         $got_array=[];
    //         if($phycntdt->exists()){
    //             foreach ($phycnthd->get() as $obj_phycnthd) {
    //                 foreach ($phycntdt->get() as $obj_phycntdt) {
    //                     if(!in_array($obj_phycnthd->idno,$got_array)){
    //                         if(
    //                             $phycntdt->srcdept == $obj_phycnthd->srcdept
    //                             && $phycntdt->itemcode == $obj_phycnthd->itemfrom
    //                             && $phycntdt->itemcode == $obj_phycnthd->itemto
    //                         ){
    //                             $obj_phycnthd->itemfrom &&  $obj_phycnthd->itemto = $phycntdt->itemcode;
    //                             $obj_phycnthd->recno = $phycntdt->recno;
    //                             $obj_phycnthd->lineno_ = $phycntdt->lineno_;
    //                             $obj_phycnthd->idno = $phycntdt->idno;
    
    //                             if(!in_array($obj_phycnthd, $return_array)){
    //                                 array_push($return_array,$obj_phycnthd);
    //                             }
    //                             array_push($got_array,$obj_phycnthd->idno);
    //                         }else{
    //                             // $obj_phycnthd->refamount = $obj_phycnthd->outamount;
    //                             // if(!in_array($obj_phycnthd, $return_array)){
    //                             //     array_push($return_array,$obj_phycnthd);
    //                             // }
    //                         }
    //                     }
    //                 }
    //             }
    //         }else{
    //             $return_array = $phycnthd->get();
    //         }
    
    //         $responce = new stdClass();
    //         $responce->rows = $return_array;
    
    //         return json_encode($responce);
        
    // }


    public function showpdf(Request $request){
        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }
        
        $ivtmphd = DB::table('material.ivtmphd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->first();

        $ivtmpdt = DB::table('material.ivtmpdt AS ivdt')
            ->select('ivdt.compcode','ivdt.recno','ivdt.lineno_','ivh.trandate','ivdt.itemcode','p.description', 'ivdt.qtyonhand','ivdt.uomcode', 'ivdt.qtyonhandrecv','ivdt.uomcoderecv',
            'ivdt.txnqty','ivdt.qtyrequest','ivdt.netprice','ivdt.amount','ivdt.expdate','ivdt.batchno')
            ->leftJoin('material.productmaster as p', function($join) use ($request){
                        $join = $join->on('ivdt.itemcode', '=', 'p.itemcode')
                                ->where('p.compcode','=',session('compcode'));
                    })
            ->leftJoin('material.ivtmphd as ivh', function($join) use ($request){
                        $join = $join->on('ivh.recno', '=', 'ivdt.recno')
                                ->where('ivh.compcode','=',session('compcode'));
                    })
            ->where('ivdt.compcode','=',session('compcode'))
            ->where('ivdt.recno','=',$recno)
            ->get();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $total_amt = DB::table('material.ivtmpdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->sum('amount');

        // $total_tax = DB::table('material.ivtmpdt')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('recno','=',$recno)
        //     ->sum('amtslstax');
        
        // $total_discamt = DB::table('material.ivtmpdt')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('recno','=',$recno)
        //     ->sum('amtdisc');

        $totamount_expld = explode(".", (float)$ivtmphd->amount);

        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";

        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }


        //account
        $cc_acc = [];
        foreach ($ivtmpdt as $value) {
            $gltran = DB::table('finance.gltran as gl')
                   ->where('gl.compcode',session('compcode'))
                   ->where('gl.auditno',$value->recno)
                   ->where('gl.lineno_',$value->lineno_)
                   ->where('gl.source',$ivtmphd->source)
                   ->where('gl.trantype',$ivtmphd->trantype)
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
        
        return view('material.inventoryTransaction.inventoryTransaction_pdfmake',compact('ivtmphd','ivtmpdt', 'company','total_amt','cr_acc','db_acc'));
        
    }
}

