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
            case 'get_itemcode_price':
                if(!empty($request->searchCol2)){
                    return $this->get_itemcode_price_2($request);
                }else{
                    return $this->get_itemcode_price($request);
                }
            case 'get_itemcode_uom_recv':
                return $this->get_itemcode_uom_recv($request);
                break;
            case 'get_itemcode_uom_recv_check':
                return $this->get_itemcode_uom_recv_check($request);
                break;
            case 'get_itemcode_price_check':
                return $this->get_itemcode_price_check($request);
                break;
            case 'get_ordcom_totamount':
                return $this->get_ordcom_totamount($request);
                break;
            case 'showpdf_detail':
                return $this->showpdf_detail($request);
                break;
            case 'showpdf_summ':
                return $this->showpdf_summ($request);
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

            case 'order_entry_pkg':

                switch($request->oper){
                    case 'add':
                        return $this->order_entry_pkg_add($request);
                    case 'edit':
                        return $this->order_entry_pkg_edit($request);
                    case 'del':
                        return $this->order_entry_pkg_del($request);
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
                    ->select('trx.auditno','trx.compcode','trx.idno','trx.mrn','trx.episno','trx.epistype','trx.trxtype','trx.docref','trx.trxdate','trx.chgcode','trx.billcode','trx.costcd','trx.revcd','trx.mmacode','trx.billflag','trx.billdate','trx.billtype','trx.doctorcode','trx.chg_class','trx.unitprce','trx.quantity','trx.amount','trx.trxtime','trx.chggroup','trx.qstat','trx.dracccode','trx.cracccode','trx.arprocess','trx.taxamount','trx.billno','trx.invno','trx.uom','trx.uom_recv','trx.billtime','trx.invgroup','trx.reqdept as deptcode','trx.issdept','trx.invcode','trx.resulttype','trx.resultstatus','trx.inventory','trx.updinv','trx.invbatch','trx.doscode','trx.duration','trx.instruction','trx.discamt','trx.disccode','trx.pkgcode','trx.remarks','trx.frequency','trx.ftxtdosage','trx.addinstruction','trx.qtyorder','trx.ipqueueno','trx.itemseqno','trx.doseqty','trx.freqqty','trx.isudept','trx.qtyissue','trx.durationcode','trx.reqdoctor','trx.unit','trx.agreementid','trx.chgtype','trx.adduser','trx.adddate','trx.lastuser','trx.lastupdate','trx.daytaken','trx.qtydispense','trx.takehomeentry','trx.latechargesentry','trx.taxcode','trx.recstatus','trx.drugindicator','trx.id','trx.patmedication','trx.mmaprice','pt.avgcost as cost_price','pt.avgcost as cost_price','dos.dosedesc as doscode_desc','fre.freqdesc as frequency_desc','ins.description as addinstruction_desc','dru.description as drugindicator_desc','cm.brandname')
                    ->where('trx.mrn' ,'=', $request->mrn)
                    ->where('trx.episno' ,'=', $request->episno)
                    ->where('trx.compcode','=',session('compcode'))
                    ->where('trx.recstatus','<>','DELETE')
                    ->orderBy('trx.adddate', 'desc');

        if(strlen($request->chggroup) > 3){
            $table_chgtrx = $table_chgtrx->whereNotIn('trx.chggroup',explode(",",$request->chggroup));
        }else{
            $table_chgtrx = $table_chgtrx->where('trx.chggroup',$request->chggroup);
        }

        $table_chgtrx = $table_chgtrx->leftjoin('material.product as pt', function($join) use ($request){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'trx.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'trx.uom_recv');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });

        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->on('cm.chgcode', '=', 'trx.chgcode');
                            $join = $join->on('cm.uom', '=', 'trx.uom');
                            $join = $join->where('cm.unit', '=', session('unit'));
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
        $responce->sql_query = $this->getQueries($table_chgtrx);
        return json_encode($responce);
    }

    public function order_entry_add(Request $request){
        
        DB::beginTransaction();

        try {
            $new_quantity = $this->check_pkgmast_exists($request);

            if($new_quantity==0){
                DB::commit();

                return $this->get_ordcom_totamount($request);
            }else if($new_quantity==$request->quantity){

                $new_amount = $request->amount;
                $new_discamt = $request->discamt;
                $new_taxamount = $request->taxamount;

            }else{

                $new_amount = $new_quantity * $request->unitprce;
                $new_discamt = $this->calc_discamt($request,$new_quantity);
                $new_taxamount = $this->calc_taxamount($request,$new_discamt);
            }

            $recno = $this->recno('OE','IN');

            $chgmast = DB::table("hisdb.chgmast")
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',$request->chgcode)
                    ->where('uom','=',$request->uom)
                    ->first();

            $updinv = ($chgmast->invflag == '1')? 1 : 0;

            $invgroup = $this->get_invgroup($chgmast,$request->doctorcode);

            $insertGetId = DB::table("hisdb.chargetrx")
                    ->insertGetId([
                        'auditno' => $recno,
                        'compcode'  => session('compcode'),
                        'mrn'  => $request->mrn,
                        'episno'  => $request->episno,
                        'trxdate' => $request->trxdate,
                        'trxtype' => 'OE',
                        'chgcode' => $request->chgcode,
                        'billflag' => 0,
                        'mmacode' => $request->mmacode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $chgmast->chgclass,
                        'unitprce' => $request->unitprce,
                        'quantity' => $new_quantity,
                        'amount' => $new_amount,
                        'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'chggroup' => $chgmast->chggroup,
                        'taxamount' => $new_taxamount,
                        'uom' => $request->uom,
                        'uom_recv' => $request->uom_recv,
                        'invgroup' => $invgroup,
                        'reqdept' => $request->deptcode,
                        'issdept' => $request->deptcode,
                        'invcode' => $chgmast->chggroup,
                        'inventory' => $updinv,
                        'updinv' =>  $updinv,
                        'discamt' => $new_discamt,
                        'qtyorder' => $new_quantity,
                        'qtyissue' => $new_quantity,
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
                    if($updinv == 1){
                        $this->updivdspdt($chargetrx_obj);
                    }
                    $this->updgltran($chargetrx_obj,$updinv);
                }else{
                    if($updinv == 1){
                        $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                    }
                    $this->crtgltran($chargetrx_obj,$updinv);
                }
            }
            
            DB::commit();

            return $this->get_ordcom_totamount($request);
            
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

            if(!empty($request->uom_recv)){
                $chgmast = DB::table("hisdb.chgmast")
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chgcode)
                        ->where('uom','=',$request->uom_recv)
                        ->first();
                
                $updinv = ($chgmast->invflag == '1')? 1 : 0;
            }else{
                $chgmast = DB::table("hisdb.chgmast")
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chgcode)
                        ->first();

                $updinv = 0;
            }

            if($chargetrx_lama->chgcode != $request->chgcode || $chargetrx_lama->uom_recv != $request->uom_recv){

                $edit_lain_chggroup = true;
                $product_lama = DB::table('material.product')
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
                            'trxtype' => 'OE',
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
                            'discamt' => $request->discamt,
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
                            'discamt' => $request->discamt,
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
                    if($updinv == 1){
                        $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                    }
                    $this->crtgltran($chargetrx_obj,$updinv);
                }else{
                    if($ivdspdt->exists()){
                        if($updinv == 1){
                            $this->updivdspdt($chargetrx_obj);
                        }
                        $this->updgltran($chargetrx_obj,$updinv);
                    }else{
                        if($updinv == 1){
                            $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                        }
                        $this->crtgltran($chargetrx_obj,$updinv);
                    }
                }
            }
            
            DB::commit();

            return $this->get_ordcom_totamount($request);
            
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

    public function calc_taxamount(Request $request,$new_amount,$new_discamt){
        $taxmast = DB::table('hisdb.taxmast')
                        ->where('compcode',session('compcode'))
                        ->where('taxcode',$request->taxcode)
                        ->first();

        $rate = floatval($taxmast->rate);

        $taxamount = ($new_amount + $new_discamt) * $rate / 100;
    }

    public function calc_discamt(Request $request,$new_quantity){
        $episode = DB::table('hisdb.episode')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->first();

        $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode',session('compcode'))
                        ->where('chgcode',$request->chgcode)
                        ->where('uom',$request->uom)
                        ->first();

        $billtype = DB::table('hisdb.billtymst as bm')
                        ->select(
                            'bm.billtype as bm_billtype',
                            'bm.service as bm_service',
                            'bm.percent_ as bm_percent',
                            'bm.amount as bm_amount',
                            'bs.chggroup as bs_chggroup',
                            'bs.allitem as bs_allitem',
                            'bs.percent_ as bs_percent',
                            'bs.amount as bs_amount',
                            'bi.chgcode as bi_chgcode',
                            'bi.percent_ as bi_percent',
                            'bi.amount as bi_amount'
                        )
                        ->leftjoin('hisdb.billtysvc as bs', function($join){
                            $join = $join->where('bs.compcode', '=', session('compcode'));
                            $join = $join->on('bs.billtype', '=', 'bm.billtype');
                            $join = $join->where('bm.service', '=', '0');
                        })
                        ->leftjoin('hisdb.billtyitem as bi', function($join){
                            $join = $join->where('bi.compcode', '=', session('compcode'));
                            $join = $join->on('bi.billtype', '=', 'bs.billtype');
                            $join = $join->where('bs.allitem', '=', '0');
                            $join = $join->on('bi.chggroup', '=', 'bs.chggroup');
                        })
                        ->where('bm.compcode',session('compcode'))
                        ->where('bm.effdatefrom', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
                        ->where(function($join){
                               $join = $join->where('bm.effdateto', '>=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                               $join = $join->orWhereNull('bm.effdateto');
                         })
                        ->where('bm.recstatus','ACTIVE')
                        ->where('bm.billtype',$episode->billtype)
                        ->orderBy('bm.idno','desc')
                        ->get();

            $percent = 100;
            $amount = 0;

            foreach ($billtype as $key => $value) {
                if($chgmast->chggroup == $value->bs_chggroup){
                    $percent = $value->bs_percent;
                    $amount = $value->bs_amount;
                    if($chgmast->chgcode == $value->chgcode){
                        $percent = $value->bi_percent;
                        $amount = $value->bi_amount;
                    }
                }
            }

            $discamount = ((((100-$percent)/100)*floatval($request->unitprce)*-1)*$new_quantity) - $amount;

            // var percent=(get_billtype_main.length>0)?get_billtype_main[0].bm_percent:100;
            // var amount=(get_billtype_main.length>0)?get_billtype_main[0].bm_amount:0;
            // get_billtype_main.forEach(function(e,i){
            //     if(e.bs_chggroup == chggroup){
            //         percent = e.bs_percent;
            //         amount = e.bs_amount;
            //         if(e.bi_chgcode == chgcode){
            //             percent = e.bi_percent;
            //             amount = e.bi_amount;
            //         }
            //     }
            // });

            // var discamount = ((((100-percent)/100)*unitprce*-1)*quantity) - amount;

            return $discamount;
    }

    public function order_entry_pkg_add(Request $request){
        
        DB::beginTransaction();

        try {
            $recno = $this->recno('OE','PK');

            if(!empty($request->uom)){
                $chgmast = DB::table("hisdb.chgmast")
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chgcode)
                        ->where('uom','=',$request->uom)
                        ->first();

                $updinv = ($chgmast->invflag == '1')? 1 : 0;
            }else{
                $chgmast = DB::table("hisdb.chgmast")
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chgcode)
                        ->first();
                $updinv = 0;
            }

            $invgroup = $this->get_invgroup($chgmast,$request->doctorcode);

            $insertGetId = DB::table("hisdb.chargetrx")
                    ->insertGetId([
                        'auditno' => $recno,
                        'compcode'  => session('compcode'),
                        'mrn'  => $request->mrn,
                        'episno'  => $request->episno,
                        'trxdate' => $request->trxdate,
                        'trxtype' => 'PK',
                        'chgcode' => $request->chgcode,
                        'billflag' => 0,
                        'mmacode' => $request->mmacode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $chgmast->chgclass,
                        'unitprce' => $request->unitprce,
                        'quantity' => $request->quantity,
                        'amount' => $request->amount,
                        'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'chggroup' => $chgmast->chggroup,
                        'taxamount' => $request->taxamount,
                        'uom' => $request->uom,
                        'uom_recv' => $request->uom_recv,
                        'invgroup' => $invgroup,
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
            
            $pkgmast = DB::table('hisdb.pkgmast')
                            ->where('compcode','=',session('compcode'))
                            ->where('pkgcode','=',$request->chgcode)
                            ->whereDate('effectdate','<=', Carbon::now("Asia/Kuala_Lumpur"))
                            ->orderBy('effectdate','desc');
            
            if($pkgmast->exists()){
                $pkgmast = $pkgmast->first();

                $pkgdet = DB::table('hisdb.pkgdet')
                    ->where('compcode','=',session('compcode'))
                    ->where('pkgcode','=',$pkgmast->pkgcode)
                    ->where('effectdate','=',$pkgmast->effectDate)
                    ->get();

                foreach ($pkgdet as $key_pkgdet => $value_pkgdet) {
                        $this->order_entry_pkgdet_add($pkgmast,$value_pkgdet,$request);
                }

            }else{
                throw new \Exception("Package Doesnt exists or not yet effective");
            }
            
            DB::commit();

            return $this->get_ordcom_totamount($request);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
    }

    public function order_entry_pkgdet_add($pkgmast,$pkgdet,Request $request){

        if($pkgmast->autopull == 1){
            $recno = $this->recno('OE','PD');

            $chgmast = DB::table("hisdb.chgmast")
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',$pkgdet->chgcode)
                    ->where('uom','=',$pkgdet->uom)
                    ->first();

            $updinv = ($chgmast->invflag == '1')? 1 : 0;

            $invgroup = $this->get_invgroup($chgmast,$request->doctorcode);

            $insertGetId = DB::table("hisdb.chargetrx")
                ->insertGetId([
                    'auditno' => $recno,
                    'compcode'  => session('compcode'),
                    'mrn'  => $request->mrn,
                    'episno'  => $request->episno,
                    'trxdate' => $request->trxdate,
                    'trxtype' => 'PD',
                    'chgcode' => $pkgdet->chgcode,
                    'billflag' => 0,
                    'mmacode' => $request->mmacode,
                    'doctorcode' => $request->doctorcode,
                    'chg_class' => $chgmast->chgclass,
                    'unitprce' => $pkgdet->pkgprice1,
                    'quantity' => $pkgdet->quantity,
                    'amount' => $pkgdet->totprice1,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chggroup' => $chgmast->chggroup,
                    // 'taxamount' => $request->taxamount,
                    'uom' => $pkgdet->uom,
                    'uom_recv' => $pkgdet->uom,
                    'invgroup' => $invgroup,
                    'reqdept' => $request->deptcode,
                    'issdept' => $request->deptcode,
                    'invcode' => $chgmast->chggroup,
                    'inventory' => $updinv,
                    'updinv' =>  $updinv,
                    // 'discamt' => $request->discamt,
                    'qtyorder' => $pkgdet->quantity,
                    'qtyissue' => $pkgdet->quantity,
                    'unit' => session('unit'),
                    'chgtype' => $chgmast->chgtype,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'qtydispense' => $pkgdet->quantity,
                    // 'taxcode' => $request->taxcode,
                    'remarks' => $request->remarks,
                    'recstatus' => 'POSTED',
                    // 'doctorcode' => $this->givenullifempty($request->doctorcode),
                    // 'drugindicator' => $this->givenullifempty($request->drugindicator),
                    // 'frequency' => $this->givenullifempty($request->frequency),
                    // 'doscode' => $this->givenullifempty($request->doscode),
                    // 'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                    // 'addinstruction' => $this->givenullifempty($request->addinstruction)
                ]);

                $chargetrx_obj = db::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('id', '=', $insertGetId)
                        ->first();
        
                $product = DB::table('material.product')
                                ->where('compcode','=',session('compcode'))
                                ->where('itemcode','=',$pkgdet->chgcode)
                                ->where('uomcode','=',$pkgdet->uom);
                
                if($product->exists()){
                    $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$chargetrx_obj->auditno);
                    
                    if($updinv == 1){
                        $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                    }
                    $this->crtgltran($chargetrx_obj,$updinv);
                }

            $qtybal_ = 0;
        }else{
            $qtybal_ = $pkgdet->quantity;
        }

        DB::table("hisdb.pkgpat")
            ->insert([
                'compcode' => session('compcode'),
                'mrn' => $request->mrn,
                'episno' => $request->episno,
                'pkgcode' => $pkgmast->pkgcode,
                'pkgqty' => $pkgdet->quantity,
                'pkgprice' => $pkgdet->pkgprice1,
                'pkgtotprice' => $pkgdet->totprice1,
                'chgcode' => $pkgdet->chgcode,
                'uom' => $pkgdet->uom,
                'doctorcode' => $request->doctorcode,
                // 'ipacccode' => $request-> ,
                // 'opacccode' => $request-> ,
                // 'qtyused' => $request-> ,
                'qtybal' => $qtybal_,
                // 'amtbal' => $request-> ,
                // 'updategl' => $request-> ,
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'costcd' => $request-> ,
                // 'memberno' => $request-> ,
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'adduser' => session('username'),
                // 'agreementdate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'expirydate' => $request-> ,
                // 'AgreementID' => $request-> ,
            ]);
    }

    public function check_pkgmast_exists(Request $request){
        $pkgmast = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('trxtype', 'PK');

        if($pkgmast->exists()){
            $pkgpat = DB::table('hisdb.pkgpat')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('qtybal','>',0);

            if($pkgpat->exists()){
                $pkgpat = $pkgpat->first();
                $new_qtybal = $pkgpat->qtybal - $request->quantity;

                if($new_qtybal < 0){
                    $quantity_ret = $new_qtybal * -1;
                    $qtyused = $pkgpat->qtybal;
                    $new_qtybal = 0;
                }else{
                    $quantity_ret = 0;
                    $qtyused = $request->quantity;
                }

                $recno = $this->recno('OE','PD');

                $chgmast = DB::table("hisdb.chgmast")
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$pkgpat->chgcode)
                        ->where('uom','=',$pkgpat->uom)
                        ->first();

                $updinv = ($chgmast->invflag == '1')? 1 : 0;

                $invgroup = $this->get_invgroup($chgmast,$request->doctorcode);

                $insertGetId = DB::table("hisdb.chargetrx")
                    ->insertGetId([
                        'auditno' => $recno,
                        'compcode'  => session('compcode'),
                        'mrn'  => $request->mrn,
                        'episno'  => $request->episno,
                        'trxdate' => $request->trxdate,
                        'trxtype' => 'PD',
                        'chgcode' => $pkgpat->chgcode,
                        'billflag' => 0,
                        'mmacode' => $request->mmacode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $chgmast->chgclass,
                        'unitprce' => $pkgpat->pkgprice,
                        'quantity' => $request->quantity,
                        'amount' => $pkgpat->pkgprice * $request->quantity,
                        'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'chggroup' => $chgmast->chggroup,
                        // 'taxamount' => $request->taxamount,
                        'uom' => $pkgpat->uom,
                        'uom_recv' => $pkgpat->uom,
                        'invgroup' => $invgroup,
                        'reqdept' => $request->deptcode,
                        'issdept' => $request->deptcode,
                        'invcode' => $chgmast->chggroup,
                        'inventory' => $updinv,
                        'updinv' =>  $updinv,
                        // 'discamt' => $request->discamt,
                        'qtyorder' => $request->quantity,
                        'qtyissue' => $request->quantity,
                        'unit' => session('unit'),
                        'chgtype' => $chgmast->chgtype,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'qtydispense' => $request->quantity,
                        // 'taxcode' => $request->taxcode,
                        // 'remarks' => $request->remarks,
                        'recstatus' => 'POSTED',
                        // 'doctorcode' => $this->givenullifempty($request->doctorcode),
                        // 'drugindicator' => $this->givenullifempty($request->drugindicator),
                        // 'frequency' => $this->givenullifempty($request->frequency),
                        // 'doscode' => $this->givenullifempty($request->doscode),
                        // 'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                        // 'addinstruction' => $this->givenullifempty($request->addinstruction)
                    ]);

                $chargetrx_obj = db::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('id', '=', $insertGetId)
                        ->first();
        
                $product = DB::table('material.product')
                                ->where('compcode','=',session('compcode'))
                                ->where('itemcode','=',$pkgpat->chgcode)
                                ->where('uomcode','=',$pkgpat->uom);
                
                if($product->exists()){
                    $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$chargetrx_obj->auditno);
                    
                    if($updinv == 1){
                        $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
                    }
                    $this->crtgltran($chargetrx_obj,$updinv);
                }


                if($new_qtybal >= 0){
                    DB::table('hisdb.pkgpat')
                        ->where('compcode',session('compcode'))
                        ->where('idno', $pkgpat->idno)
                        ->update([
                            'qtybal' => $new_qtybal
                        ]);
                }

                return $quantity_ret;

            }else{
                return $request->quantity;
            }
        }else{
            return $request->quantity;
        }
    }

    public function get_invgroup($chgmast,$doctorcode){
        switch (strtoupper($chgmast->invgroup)) {
            case 'CC':
                return $chgmast->chgcode;
                break;
            case 'CG':
                return $chgmast->chggroup;
                break;
            case 'CT':
                return $chgmast->chgtype;
                break;
            case 'DC':
                return $doctorcode;
                break;
            default:
                return $chgmast->chggroup;
                break;
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
                ->where('recno','=',$chargetrx_obj->auditno)
                ->first();

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
                ->where('recno','=',$chargetrx_obj->auditno)
                ->first();

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

        if(!$ivdspdt_lama_->exists()){
            return false;
        }

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

    public function get_itemcode_price(Request $request){
        $deptcode = $request->deptcode;
        $priceuse = $request->price;
        $entrydate = $request->entrydate;
        $billtype_obj = $this->billtype_obj_get($request);
        $dfee = (!empty($request->dfee))?true:false;

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

        $table = DB::table('hisdb.chgmast as cm');

        if(!$dfee){

            $table = $table->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','uom.convfactor','cm.constype','cm.revcode','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost');
        }else{

            $table = $table->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','uom.convfactor','cm.constype','cm.revcode','dept.deptcode','doc.doctorcode','doc.doctorname');
        }

        $table = $table->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE');
                        // ->where(function ($query) {
                        //    $query->whereNotNull('st.idno')
                        //          ->orWhere('cm.invflag', '=', 0);
                        // });

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            if($request->from != 'chgcode_dfee'){
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            }
                            $join = $join->where('cp.effdate', '<=', $entrydate);
                        });

        if(!$dfee){
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
        }else{
            $table = $table->join('hisdb.doctor as doc', function($join){
                                $join = $join->on('doc.doctorcode', '=', 'cm.costcode');
                                $join = $join->where('doc.compcode', '=', session('compcode'));
                            });

            $table = $table->join('hisdb.discipline as disp', function($join){
                                $join = $join->on('disp.code', '=', 'doc.disciplinecode');
                                $join = $join->where('disp.compcode', '=', session('compcode'));
                            });

            $table = $table->join('sysdb.department as dept', function($join){
                                $join = $join->on('dept.deptcode', '=', 'disp.code');
                                $join = $join->where('dept.compcode', '=', session('compcode'));
                            });

        }

        $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.optax', '=', 'tm.taxcode');
                        });

        $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false && trim($request->searchVal[$key]) != '%%'){
                            $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                            $table->Where('cm.'.$searchCol_array[$key],'like',$search_);
                            // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            // $table->Where('cm.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
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
                    $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

        if(!empty($request->whereNotInCol)){
            foreach ($request->whereNotInCol as $key => $value) {
                $table = $table->whereNotIn($value,explode(",",$request->whereNotInVal[$key][0]));
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
            $table = $table->orderBy('cm.idno','desc');
        }

        $paginate = $table->paginate($request->rows);
        // dd($paginate);
        $rows = $paginate->items();

        foreach ($rows as $key => $value) {
            $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
            $value->billty_amount = $billtype_amt_percent->amount; 
            $value->billty_percent = $billtype_amt_percent->percent_;

            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode)
                ->where('cp.uom', '=', $value->uom)
                ->whereDate('cp.effdate', '<=', $entrydate)
                ->orderBy('cp.effdate','desc');

            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();

                if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                    unset($rows[$key]);
                    continue;
                }
            }
        }

        $rows = array_values($rows);

        //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        // $responce->rows = $paginate->items();
        $responce->rows = $rows;
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_itemcode_price_2(Request $request){
        $deptcode = $request->deptcode;
        $priceuse = $request->price;
        $entrydate = $request->entrydate;
        $serch_chgcode = substr($request->searchVal2[0], 1);
        $billtype_obj = $this->billtype_obj_get($request);

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
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                        ->Where('cm.chgcode','like',$serch_chgcode)
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE')
                        ->orderBy('cm.idno','desc');
                        // ->where(function ($query) {
                        //    $query->whereNotNull('st.idno')
                        //          ->orWhere('cm.invflag', '=', 0);
                        // });

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            if($request->from != 'chgcode_dfee'){
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            }
                            $join = $join->whereNotNull('cp.effdate');
                            $join = $join->where('cp.effdate', '<=', $entrydate);
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

        $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.optax', '=', 'tm.taxcode');
                        });

        $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

        $table_count = $table->count();

        if($table_count>0){
            $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->Where('cm.chgcode','like',$serch_chgcode)
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });

            $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                                $join = $join->on('cp.uom', '=', 'cm.uom');
                                if($request->from != 'chgcode_dfee'){
                                    $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                                }
                                $join = $join->where('cp.effdate', '<=', $entrydate);
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

            $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.optax', '=', 'tm.taxcode');
                            });
        
            $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

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
                $table = $table->orderBy('cm.idno','desc');
            }

            $paginate = $table->paginate($request->rows);
            $rows = $paginate->items();

            foreach ($rows as $key => $value) {
                $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
                $value->billty_amount = $billtype_amt_percent->amount; 
                $value->billty_percent = $billtype_amt_percent->percent_;

                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                    ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                    ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                    ->where('cp.compcode', '=', session('compcode'))
                    ->where('cp.chgcode', '=', $value->chgcode)
                    ->where('cp.uom', '=', $value->uom)
                    ->whereDate('cp.effdate', '<=', $entrydate)
                    ->orderBy('cp.effdate','desc');

                if($chgprice_obj->exists()){
                    $chgprice_obj = $chgprice_obj->first();

                    if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                        unset($rows[$key]);
                        continue;
                    }
                }
            }

            $rows = array_values($rows);

            //////////paginate/////////
            // $paginate = $table->paginate($request->rows);

            $responce = new stdClass();
            $responce->page = $paginate->currentPage();
            $responce->total = $paginate->lastPage();
            $responce->records = $paginate->total();
            // $responce->rows = $paginate->items();
            $responce->rows = $rows;
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            $responce->sql_query = $this->getQueries($table);

            return json_encode($responce);
        }else{

            $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.recstatus','<>','DELETE');
                            // ->where(function ($query) {
                            //    $query->whereNotNull('st.idno')
                            //          ->orWhere('cm.invflag', '=', 0);
                            // });

            $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                                $join = $join->on('cp.uom', '=', 'cm.uom');
                                if($request->from != 'chgcode_dfee'){
                                    $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                                }
                                $join = $join->where('cp.effdate', '<=', $entrydate);
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

            $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                                $join = $join->where('cp.compcode', '=', session('compcode'));
                                $join = $join->on('cp.optax', '=', 'tm.taxcode');
                            });

            $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

            if(!empty($request->searchCol)){
                $searchCol_array = $request->searchCol;

                $count = array_count_values($searchCol_array);

                foreach ($count as $key => $value) {
                    $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                    $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                        foreach ($searchCol_array as $key => $value) {
                            $found = array_search($key,$occur_ar);
                            if($found !== false && trim($request->searchVal[$key]) != '%%'){
                                $search_ = $this->begins_search_if(['itemcode','chgcode'],$searchCol_array[$key],$request->searchVal[$key]);
                                $table->Where('cm.'.$searchCol_array[$key],'like',$search_);
                                // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                                // $table->Where('cm.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
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
                        $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });

                if(count($searchCol_array)>2){
                    $table = $table->where(function($table) use ($searchCol_array, $request){
                        foreach ($searchCol_array as $key => $value) {
                            if($key<=1) continue;
                            // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                            $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
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
                $table = $table->orderBy('cm.idno','desc');
            }

            $paginate = $table->paginate($request->rows);
            $rows = $paginate->items();

            foreach ($rows as $key => $value) {
                $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
                $value->billty_amount = $billtype_amt_percent->amount; 
                $value->billty_percent = $billtype_amt_percent->percent_;

                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                    ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                    ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                    ->where('cp.compcode', '=', session('compcode'))
                    ->where('cp.chgcode', '=', $value->chgcode)
                    ->where('cp.uom', '=', $value->uom)
                    ->whereDate('cp.effdate', '<=', $entrydate)
                    ->orderBy('cp.effdate','desc');

                if($chgprice_obj->exists()){
                    $chgprice_obj = $chgprice_obj->first();

                    if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                        unset($rows[$key]);
                        continue;
                    }
                }
            }

            $rows = array_values($rows);

            //////////paginate/////////
            // $paginate = $table->paginate($request->rows);

            $responce = new stdClass();
            $responce->page = $paginate->currentPage();
            $responce->total = $paginate->lastPage();
            $responce->records = $paginate->total();
            // $responce->rows = $paginate->items();
            $responce->rows = $rows;
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            $responce->sql_query = $this->getQueries($table);

            return json_encode($responce);
        }

    }

    public function get_itemcode_uom_recv(Request $request){
        $chgcode = $request->chgcode;
        $deptcode = $request->deptcode;
        $entrydate = $request->entrydate;

        $invflag = DB::table('hisdb.chgmast as cm')
                        ->select('cm.invflag')
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.unit', '=', session('unit'))
                        ->where('cm.chgcode','=',$chgcode)
                        ->where('cm.recstatus','<>','DELETE')
                        ->first();

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description as chgdesc','uom.description','cm.uom as uomcode','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            ->where('cm.recstatus','<>','DELETE');

        $table = $table->join('material.uom as uom', function($join) use ($chgcode){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        if($invflag->invflag == '1'){
            $table = $table->join('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });
        }else{
            $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
                        });
        }

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
        if($request->from == 'uom_recv_oth'){
            $uom = $request->filterVal[0];
        }else{
            $uom = $request->filterVal[1];
            $chggroup = $request->filterVal[0];
        }
        $entrydate = $request->entrydate;

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','uom.description','cm.uom as uomcode','st.idno as st_idno','st.qtyonhand','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                            ->where('cm.compcode','=',session('compcode'))
                            ->where('cm.chgcode','=',$chgcode)
                            // ->where('cm.chggroup','=',$chggroup)
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
        if($request->from == 'chgcode_oth'){
            $chgcode = $request->filterVal[0];
        }else{
            $chgcode = $request->filterVal[1];
        }
        $uom = $request->uom;
        $dfee = $request->dfee;
        $billtype_obj = $this->billtype_obj_get($request);

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
                        ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE')
                        ->where('cm.chgcode','=',$chgcode);
        if(!$dfee){
            $table = $table->where('cm.uom','=',$uom);
        }

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            if($request->from != 'chgcode_dfee'){
                                $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            }
                            $join = $join->where('cp.effdate', '<=', $entrydate);
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

        $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.optax', '=', 'tm.taxcode');
                        });

        $table = $table->join('material.uom as uom', function($join){
                            $join = $join->on('uom.uomcode', '=', 'cm.uom')
                                        ->where('uom.compcode', '=', session('compcode'))
                                        ->where('uom.recstatus','=','ACTIVE');
                    });

        $rows = $table->get();

        foreach ($rows as $key => $value) {
            $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
            $value->billty_amount = $billtype_amt_percent->amount; 
            $value->billty_percent = $billtype_amt_percent->percent_;

            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode);
                if(!$dfee){
                    $chgprice_obj = $chgprice_obj->where('cp.uom', '=', $value->uom);
                }
            $chgprice_obj = $chgprice_obj->whereDate('cp.effdate', '<=', $entrydate)
                ->orderBy('cp.effdate','desc');

            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();

                if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                    unset($rows[$key]);
                    continue;
                }
            }
        }

        $responce = new stdClass();
        $responce->rows = $rows;
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function get_ordcom_totamount(Request $request){
        $chargetrx = DB::table('hisdb.chargetrx as trx')
                        ->select('trx.amount','trx.discamt','trx.taxamount')
                        ->where('trx.compcode',session('compcode'))
                        ->where('trx.trxtype','!=','PD')
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.recstatus','<>','DELETE')
                        ->get();

        $amount = $chargetrx->sum('amount');
        $discamt = $chargetrx->sum('discamt');
        $taxamount = $chargetrx->sum('taxamount');
        $totamount = $amount + $discamt + $taxamount;


        $responce = new stdClass();
        $responce->amount = $amount;
        $responce->discamt = $discamt;
        $responce->taxamount = $taxamount;
        $responce->totamount = $totamount;

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

    public function get_billtype_amt_percent($billtype_obj,$loop_item){
        $billtype_amt_percent = new stdClass();
        $billtype_amt_percent->amount = (empty($billtype_obj->billtype->amount))?0:$billtype_obj->billtype->amount;
        $billtype_amt_percent->percent_ = (empty($billtype_obj->billtype->percent_))?0:$billtype_obj->billtype->percent_;

        if(count($billtype_obj->svc) > 0){

            foreach ($billtype_obj->svc as $key_svc => $svc_obj) {
                if($svc_obj->chggroup == $loop_item->chggroup){
                    $billtype_amt_percent->amount = (empty($svc_obj->svc->amount))?0:$svc_obj->svc->amount;
                    $billtype_amt_percent->percent_ = (empty($svc_obj->svc->percent_))?0:$svc_obj->svc->percent_;

                    if(count($svc_obj->item) > 0){
                        foreach ($svc_obj->item as $key_item => $item_obj){
                            if($item_obj->chgcode == $loop_item->chgcode){
                                $billtype_amt_percent->amount = (empty($item_obj->amount))?0:$item_obj->amount;
                                $billtype_amt_percent->percent_ = (empty($item_obj->percent_))?0:$item_obj->percent_;
                                break;
                            }
                        }
                        break;
                    }
                    break;
                }
            }
        }
        return $billtype_amt_percent;
    }

    public function showpdf_detail(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;

        $patmast_episode = DB::table('hisdb.pat_mast as pm')
                                ->select('pm.mrn','pm.name','pm.newic','ep.reg_date','ep.episno','ep.reg_time','ep.pay_type','doc.doctorname as doc_name','dm.debtorcode','dm.name as debtorname','dm.address1','dm.address2','dm.address3','dm.address4','dm.contact','epayr.refno')
                                ->where('pm.compcode',session('compcode'))
                                ->where('pm.mrn',$mrn)
                                ->join('hisdb.episode as ep', function($join) use ($request){
                                    $join = $join->where('ep.compcode', '=', session('compcode'));
                                    $join = $join->on('ep.mrn', '=', 'pm.mrn');
                                    $join = $join->where('ep.episno', '=', $request->episno);
                                })
                                ->join('hisdb.epispayer as epayr', function($join) use ($request){
                                    $join = $join->where('epayr.compcode', '=', session('compcode'));
                                    $join = $join->on('epayr.mrn', '=', 'ep.mrn');
                                    $join = $join->on('epayr.episno', '=', 'ep.episno');
                                    $join = $join->where('epayr.LineNo','=','1');
                                })
                                ->join('hisdb.doctor as doc', function($join) use ($request){
                                    $join = $join->where('doc.compcode', '=', session('compcode'));
                                    $join = $join->on('doc.doctorcode', '=', 'ep.admdoctor');
                                })
                                ->join('debtor.debtormast as dm', function($join) use ($request){
                                    $join = $join->where('dm.compcode', '=', session('compcode'));
                                    $join = $join->on('dm.debtorcode', '=', 'ep.payer');
                                });

        if(!$patmast_episode->exists()){
            abort(403, 'Patient Not Exist');
        }

        $patmast_episode = $patmast_episode->first();
        // dd($patmast_episode);

        $chargetrx = DB::table('hisdb.chargetrx as trx')
                        ->select('trx.chgcode','trx.uom','chgm.description','trx.trxdate','trx.quantity','trx.amount','trx.discamt','trx.taxamount','chgm.invgroup','chgm.chgclass','chgc.description as chgc_desc','chgc.classlevel','chgg.description as chgg_desc','chgt.description as chgt_desc')
                        ->where('trx.compcode',session('compcode'))
                        ->where('trx.trxtype','!=','PD')
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('chgm.invgroup','desc')
                        ->orderBy('trx.adddate','asc')
                        ->join('hisdb.chgmast as chgm', function($join) use ($request){
                            $join = $join->where('chgm.compcode', '=', session('compcode'));
                            $join = $join->on('chgm.chgcode', '=', 'trx.chgcode');
                            $join = $join->on('chgm.uom', '=', 'trx.uom');
                        })
                        ->leftjoin('hisdb.chggroup as chgg', function($join) use ($request){
                            $join = $join->where('chgg.compcode', '=', session('compcode'));
                            $join = $join->on('chgg.grpcode', '=', 'chgm.chggroup');
                        })
                        ->leftjoin('hisdb.chgtype as chgt', function($join) use ($request){
                            $join = $join->where('chgt.compcode', '=', session('compcode'));
                            $join = $join->on('chgt.chgtype', '=', 'chgm.chgtype');
                        })
                        ->leftjoin('hisdb.chgclass as chgc', function($join) use ($request){
                            $join = $join->where('chgc.compcode', '=', session('compcode'));
                            $join = $join->on('chgc.classcode', '=', 'chgm.chgclass');
                        })
                        ->get();

        // dd($chargetrx);

        foreach ($chargetrx as $key => $value) {
            if(strtoupper($value->invgroup) == 'CC'){
                $value->pdescription = $value->description;
            }else if(strtoupper($value->invgroup) == 'CT'){
                $value->pdescription = $value->chgt_desc;
            }else{
                $value->pdescription = $value->chgg_desc;
            }
        }

        $chgclass = $chargetrx->unique('chgclass')->sortBy('classlevel');
        $invgroup = $chargetrx->unique('pdescription');
        $username = session('username');
        $footer = '';
        $footer_ = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','PB')
                        ->where('trantype','note');

        if($footer_->exists()){
            $footer_ = $footer_->first();
            $footer = $footer_->description;
        } 

        return view('hisdb.ordcom.cb_summary_detail',compact('patmast_episode','chargetrx','chgclass','invgroup','username','footer'));

    }

    public function showpdf_summ(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;

        $patmast_episode = DB::table('hisdb.pat_mast as pm')
                                ->select('pm.mrn','pm.name','pm.newic','ep.reg_date','ep.episno','ep.reg_time','ep.pay_type','doc.doctorname as doc_name','dm.debtorcode','dm.name as debtorname','dm.address1','dm.address2','dm.address3','dm.address4','dm.contact','epayr.refno')
                                ->where('pm.compcode',session('compcode'))
                                ->where('pm.mrn',$mrn)
                                ->join('hisdb.episode as ep', function($join) use ($request){
                                    $join = $join->where('ep.compcode', '=', session('compcode'));
                                    $join = $join->on('ep.mrn', '=', 'pm.mrn');
                                    $join = $join->where('ep.episno', '=', $request->episno);
                                })
                                ->join('hisdb.epispayer as epayr', function($join) use ($request){
                                    $join = $join->where('epayr.compcode', '=', session('compcode'));
                                    $join = $join->on('epayr.mrn', '=', 'ep.mrn');
                                    $join = $join->on('epayr.episno', '=', 'ep.episno');
                                    $join = $join->where('epayr.LineNo','=','1');
                                })
                                ->join('hisdb.doctor as doc', function($join) use ($request){
                                    $join = $join->where('doc.compcode', '=', session('compcode'));
                                    $join = $join->on('doc.doctorcode', '=', 'ep.admdoctor');
                                })
                                ->join('debtor.debtormast as dm', function($join) use ($request){
                                    $join = $join->where('dm.compcode', '=', session('compcode'));
                                    $join = $join->on('dm.debtorcode', '=', 'ep.payer');
                                });

        if(!$patmast_episode->exists()){
            abort(403, 'Patient Not Exist');
        }

        $patmast_episode = $patmast_episode->first();
        // dd($patmast_episode);

        $chargetrx = DB::table('hisdb.chargetrx as trx')
                        ->select('trx.chgcode','trx.uom','chgm.description','trx.trxdate','trx.quantity','trx.amount','trx.discamt','trx.taxamount','chgm.invgroup','chgm.chgclass','chgc.description as chgc_desc','chgc.classlevel','chgg.description as chgg_desc','chgt.description as chgt_desc','doc.doctorname','doc.doctorcode')
                        ->where('trx.compcode',session('compcode'))
                        ->where('trx.trxtype','!=','PD')
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('chgm.invgroup','desc')
                        ->orderBy('trx.adddate','asc')
                        ->join('hisdb.chgmast as chgm', function($join) use ($request){
                            $join = $join->where('chgm.compcode', '=', session('compcode'));
                            $join = $join->on('chgm.chgcode', '=', 'trx.chgcode');
                            $join = $join->on('chgm.uom', '=', 'trx.uom');
                        })
                        ->leftjoin('hisdb.chggroup as chgg', function($join) use ($request){
                            $join = $join->where('chgg.compcode', '=', session('compcode'));
                            $join = $join->on('chgg.grpcode', '=', 'chgm.chggroup');
                        })
                        ->leftjoin('hisdb.chgtype as chgt', function($join) use ($request){
                            $join = $join->where('chgt.compcode', '=', session('compcode'));
                            $join = $join->on('chgt.chgtype', '=', 'chgm.chgtype');
                        })
                        ->leftjoin('hisdb.doctor as doc', function($join) use ($request){
                            $join = $join->where('doc.compcode', '=', session('compcode'));
                            $join = $join->on('doc.doctorcode', '=', 'trx.doctorcode');
                        })
                        ->leftjoin('hisdb.chgclass as chgc', function($join) use ($request){
                            $join = $join->where('chgc.compcode', '=', session('compcode'));
                            $join = $join->on('chgc.classcode', '=', 'chgm.chgclass');
                        })
                        ->get();

        // dd($chargetrx);

        foreach ($chargetrx as $key => $value) {
            if(strtoupper($value->invgroup) == 'CC'){
                $value->pdescription = $value->description;
            }else if(strtoupper($value->invgroup) == 'CT'){
                $value->pdescription = $value->chgt_desc;
            }else{
                $value->pdescription = $value->chgg_desc;
            }
        }

        $chgclass = $chargetrx->unique('chgclass')->sortBy('classlevel');
        $invgroup = $chargetrx->unique('pdescription');
        $username = session('username');
        $footer = '';
        $footer_ = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','PB')
                        ->where('trantype','note');

        if($footer_->exists()){
            $footer_ = $footer_->first();
            $footer = $footer_->description;
        } 

        return view('hisdb.ordcom.cb_summary_summ',compact('patmast_episode','chargetrx','chgclass','invgroup','username','footer'));

    }

}