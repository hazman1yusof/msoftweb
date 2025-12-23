<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\RCNExport;

class GoodReturnCreditController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.goodReturnCredit.goodReturnCredit');
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
        $table =  DB::table('material.delordhd as do');
        $table = $table->select('do.idno as delordhd_idno','do.compcode as delordhd_compcode','do.recno as delordhd_recno','do.prdept as delordhd_prdept','do.trantype as delordhd_trantype','do.docno as delordhd_docno','do.delordno as delordhd_delordno','do.invoiceno as delordhd_invoiceno','do.suppcode as delordhd_suppcode','do.srcdocno as delordhd_srcdocno','do.po_recno as delordhd_po_recno','do.deldept as delordhd_deldept','do.subamount as delordhd_subamount','do.amtdisc as delordhd_amtdisc','do.perdisc as delordhd_perdisc','do.totamount as delordhd_totamount','do.deliverydate as delordhd_deliverydate','do.trandate as delordhd_trandate','do.trantime as delordhd_trantime','do.respersonid as delordhd_respersonid','do.checkpersonid as delordhd_checkpersonid','do.checkdate as delordhd_checkdate','do.postedby as delordhd_postedby','do.recstatus as delordhd_recstatus','do.remarks as delordhd_remarks','do.adduser as delordhd_adduser','do.adddate as delordhd_adddate','do.upduser as delordhd_upduser','do.upddate as delordhd_upddate','do.reason as delordhd_reason','do.rtnflg as delordhd_rtnflg','do.reqdept as delordhd_reqdept','do.credcode as delordhd_credcode','do.impflg as delordhd_impflg','do.allocdate as delordhd_allocdate','do.postdate as delordhd_postdate','do.deluser as delordhd_deluser','do.taxclaimable as delordhd_taxclaimable','do.TaxAmt as delordhd_TaxAmt','do.prortdisc as delordhd_prortdisc','do.cancelby as delordhd_cancelby','do.canceldate as delordhd_canceldate','do.reopenby as delordhd_reopenby','do.reopendate as delordhd_reopendate','do.unit as delordhd_unit','do.postflag as delordhd_postflag','do.debtorcode as delordhd_debtorcode','do.mrn as delordhd_mrn','do.cnno as delordhd_cnno','pm.newmrn as pm_newmrn','do.hdrtype as delordhd_hdrtype','do.paymode as delordhd_paymode')
                    ->where('do.compcode',session('compcode'))
                    ->where('do.trantype','RCN')
                    ->whereNotNull('do.cnno');

        // $table = $table->

        $table = $table->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                        $join = $join->on('pm.mrn','=','do.mrn')
                                     ->where('pm.compcode',session('compcode'));
                 });

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'pm_newmrn'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('pm.newmrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'dm_name'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('dm.name','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'delordhd_cnno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('do.cnno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_payercode'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.payercode','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_mrn'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.mrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.auditno','like',$request->searchVal[0]);
                });
            }else{
                $table = $table->Where(function ($table) use ($request){
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                });
            }
        }

        if(strtoupper($request->status)!='ALL'){
            $table->Where('do.recstatus',$request->status);
        }

        if(strtoupper($request->trandept)!='ALL'){
            $table->Where('do.deldept',$request->trandept);
        }

        // if(!empty($request->filterCol)){
        //     foreach ($request->filterCol as $key => $value) {
        //         $pieces = explode(".", $request->filterVal[$key], 2);
        //         if($pieces[0] == 'session'){
        //             $table = $table->where($request->filterCol[$key],'=',session($pieces[1]));
        //         }else if($pieces[0] == '<>'){
        //             $table = $table->where($request->filterCol[$key],'<>',$pieces[1]);
        //         }else if($pieces[0] == '>'){
        //             $table = $table->where($request->filterCol[$key],'>',$pieces[1]);
        //         }else if($pieces[0] == '>='){
        //             $table = $table->where($request->filterCol[$key],'>=',$pieces[1]);
        //         }else if($pieces[0] == '<'){
        //             $table = $table->where($request->filterCol[$key],'<',$pieces[1]);
        //         }else if($pieces[0] == '<='){
        //             $table = $table->where($request->filterCol[$key],'<=',$pieces[1]);
        //         }else if($pieces[0] == 'on'){
        //             $table = $table->whereColumn($request->filterCol[$key],$pieces[1]);
        //         }else if($pieces[0] == 'null'){
        //             $table = $table->whereNull($request->filterCol[$key]);
        //         }else if($pieces[0] == 'raw'){
        //             $table = $table->where($request->filterCol[$key],'=',DB::raw($pieces[1]));
        //         }else{
        //             $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
        //         }
        //     }
        // }

        if(!empty($request->sidx)){

            // if(!empty($request->fixPost)){
            //     $request->sidx = substr_replace($request->sidx, ".", strpos($request->sidx, "_"), strlen("."));
            // }

            $pieces_sidx = explode("_", $request->sidx);
            $request->sidx = 'do.'.$pieces_sidx[1];
            
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

        if(empty($delordhd->recno)){
            $responce = new stdClass();
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            $responce->rows = [];

            return json_encode($responce);
        }


        $table =  DB::table('material.delorddt as dodt');
        $table = $table->select('dodt.idno','dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode', 'dodt.pouom', 'dodt.suppcode','dodt.trandate',
        'dodt.deldept','dodt.deliverydate','dodt.qtydelivered','dodt.qtyreturned','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 
        'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks','t.rate')
        ->where('dodt.recno',$delordhd->recno)
        ->where('dodt.recstatus','!=','DELETE');

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

            // if(!empty($request->referral)){
            //     $request_no = $this->request_no('GRT', $request->delordhd_deldept);
            //     $recno = $this->recno('IV','IT');
            //     $compcode = session('compcode');
            // }else{
                $request_no = 0;
                $recno = 0;
                $compcode = 'DD';
                $cnno = 'DD';
            // }

            $table = DB::table("material.delordhd");

            $array_insert = [
                'trantype' => 'RCN', 
                'docno' => $request_no,
                'recno' => $recno,
                'compcode' => $compcode,
                'unit' => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'deldept' => $request->delordhd_deldept,
                'subamount' => $request->delordhd_subamount,
                'amtdisc' => $request->delordhd_amtdisc,
                'totamount' => $request->delordhd_totamount,
                'trandate' => $request->delordhd_trandate,
                'trantime' => $request->delordhd_trantime,
                'deliverydate' => $request->delordhd_deliverydate,
                'remarks' => $request->delordhd_remarks,
                'taxclaimable' => $request->delordhd_taxclaimable,
                'debtorcode' => $request->delordhd_debtorcode,
                'hdrtype' => $request->delordhd_hdrtype,
                // 'paymode' => $request->delordhd_paymode,
                'mrn' => $request->delordhd_mrn,
                'cnno' => $cnno
            ];

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;

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
        DB::beginTransaction();

        try{

            //2. Delete detail from delorddt
            DB::table('material.delorddt')->where('recno','=',$request->delordhd_recno);

            //3. Update srcdocno_delordhd
            $table = DB::table("material.delordhd");

            $array_update = [
                // 'trantype' => 'GRT', 
                // 'docno' => $request_no,
                // 'recno' => $recno,
                // 'compcode' => $compcode,
                // 'unit' => session('unit'),
                // 'adduser' => session('username'),
                // 'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'recstatus' => 'OPEN',
                // 'deldept' => $request->delordhd_deldept,
                // 'subamount' => $request->delordhd_subamount,
                // 'amtdisc' => $request->delordhd_amtdisc,
                // 'totamount' => $request->delordhd_totamount,
                'trandate' => $request->delordhd_trandate,
                'trantime' => $request->delordhd_trantime,
                'deliverydate' => $request->delordhd_deliverydate,
                'remarks' => $request->delordhd_remarks,
                // 'taxclaimable' => $request->delordhd_taxclaimable,
                // 'debtorcode' => $request->delordhd_debtorcode,
                // 'hdrtype' => $request->delordhd_hdrtype,
                // 'mrn' => $request->delordhd_mrn,
            ];

            $table = $table->where('idno','=',$request->delordhd_idno);
            $table->update($array_update);

            $totalAmount = $request->delordhd_totamount;

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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

                $recno = $delordhd_obj->recno;
                $cnno = substr($delordhd_obj->cnno,3);

                    //2. pastu letak dkt ivtxnhd
                DB::table('material.ivtxnhd')
                    ->insert([
                        'compcode'=>$delordhd_obj->compcode, 
                        'recno'=>$delordhd_obj->recno, 
                        'reference'=>$delordhd_obj->delordno, 
                        'source'=>'IV', 
                        'txndept'=>$delordhd_obj->deldept, 
                        'trantype'=>'RCN',
                        'docno'=>$delordhd_obj->docno, 
                        // 'srcdocno'=>$delordhd_obj->srcdocno, 
                        // 'sndrcv'=>$delordhd_obj->suppcode, 
                        // 'sndrcvtype'=>'Supplier', 
                        'trandate'=>$delordhd_obj->trandate, 
                        'trantime'=>$delordhd_obj->trantime, 
                        'datesupret'=>$delordhd_obj->deliverydate, 
                        // 'respersonid'=>$delordhd_obj->checkpersonid, 
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

                $debtorobj = DB::table('debtor.debtormast')
                            ->where('compcode',session('compcode'))
                            ->where('debtorcode',$delordhd_obj->debtorcode)
                            ->first();

                $deldeptobj = DB::table('sysdb.department')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$delordhd_obj->deldept)
                            ->first();

                //amik yearperiod dari delordhd
                $yearperiod = $this->getyearperiod($delordhd_obj->trandate);

                    //2. start looping untuk delorddt
                foreach ($delorddt_obj as $value) {
                    if($value->qtyreturned <= 0){
                        continue;
                    }
                    $productcat = $this->get_productcat($value->itemcode);
                    $txnqty = $value->qtyreturned;
                    $netprice = $value->netunitprice;

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
                            'upduser' => session('username'), 
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                            'productcat' => $productcat, 
                            // 'draccno' => $value->draccno, 
                            // 'drccode' => $value->drccode, 
                            // 'craccno' => $value->craccno, 
                            // 'crccode' => $value->crccode, 
                            // 'expdate' => $value->expdate, 
                            'remarks' => strtoupper($value->remarks), 
                            // 'qtyonhand' => 0, 
                            // 'batchno' => $value->batchno, 
                            'amount' => $value->amount, 
                            'trandate' => $delordhd_obj->trandate, 
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
                        $NetMvVal = $stockloc_arr['netmvval'.$month] + ($netprice * $txnqty);

                         $stockloc_obj
                            ->update([
                                'QtyOnHand' => $QtyOnHand,
                                'NetMvQty'.$month => $NetMvQty, 
                                'NetMvVal'.$month => $NetMvVal
                            ]);

                    }else{
                    //3.kalu xde stockloc, create stockloc baru

                    }

                    $expdate_obj = DB::table('material.stockexp')
                        ->where('compcode',session('compcode'))
                        ->where('Year','=',defaultController::toYear($value->trandate))
                        ->where('DeptCode','=',$value->deldept)
                        ->where('ItemCode','=',$value->itemcode)
                        ->where('UomCode','=',$value->uomcode)
                        ->orderBy('expdate', 'asc');

                    //2.kalu ada Stock Expiry, update

                    if($expdate_obj->exists()){

                        $expdate_first = $expdate_obj->first();
                        $txnqty_ = $txnqty;
                        $balqty = $expdate_first->balqty + $txnqty;

                        DB::table('material.stockexp')
                            ->where('idno','=',$expdate_first->idno)
                            ->update([
                                'balqty' => $balqty
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
                        $newqtyonhand = $OldQtyOnHand + $txnqty;

                        // update qtyonhand, avgcost, currprice
                        $product_obj
                            ->update([
                                'qtyonhand' => $newqtyonhand ,
                                // 'avgcost' => $newAvgCost,
                                // 'currprice' => $currprice
                            ]);
                    }
                    //--- 6. posting GL ---//

                    //amik ivtxnhd
                    $ivtxnhd_obj = DB::table('material.ivtxnhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','IV')
                        ->where('trantype','RCN')
                        ->where('recno',$delordhd_obj->recno)
                        ->first();

                    //amik department,category dgn sysparam pvalue1 dgn pvalue2
                    //utk debit costcode
                    

                    //utk debit accountcode
                    $row_cat = DB::table('material.category')
                        ->select('stockacct','adjacct')
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

                    //1. buat gltran grt
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
                            'drcostcode' => $deldeptobj->costcode,
                            'dracc' => $row_cat->stockacct,
                            'crcostcode' => $deldeptobj->costcode,
                            'cracc' => $row_cat->adjacct,
                            'amount' => $value->amount,
                            'idno' => $value->itemcode
                        ]);

                    $this->init_glmastdtl(
                            $deldeptobj->costcode,//drcostcode
                            $row_cat->stockacct,//dracc
                            $deldeptobj->costcode,//crcostcode
                            $row_cat->adjacct,//cracc
                            $yearperiod,
                            $value->amount
                        );

                    $chgmast = DB::table('hisdb.chgmast')
                                    ->where('compcode',session('compcode'))
                                    ->where('chgcode',$value->itemcode)
                                    ->first();

                    $chgtype=DB::table('hisdb.chgtype')
                                    ->where('compcode',session('compcode'))
                                    ->where('chgtype',$chgmast->chgtype)
                                    ->first();

                    //1. gltran CN
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => session('compcode'),
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'auditno' => $cnno,
                            'lineno_' => $value->lineno_,
                            'source' => 'PB',
                            'trantype' => 'CN',
                            'reference' => $delordhd_obj->cnno,
                            'description' => $delordhd_obj->remarks,
                            'postdate' => $delordhd_obj->trandate,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $deldeptobj->costcode,
                            'dracc' => $chgtype->opacccode,
                            'crcostcode' => $debtorobj->actdebccode,
                            'cracc' => $debtorobj->actdebglacc,
                            'amount' => $value->amount,
                            'idno' => $value->itemcode
                        ]);

                    $this->init_glmastdtl(
                            $deldeptobj->costcode,//drcostcode
                            $chgtype->opacccode,//dracc
                            $debtorobj->actdebccode,//crcostcode
                            $debtorobj->actdebglacc,//cracc
                            $yearperiod,
                            $value->amount
                        );

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
                    ->where('trantype','=','RCN')
                    ->where('recno','=',$recno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'postedby' => session('username'),
                        'postdate' => $delordhd_obj->trandate, 
                        'recstatus' => 'POSTED' 
                    ]);

                DB::table('material.delorddt')
                    ->where('recno','=',$recno)
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','!=','DELETE')
                    ->update([
                        'recstatus' => 'POSTED' 
                    ]);

                DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','PB')
                    ->where('trantype','CN')
                    ->where('auditno',$cnno)
                    ->update([
                        'posteddate' => $delordhd_obj->trandate, 
                        'recstatus' => 'POSTED', 
                    ]);

                DB::table('debtor.dbactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','PB')
                    ->where('trantype','CN')
                    ->where('auditno',$cnno)
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
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $idno){
                
                $delordhd_obj = DB::table('material.delordhd')
                    ->where('idno', '=', $idno)
                    ->where('compcode', '=' ,session('compcode'))
                    ->first();

                DB::table('material.delordhd')
                    ->where('idno', '=', $idno)
                    ->where('compcode', '=' ,session('compcode'))
                    ->update([
                        'cancelby' => session('username'),
                        'canceldate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'CANCELLED' 
                    ]);

                DB::table('material.delorddt')
                    ->where('recno','=',$delordhd_obj->recno)
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
        
        $delordhd = DB::table('material.delordhd as do')
            ->select('do.compcode','do.recno','do.prdept','do.trantype','do.docno','do.delordno','do.invoiceno','do.suppcode','do.srcdocno','do.po_recno','do.deldept','do.subamount','do.amtdisc','do.perdisc','do.totamount','do.deliverydate','do.trandate','do.trantime','do.respersonid','do.checkpersonid','do.checkdate','do.postedby','do.recstatus','do.remarks','do.adduser','do.adddate','do.upduser','do.upddate','do.reason','do.rtnflg','do.reqdept','do.credcode','do.impflg','do.allocdate','do.postdate','do.deluser','do.taxclaimable','do.TaxAmt','do.prortdisc','do.cancelby','do.canceldate','do.reopenby','do.reopendate','do.unit','do.postflag','do.debtorcode','do.mrn','do.cnno','do.hdrtype','do.paymode','dm.name','dm.address1','dm.address2','dm.address3','dm.address4')
            ->where('do.compcode','=',session('compcode'))
            ->where('do.recno','=',$recno)
            ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'do.debtorcode')
                                ->where('dm.compcode','=',session('compcode'));
                    })
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
            ->where('dodt.recstatus','!=','DELETE')
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
                // dd($gltran);

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
        
        return view('material.goodReturncredit.goodreturncredit_pdfmake',compact('delordhd','delorddt','totamt_eng', 'company', 'total_tax', 'total_discamt', 'total_amt','cr_acc','db_acc'));
        
    }

    public function get_productcat($itemcode){
        $query = DB::table('material.product')
                ->select('productcat')
                ->where('compcode','=',session('compcode'))
                ->where('itemcode','=',$itemcode)
                ->first();
        
        return $query->productcat;
    }

    public function gltran_fromdept($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        return $obj;
    }

    public function gltran_fromcategory($category){
        $obj = DB::table('material.category')
                ->select('expacct')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CR')
                ->where('catcode','=',$category)
                ->first();

        return $obj;
    }

    public function init_glmastdtl($dbcc,$dbacc,$crcc,$cracc,$yearperiod,$amount){
        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($dbcc,$dbacc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$dbcc)
                ->where('glaccount','=',$dbacc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => floatval($amount) + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $dbcc,
                    'glaccount' => $dbacc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcc,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcc)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - floatval($amount),
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcc,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }
}

