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
            case 'get_itemcode_uom_recv':
                return $this->get_itemcode_uom_recv($request);
                break;
            case 'get_itemcode_uom_recv_check':
                return $this->get_itemcode_uom_recv_check($request);
                break;
            case 'get_itemcode_price_check':
                return $this->get_itemcode_price_check($request);
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
                        return $this->order_entry_edit($request);
                    case 'del':
                        return $this->order_entry_del($request);
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
                    ->select('trx.auditno','trx.compcode','trx.idno','trx.mrn','trx.episno','trx.epistype','trx.trxtype','trx.docref','trx.trxdate','trx.chgcode','trx.billcode','trx.costcd','trx.revcd','trx.mmacode','trx.billflag','trx.billdate','trx.billtype','trx.doctorcode','trx.chg_class','trx.unitprce','trx.quantity','trx.amount','trx.trxtime','trx.chggroup','trx.qstat','trx.dracccode','trx.cracccode','trx.arprocess','trx.taxamount','trx.billno','trx.invno','trx.uom','trx.uom_recv','trx.billtime','trx.invgroup','trx.reqdept as deptcode','trx.issdept','trx.invcode','trx.resulttype','trx.resultstatus','trx.inventory','trx.updinv','trx.invbatch','trx.doscode','trx.duration','trx.instruction','trx.discamt','trx.disccode','trx.pkgcode','trx.remarks','trx.frequency','trx.ftxtdosage','trx.addinstruction','trx.qtyorder','trx.ipqueueno','trx.itemseqno','trx.doseqty','trx.freqqty','trx.isudept','trx.qtyissue','trx.durationcode','trx.reqdoctor','trx.unit','trx.agreementid','trx.chgtype','trx.adduser','trx.adddate','trx.lastuser','trx.lastupdate','trx.daytaken','trx.qtydispense','trx.takehomeentry','trx.latechargesentry','trx.taxcode','trx.recstatus','trx.drugindicator','trx.id','trx.patmedication','trx.mmaprice','pt.avgcost as cost_price','dos.dosedesc as doscode_desc','fre.freqdesc as frequency_desc','ins.description as addinstruction_desc','dru.description as drugindicator_desc')
                    ->where('trx.mrn' ,'=', $request->mrn)
                    ->where('trx.episno' ,'=', $request->episno)
                    ->where('trx.compcode','=',session('compcode'))
                    ->where('trx.chggroup',$request->chggroup)
                    ->where('trx.recstatus','<>','DELETE')
                    ->orderBy('trx.adddate', 'desc');

        $table_chgtrx = $table_chgtrx->leftjoin('material.product as pt', function($join) use ($request){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'trx.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'trx.uom_recv');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.dose as dos', function($join) use ($request){
                            $join = $join->where('dos.compcode', '=', session('compcode'));
                            $join = $join->on('dos.dosecode', '=', 'trx.doscode');
                        });

        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.freq as fre', function($join) use ($request){
                            $join = $join->where('fre.compcode', '=', session('compcode'));
                            $join = $join->on('fre.freqcode', '=', 'trx.frequency');
                        });

        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.instruction as ins', function($join) use ($request){
                            $join = $join->where('ins.compcode', '=', session('compcode'));
                            $join = $join->on('ins.inscode', '=', 'trx.addinstruction');
                        });

        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.drugindicator as dru', function($join) use ($request){
                            $join = $join->where('dru.compcode', '=', session('compcode'));
                            $join = $join->on('dru.drugindcode', '=', 'trx.drugindicator');
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
                        'taxamount' => $request->taxamount,
                        'uom' => $request->uom,
                        'uom_recv' => $request->uom_recv,
                        'invgroup' => $chgmast->invgroup,
                        'reqdept' => $request->deptcode,
                        'issdept' => $request->deptcode,
                        'invcode' => $chgmast->chggroup,
                        'inventory' => $updinv,
                        'updinv' =>  $updinv,
                        'discamt' => $request->discamount,
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
                        'remarks' => $request->remarks,
                        'recstatus' => 'POSTED',
                        'doctorcode' => $this->givenullifempty($request->doctorcode),
                        'drugindicator' => $this->givenullifempty($request->drugindicator),
                        'frequency' => $this->givenullifempty($request->frequency),
                        'doscode' => $this->givenullifempty($request->doscode),
                        'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                        'addinstruction' => $this->givenullifempty($request->addinstruction)
                    ]);
            
            $chargetrx_obj = db::table('hisdb.chargetrx')
                            ->where('compcode',session('compcode'))
                            ->where('id', '=', $insertGetId)
                            ->first();
            
            $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$request->uom_recv)
                            ->where('itemcode','=',$request->chgcode);
            
            if($product->exists()){
                // $stockloc = DB::table('material.stockloc')
                //         ->where('compcode','=',session('compcode'))
                //         ->where('uomcode','=',$request->uom_recv)
                //         ->where('itemcode','=',$request->chgcode)
                //         ->where('deptcode','=',$request->deptcode)
                //         ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                
                // if($stockloc->exists()){
                //     $stockloc = $stockloc->first();
                // }else{
                //     throw new \Exception("Stockloc not exists for item: ".$request->chgcode." dept: ".$request->deptcode." uom: ".$request->uom_recv,500);
                // }
                
                $ivdspdt = DB::table('material.ivdspdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$chargetrx_obj->auditno);
                
                if($ivdspdt->exists()){
                    $this->updivdspdt($chargetrx_obj);
                    $this->updgltran($chargetrx_obj,$updinv);
                }else{
                    $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                    $this->crtgltran($chargetrx_obj,$updinv);
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
    }

    public function order_entry_edit(Request $request){
        
        DB::beginTransaction();
        try {

            $chargetrx_lama = DB::table("hisdb.chargetrx")
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$request->id)
                            ->first();

            $chgmast = DB::table("hisdb.chgmast")
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',$request->chgcode)
                    ->where('uom','=',$request->uom_recv)
                    ->first();
            
            $updinv = ($chgmast->invflag == '1')? 1 : 0;

            if($chargetrx_lama->chgcode != $request->chgcode || $chargetrx_lama->uom_recv != $request->uom_recv){

                $edit_lain_chggroup = true;
                $product_lama = DB::table('hisdb.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$chargetrx_lama->uom_recv)
                        ->where('itemcode','=',$chargetrx_lama->chgcode);

                if($product_lama->exists()){
                    $this->delivdspdt($chargetrx_lama);
                }

                $this->sysdb_log('update',$chargetrx_lama,'sysdb.chargetrxlog');

                DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('id', '=', $request->id)
                        ->update([
                            'trxdate' => $request->trxdate,
                            'chgcode' => $request->chgcode,
                            'chg_class' => $chgmast->chgclass,
                            'unitprce' => $request->unitprce,
                            'quantity' => $request->quantity,
                            'amount' => $request->amount,
                            'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                            'chggroup' => $chgmast->chggroup,
                            'taxamount' => $request->taxamount,
                            'uom' => $request->uom,
                            'uom_recv' => $request->uom_recv,
                            'invgroup' => $chgmast->invgroup,
                            'reqdept' => $request->deptcode,
                            'issdept' => $request->deptcode,
                            'invcode' => $chgmast->chggroup,
                            'inventory' => $updinv,
                            'updinv' =>  $updinv,
                            'discamt' => $request->discamount,
                            'qtyorder' => $request->quantity,
                            'qtyissue' => $request->quantity,
                            'unit' => session('unit'),
                            'chgtype' => $chgmast->chgtype,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'qtydispense' => $request->quantity,
                            'taxcode' => $request->taxcode,
                            'remarks' => $request->remarks,
                            'drugindicator' => $this->givenullifempty($request->drugindicator),
                            'frequency' => $this->givenullifempty($request->frequency),
                            'doscode' => $this->givenullifempty($request->doscode),
                            'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                            'addinstruction' => $this->givenullifempty($request->addinstruction),
                        ]);
            }else{

                $edit_lain_chggroup = false;

                $this->sysdb_log('update',$chargetrx_lama,'sysdb.chargetrxlog');

                DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('id', '=', $request->id)
                        ->update([
                            'trxdate' => $request->trxdate,
                            'chgcode' => $request->chgcode,
                            'chg_class' => $chgmast->chgclass,
                            'unitprce' => $request->unitprce,
                            'quantity' => $request->quantity,
                            'amount' => $request->amount,
                            'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                            'chggroup' => $chgmast->chggroup,
                            'taxamount' => $request->taxamount,
                            'uom' => $request->uom,
                            'uom_recv' => $request->uom_recv,
                            'invgroup' => $chgmast->invgroup,
                            'reqdept' => $request->deptcode,
                            'issdept' => $request->deptcode,
                            'invcode' => $chgmast->chggroup,
                            'inventory' => $updinv,
                            'updinv' =>  $updinv,
                            'discamt' => $request->discamount,
                            'qtyorder' => $request->quantity,
                            'qtyissue' => $request->quantity,
                            'unit' => session('unit'),
                            'chgtype' => $chgmast->chgtype,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'qtydispense' => $request->quantity,
                            'taxcode' => $request->taxcode,
                            'remarks' => $request->remarks,
                            'drugindicator' => $this->givenullifempty($request->drugindicator),
                            'frequency' => $this->givenullifempty($request->frequency),
                            'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                            'addinstruction' => $this->givenullifempty($request->addinstruction),
                        ]);
            }
            
            $chargetrx_obj = db::table('hisdb.chargetrx')
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$request->id)
                            ->first();
            
            $product = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$request->uom_recv)
                            ->where('itemcode','=',$request->chgcode);
            
            if($product->exists()){
                $stockloc = DB::table('material.stockloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$request->uom_recv)
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

                if($edit_lain_chggroup){
                    $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                    $this->crtgltran($ivdspdt_idno);
                }else{
                    if($ivdspdt->exists()){
                        $this->updivdspdt($chargetrx_obj);
                        $this->updgltran($chargetrx_obj,$updinv);
                    }else{
                        $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                        $this->crtgltran($chargetrx_obj,$updinv);
                    }
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
    }

    public function order_entry_del(Request $request){

        DB::beginTransaction();

        try {

            $chargetrx_obj = DB::table("hisdb.chargetrx")
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$request->id)
                            ->first();

            $chgmast_lama = DB::table('hisdb.chgmast')
                    ->where('compcode','=',session('compcode'))
                    ->where('uom','=',$chargetrx_obj->uom_recv)
                    ->where('chgcode','=',$chargetrx_obj->chgcode)
                    ->first();

            if($chgmast_lama->invflag != '0'){
                $this->delivdspdt($chargetrx_obj);
            }else{
                $this->delgltran($chargetrx_obj);
            }

            //pindah yang lama ke billsumlog sebelum update
            //recstatus->delete

            $chargetrx_lama = DB::table("hisdb.chargetrx")
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$request->id)
                            ->first();

            $this->sysdb_log('delete',$chargetrx_lama,'sysdb.chargetrxlog');

            DB::table("hisdb.chargetrx")
                    ->where('compcode','=',session('compcode'))
                    ->where('id','=',$request->id)
                    ->delete();

            DB::commit();

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;

            return json_encode($responce);

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
            ->where('uomcode','=',$chargetrx_obj->uom_recv)
            ->where('itemcode','=',$chargetrx_obj->chgcode);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$chargetrx_obj->uom_recv)
            ->where('itemcode','=',$chargetrx_obj->chgcode)
            ->where('deptcode','=',$chargetrx_obj->reqdept)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        // $convuom_recv = DB::table('material.uom')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('uomcode','=',$chargetrx_obj->uom_recv)
        //     ->first();
        // $convuom_recv = $convuom_recv->convfactor;

        // $conv_uom = DB::table('material.uom')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('uomcode','=',$chargetrx_obj->uom)
        //     ->first();
        // $conv_uom = $conv_uom->convfactor;

        if($stockloc->exists()){

            $prev_netprice = $product->first()->avgcost; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $product->first()->avgcost;
            // $curr_quan = $chargetrx_obj->quantity * ($conv_uom/$convuom_recv);
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
                                ->where('uomcode','=',$chargetrx_obj->uom_recv)
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
                ->where('UomCode','=',$chargetrx_obj->uom_recv)
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
            'txnqty' => $curr_quan,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        $ivdspdt_lama = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$chargetrx_obj->auditno)
                        ->first();

        $this->sysdb_log('update',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$chargetrx_obj->auditno)
            ->update($ivdspdt_arr);
    }

    public function updgltran($chargetrx_obj,$invflag){
        $my_uom = $chargetrx_obj->uom_recv;
        $my_chgcode = $chargetrx_obj->chgcode;
        $my_deptcode = $chargetrx_obj->reqdept;
        $my_auditno = $chargetrx_obj->auditno;
        $my_date = $chargetrx_obj->trxdate;

        if($invflag == 1){
            $ivdspdt = DB::table('material.ivdspdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$chargetrx_obj->auditno);

            $my_amount = $ivdspdt->amount;
        }else{
            $my_amount = $chargetrx_obj->amount;
        }

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$my_auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($my_date);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            $gltran->update([
                'amount' => $my_amount
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
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount + $my_amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $my_amount,
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
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount - $my_amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$my_amount,
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

        $my_uom = $chargetrx_obj->uom_recv;
        $my_chgcode = $chargetrx_obj->chgcode;
        $my_deptcode = $chargetrx_obj->reqdept;
        $my_year = defaultController::toYear($chargetrx_obj->trxdate);

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
            ->where('deptcode','=',$my_deptcode)
            ->where('year','=',$my_year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        // $convuom_recv = DB::table('material.uom')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('uomcode','=',$chargetrx_obj->uom_recv)
        //     ->first();
        // $convuom_recv = $convuom_recv->convfactor;

        // $conv_uom = DB::table('material.uom')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('uomcode','=',$chargetrx_obj->uom)
        //     ->first();
        // $conv_uom = $conv_uom->convfactor;

        $curr_netprice = $product->first()->avgcost;
        // $curr_quan = $chargetrx_obj->quantity * ($conv_uom/$convuom_recv);
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
                                ->where('year','=',$my_year)
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
                ->where('Year','=',$my_year)
                ->where('DeptCode','=',$my_deptcode)
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
            'uomcode' => $chargetrx_obj->uom_recv,
            'txnqty' => $curr_quan,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'productcat' => $product->first()->productcat,
            'issdept' => $chargetrx_obj->reqdept,
            'reqdept' => $chargetrx_obj->reqdept,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'trantype' => 'DS',
            'trandate' => $chargetrx_obj->trxdate,
            'trxaudno' => $chargetrx_obj->auditno,
            'mrn' => $this->givenullifempty($chargetrx_obj->mrn),
            'episno' => $this->givenullifempty($chargetrx_obj->episno),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];


        $insertGetId = DB::table('material.ivdspdt')
                            ->insertGetId($ivdspdt_arr);

        return $insertGetId;
    }

    public function crtgltran($chargetrx_obj,$invflag){

        $my_uom = $chargetrx_obj->uom_recv;
        $my_chgcode = $chargetrx_obj->chgcode;
        $my_deptcode = $chargetrx_obj->reqdept;
        $my_auditno = $chargetrx_obj->auditno;
        $my_date = $chargetrx_obj->trxdate;

        if($invflag == 1){
            $ivdspdt = DB::table('material.ivdspdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$chargetrx_obj->auditno);

            $my_amount = $ivdspdt->amount;
        }else{
            $my_amount = $chargetrx_obj->amount;
        }

        $yearperiod = $this->getyearperiod($my_date);

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', session('compcode'))
            ->where('unit','=', session('unit'))
            ->where('itemcode','=', $my_chgcode)
            ->first();

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$my_deptcode)
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
                'auditno' => $my_auditno,//billsum auditno
                'lineno_' => 1,
                'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => 'DS',
                'reference' => $my_uom,
                'description' => $my_chgcode, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $my_amount
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
                    'actamount'.$yearperiod->period => $my_amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $my_amount,
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
                    'actamount'.$yearperiod->period => $gltranAmount - $my_amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$my_amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function delivdspdt($chargetrx_obj){

        $my_uom = $chargetrx_obj->uom_recv;
        $my_chgcode = $chargetrx_obj->chgcode;
        $my_deptcode = $chargetrx_obj->reqdept;
        $my_year = defaultController::toYear($chargetrx_obj->trxdate);

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$chargetrx_obj->auditno);

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
            ->where('deptcode','=',$my_deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $ivdspdt_lama->first()->netprice; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($chargetrx_obj->trxdate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + floatval(floatval($prev_netprice) * floatval($prev_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $product
                ->update([
                    'qtyonhand' => $new_qoh,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('Year','=',$my_year)
                ->where('DeptCode','=',$my_deptcode)
                ->where('ItemCode','=',$my_chgcode)
                ->where('UomCode','=',$my_uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan);
                $expdate_obj
                        ->update([
                            'balqty' => $balqty
                        ]);

            }else{
                throw new \Exception("No stockexp");
            }

        }

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$chargetrx_obj->auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($ivdspdt_lama->first()->trandate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

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
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$OldAmount,
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
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => +$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$chargetrx_obj->auditno)
                ->delete();
        }

        // pindah ke ivdspdtlog
        // recstatus->delete
        $ivdspdt_lama = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$chargetrx_obj->auditno)
                        ->first();

        $this->sysdb_log('delete',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$chargetrx_obj->auditno)
            ->delete();
    }

    public function delgltran($chargetrx_obj){
        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$chargetrx_obj->auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($chargetrx_obj->trxdate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            // $gltran->update([
            //     'amount' => $ivdspdt->amount
            // ]);

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
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$OldAmount,
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
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => +$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$chargetrx_obj->auditno)
                ->delete();
        }
    }

    public function get_itemcode_uom_recv(Request $request){
        $chgcode = $request->chgcode;
        $deptcode = $request->deptcode;
        $entrydate = $request->entrydate;

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','uom.description','cm.uom as uomcode','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            ->where('cm.recstatus','<>','DELETE');

        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                        $join = $join->where('pt.compcode', '=', session('compcode'));
                        $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                        $join = $join->on('pt.uomcode', '=', 'cm.uom');
                        $join = $join->where('pt.unit', '=', session('unit'));
                    });

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            $table->Where('uom.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        if(!empty($request->searchCol2)){
            $searchCol_array = $request->searchCol2;
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
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
        }else{
            $table = $table->orderBy('uom.idno','desc');
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

    public function get_itemcode_uom_recv_check(Request $request){
        $chgcode = $request->chgcode;
        $deptcode = $request->deptcode;
        $uom = $request->filterVal[1];
        $chggroup = $request->filterVal[0];
        $entrydate = $request->entrydate;

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','uom.description','cm.uom as uomcode','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            ->where('cm.chggroup','=',$chggroup)
                            ->where('cm.uom','=',$uom)
                            ->where('cm.recstatus','<>','DELETE');

        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                        $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                        $join = $join->on('pt.uomcode', '=', 'cm.uom');
                        $join = $join->where('pt.compcode', '=', session('compcode'));
                        $join = $join->where('pt.unit', '=', session('unit'));
                    });

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            $table->Where('uom.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        if(!empty($request->searchCol2)){
            $searchCol_array = $request->searchCol2;
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }

        // if(!empty($request->filterCol)){
        //     foreach ($request->filterCol as $key => $value) {
        //         $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
        //     }
        // }

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
        }else{
            $table = $table->orderBy('uom.idno','desc');
        }
        

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_itemcode_price_check(Request $request){
        $deptcode = $request->deptcode;
        $priceuse = $request->price;
        $entrydate = $request->entrydate;
        $chgcode = $request->filterVal[1];
        $uom = $request->uom;

        switch ($priceuse) {
            case 'PRICE1':
                $cp_fld = 'amt1';
                break;
            case 'PRICE2':
                $cp_fld = 'amt2';
                break;
            case 'PRICE3':
                $cp_fld = 'amt3';
                break;
            default:
                $cp_fld = 'costprice';
                break;
        }

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE')
                        ->where('cm.chgcode','=',$chgcode)
                        ->where('cm.uom','=',$uom);

        $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });

        $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'cm.uom');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

        $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function sysdb_log($oper,$array,$log_table){
        $array_lama = (array)$array;
        $array_lama['logstatus'] = $oper;

        DB::table($log_table)
                ->insert($array_lama);
    }

    public function billtype_obj_get(Request $request){
        $billtype_obj = new stdClass();

        $billtymst = DB::table('hisdb.billtymst')
                        ->where('compcode','=',session('compcode'))
                        ->where('billtype','=',$request->billtype);

        if($billtymst->exists()){
            $billtype_obj->billtype = $billtymst->first();
            $billtype_obj->svc = [];

            $billtysvc = DB::table('hisdb.billtysvc')
                        ->where('compcode','=',session('compcode'))
                        ->where('billtype','=',$request->billtype);

            if($billtysvc->exists()){
                foreach ($billtysvc->get() as $key => $value) {
                    $billtysvc_obj = new stdClass();
                    $billtysvc_obj->chggroup = $value->chggroup;
                    $billtysvc_obj->svc = $value;

                    $billtyitem = DB::table('hisdb.billtyitem')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('billtype','=',$value->billtype)
                                    ->where('chggroup','=',$value->chggroup);

                    if($billtyitem->exists()){
                        $billtysvc_obj->item = $billtyitem->get()->toArray(); 
                    }
                    array_push($billtype_obj->svc, $billtysvc_obj);
                }
            }

            return $billtype_obj;

        }else{
            throw new \Exception("Wrong billtype");
        }
    }

}