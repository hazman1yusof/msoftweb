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
            case 'ordcom_table_pkgdet':
                return $this->ordcom_table_pkgdet($request);
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
            case 'final_bill_invoice':
                return $this->final_bill_invoice($request);
                break;
            case 'showpdf_summ_final': //sama je mcm showpdf_summ
                return $this->showpdf_summ_final($request);
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

            case 'final_bill':
                return $this->final_bill_init($request);

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
        if(empty($request->mrn) || empty($request->episno)){
            return abort(403,'No MRN or Episno');
        }

        $table_chgtrx = DB::table('hisdb.chargetrx as trx')
                    ->select('trx.auditno','trx.compcode','trx.idno','trx.mrn','trx.episno','trx.epistype','trx.trxtype','trx.docref','trx.trxdate','trx.chgcode','trx.billcode','trx.costcd','trx.revcd','trx.mmacode','trx.billflag','trx.billdate','trx.billtype','trx.doctorcode','doc.doctorname','trx.chg_class','trx.unitprce','trx.quantity','trx.amount','trx.trxtime','trx.chggroup','trx.qstat','trx.dracccode','trx.cracccode','trx.arprocess','trx.taxamount','trx.billno','trx.invno','trx.uom','trx.uom_recv','trx.billtime','trx.invgroup','trx.reqdept as deptcode','trx.issdept','trx.invcode','trx.resulttype','trx.resultstatus','trx.inventory','trx.updinv','trx.invbatch','trx.doscode','trx.duration','trx.instruction','trx.discamt','trx.disccode','trx.pkgcode','trx.remarks','trx.frequency','trx.ftxtdosage','trx.addinstruction','trx.qtyorder','trx.ipqueueno','trx.itemseqno','trx.doseqty','trx.freqqty','trx.isudept','trx.qtyissue','trx.durationcode','trx.reqdoctor','trx.unit','trx.agreementid','trx.chgtype','trx.adduser','trx.adddate','trx.lastuser','trx.lastupdate','trx.daytaken','trx.qtydispense','trx.takehomeentry','trx.latechargesentry','trx.taxcode','trx.recstatus','trx.drugindicator','trx.id','trx.patmedication','trx.mmaprice','pt.avgcost as cost_price','pt.avgcost as cost_price','dos.dosedesc as doscode_desc','fre.freqdesc as frequency_desc','ins.description as addinstruction_desc','dru.description as drugindicator_desc','cm.brandname')
                    ->where('trx.mrn' ,'=', $request->mrn)
                    ->where('trx.episno' ,'=', $request->episno)
                    ->where('trx.compcode','=',session('compcode'))
                    ->where('trx.recstatus','<>','DELETE')
                    ->orderBy('trx.adddate', 'desc');

        if(strlen($request->chggroup) > 5){
            $table_chgtrx = $table_chgtrx->whereNotIn('trx.chggroup',explode(",",$request->chggroup));
        }else{
            if(str_contains($request->chggroup, ',')){
                $table_chgtrx = $table_chgtrx->whereIn('trx.chggroup',explode(",",$request->chggroup));
            }else{
                $table_chgtrx = $table_chgtrx->where('trx.chggroup',$request->chggroup);
            }
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

        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.doctor as doc', function($join) use ($request){
                            $join = $join->where('doc.compcode', '=', session('compcode'));
                            $join = $join->on('doc.doctorcode', '=', 'trx.doctorcode');
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

    public function ordcom_table_pkgdet(Request $request){
        if($request->rows == null){
            $request->rows = 100;
        }
        if(empty($request->id)){
            return abort(404);
        }

        $pkg_chgtrx = DB::table('hisdb.chargetrx as trx')
                        ->where('compcode','=',session('compcode'))
                        ->where('id','=',$request->id)
                        ->first();

        $table_pkgpat = DB::table('hisdb.pkgpat')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn' ,'=', $pkg_chgtrx->mrn)
                    ->where('episno' ,'=', $pkg_chgtrx->episno)
                    ->where('pkgcode' ,'=', $pkg_chgtrx->chgcode)
                    ->orderBy('idno', 'desc');

        //////////paginate/////////

        $paginate = $table_pkgpat->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_pkgpat->toSql();
        $responce->sql_bind = $table_pkgpat->getBindings();
        $responce->sql_query = $this->getQueries($table_pkgpat);
        return json_encode($responce);
    }

    public function order_entry_add(Request $request){
        
        DB::beginTransaction();

        try {
            
            if($this->check_pkgdet_exists($request)){

                DB::commit();

                return $this->get_ordcom_totamount($request);
            }

            $chg_oe = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'OE');

            if(!$chg_oe->exists()){
                //add
                $quan_oe = $request->quantity;
                $this->add_chargetrx_oe($request,$quan_oe);
            }else if($chg_oe->exists()){
                //upd
                $quan_oe = $request->quantity + $chg_oe->first()->quantity;
                $this->upd_chargetrx_oe($request,$quan_oe);
            }

            // if($new_quantity==0){
            //     DB::commit();

            //     return $this->get_ordcom_totamount($request);
            // }else if($new_quantity==$request->quantity){

            //     $new_amount = $request->amount;
            //     $new_discamt = $request->discamt;
            //     $new_taxamount = $request->taxamount;

            // }else{

            //     $new_amount = $new_quantity * $request->unitprce;
            //     $new_discamt = $this->calc_discamt($request,$new_quantity);
            //     $new_taxamount = $this->calc_taxamount($request,$new_amount,$new_discamt);
            // }

            // $recno = $this->recno('OE','IN');

            // $chgmast = DB::table("hisdb.chgmast")
            //         ->where('compcode','=',session('compcode'))
            //         ->where('chgcode','=',$request->chgcode)
            //         ->where('uom','=',$request->uom)
            //         ->first();

            // $updinv = ($chgmast->invflag == '1')? 1 : 0;

            // $invgroup = $this->get_invgroup($chgmast,$request->doctorcode);

            // $insertGetId = DB::table("hisdb.chargetrx")
            //         ->insertGetId([
            //             'auditno' => $recno,
            //             'compcode'  => session('compcode'),
            //             'mrn'  => $request->mrn,
            //             'episno'  => $request->episno,
            //             'trxdate' => $request->trxdate,
            //             'trxtype' => 'OE',
            //             'chgcode' => $request->chgcode,
            //             'billflag' => 0,
            //             'mmacode' => $request->mmacode,
            //             'doctorcode' => $request->doctorcode,
            //             'chg_class' => $chgmast->chgclass,
            //             'unitprce' => $request->unitprce,
            //             'quantity' => $new_quantity,
            //             'amount' => $new_amount,
            //             'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'chggroup' => $chgmast->chggroup,
            //             'taxamount' => $new_taxamount,
            //             'uom' => $request->uom,
            //             'uom_recv' => $request->uom_recv,
            //             'invgroup' => $invgroup,
            //             'reqdept' => $request->deptcode,
            //             'issdept' => $request->deptcode,
            //             'invcode' => $chgmast->chggroup,
            //             'inventory' => $updinv,
            //             'updinv' =>  $updinv,
            //             'discamt' => $new_discamt,
            //             'qtyorder' => $new_quantity,
            //             'qtyissue' => $new_quantity,
            //             'unit' => session('unit'),
            //             'chgtype' => $chgmast->chgtype,
            //             'adduser' => session('username'),
            //             'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'lastuser' => session('username'),
            //             'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'qtydispense' => $request->quantity,
            //             'taxcode' => $request->taxcode,
            //             'remarks' => $request->remarks,
            //             'recstatus' => 'POSTED',
            //             'doctorcode' => $this->givenullifempty($request->doctorcode),
            //             'drugindicator' => $this->givenullifempty($request->drugindicator),
            //             'frequency' => $this->givenullifempty($request->frequency),
            //             'doscode' => $this->givenullifempty($request->doscode),
            //             'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
            //             'addinstruction' => $this->givenullifempty($request->addinstruction)
            //         ]);
            
            // $chargetrx_obj = db::table('hisdb.chargetrx')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('id', '=', $insertGetId)
            //                 ->first();
            
            // $product = DB::table('material.product')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('uomcode','=',$request->uom_recv)
            //                 ->where('itemcode','=',$request->chgcode);
            
            // if($product->exists()){
            //     // $stockloc = DB::table('material.stockloc')
            //     //         ->where('compcode','=',session('compcode'))
            //     //         ->where('uomcode','=',$request->uom_recv)
            //     //         ->where('itemcode','=',$request->chgcode)
            //     //         ->where('deptcode','=',$request->deptcode)
            //     //         ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                
            //     // if($stockloc->exists()){
            //     //     $stockloc = $stockloc->first();
            //     // }else{
            //     //     throw new \Exception("Stockloc not exists for item: ".$request->chgcode." dept: ".$request->deptcode." uom: ".$request->uom_recv,500);
            //     // }
                
            //     $ivdspdt = DB::table('material.ivdspdt')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('recno','=',$chargetrx_obj->auditno);
                
            //     if($ivdspdt->exists()){
            //         if($updinv == 1){
            //             $this->updivdspdt($chargetrx_obj);
            //         }
            //         $this->updgltran($chargetrx_obj,$updinv);
            //     }else{
            //         if($updinv == 1){
            //             $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
            //         }
            //         $this->crtgltran($chargetrx_obj,$updinv);
            //     }
            // }
            
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

            // if(!empty($request->uom_recv)){
            //     $chgmast = DB::table("hisdb.chgmast")
            //             ->where('compcode','=',session('compcode'))
            //             ->where('chgcode','=',$request->chgcode)
            //             ->where('uom','=',$request->uom_recv)
            //             ->first();
                
            //     $updinv = ($chgmast->invflag == '1')? 1 : 0;
            // }else{
            //     $chgmast = DB::table("hisdb.chgmast")
            //             ->where('compcode','=',session('compcode'))
            //             ->where('chgcode','=',$request->chgcode)
            //             ->first();

            //     $updinv = 0;
            // }

            if($chargetrx_lama->chgcode != $request->chgcode || $chargetrx_lama->uom_recv != $request->uom_recv){

                // $edit_lain_chggroup = true;
                $product_lama = DB::table('material.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$chargetrx_lama->uom_recv)
                        ->where('itemcode','=',$chargetrx_lama->chgcode);

                if($product_lama->exists()){
                    $this->delivdspdt($chargetrx_lama);
                }

                $chg_oe = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'OE');



                if(!$chg_oe->exists()){
                    //add
                    $quan_oe = $request->quantity;
                    $this->add_chargetrx_oe($request,$quan_oe);
                }else if($chg_oe->exists()){
                    //upd
                    $quan_oe = $request->quantity + $chg_oe->first()->quantity;
                    $this->upd_chargetrx_oe($request,$quan_oe);
                }

                // $this->sysdb_log('update',$chargetrx_lama,'sysdb.chargetrxlog');

                // DB::table('hisdb.chargetrx')
                //         ->where('compcode',session('compcode'))
                //         ->where('id', '=', $request->id)
                //         ->update([
                //             'trxdate' => $request->trxdate,
                //             'chgcode' => $request->chgcode,
                //             'chg_class' => $chgmast->chgclass,
                //             'unitprce' => $request->unitprce,
                //             'quantity' => $request->quantity,
                //             'amount' => $request->amount,
                //             'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                //             'trxtype' => 'OE',
                //             'chggroup' => $chgmast->chggroup,
                //             'taxamount' => $request->taxamount,
                //             'uom' => $request->uom,
                //             'uom_recv' => $request->uom_recv,
                //             'invgroup' => $chgmast->invgroup,
                //             'reqdept' => $request->deptcode,
                //             'issdept' => $request->deptcode,
                //             'invcode' => $chgmast->chggroup,
                //             'inventory' => $updinv,
                //             'updinv' =>  $updinv,
                //             'discamt' => $request->discamt,
                //             'qtyorder' => $request->quantity,
                //             'qtyissue' => $request->quantity,
                //             'unit' => session('unit'),
                //             'chgtype' => $chgmast->chgtype,
                //             'lastuser' => session('username'),
                //             'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                //             'qtydispense' => $request->quantity,
                //             'taxcode' => $request->taxcode,
                //             'remarks' => $request->remarks,
                //             'drugindicator' => $this->givenullifempty($request->drugindicator),
                //             'frequency' => $this->givenullifempty($request->frequency),
                //             'doscode' => $this->givenullifempty($request->doscode),
                //             'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                //             'addinstruction' => $this->givenullifempty($request->addinstruction),
                //         ]);
            }else{

                // $edit_lain_chggroup = false;

                $chg_oe = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'OE');

                if(!$chg_oe->exists()){
                    //add
                    $quan_oe = $request->quantity;
                    $this->add_chargetrx_oe($request,$quan_oe);
                }else if($chg_oe->exists()){
                    //upd
                    $quan_oe = $request->quantity;
                    $this->upd_chargetrx_oe($request,$quan_oe);
                }

                // $this->sysdb_log('update',$chargetrx_lama,'sysdb.chargetrxlog');

                // DB::table('hisdb.chargetrx')
                //         ->where('compcode',session('compcode'))
                //         ->where('id', '=', $request->id)
                //         ->update([
                //             'trxdate' => $request->trxdate,
                //             'chgcode' => $request->chgcode,
                //             'chg_class' => $chgmast->chgclass,
                //             'unitprce' => $request->unitprce,
                //             'quantity' => $request->quantity,
                //             'amount' => $request->amount,
                //             'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                //             'chggroup' => $chgmast->chggroup,
                //             'taxamount' => $request->taxamount,
                //             'uom' => $request->uom,
                //             'uom_recv' => $request->uom_recv,
                //             'invgroup' => $chgmast->invgroup,
                //             'reqdept' => $request->deptcode,
                //             'issdept' => $request->deptcode,
                //             'invcode' => $chgmast->chggroup,
                //             'inventory' => $updinv,
                //             'updinv' =>  $updinv,
                //             'discamt' => $request->discamt,
                //             'qtyorder' => $request->quantity,
                //             'qtyissue' => $request->quantity,
                //             'unit' => session('unit'),
                //             'chgtype' => $chgmast->chgtype,
                //             'lastuser' => session('username'),
                //             'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                //             'qtydispense' => $request->quantity,
                //             'taxcode' => $request->taxcode,
                //             'remarks' => $request->remarks,
                //             'drugindicator' => $this->givenullifempty($request->drugindicator),
                //             'frequency' => $this->givenullifempty($request->frequency),
                //             'doscode' => $this->givenullifempty($request->doscode),
                //             'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                //             'addinstruction' => $this->givenullifempty($request->addinstruction),
                //         ]);
            }
            
            // $chargetrx_obj = db::table('hisdb.chargetrx')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('id','=',$request->id)
            //                 ->first();
            
            // $product = DB::table('material.product')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('uomcode','=',$request->uom_recv)
            //                 ->where('itemcode','=',$request->chgcode);
            
            // if($product->exists()){
            //     $stockloc = DB::table('material.stockloc')
            //             ->where('compcode','=',session('compcode'))
            //             ->where('uomcode','=',$request->uom_recv)
            //             ->where('itemcode','=',$request->chgcode)
            //             ->where('deptcode','=',$request->deptcode)
            //             ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                
            //     if($stockloc->exists()){
            //         $stockloc = $stockloc->first();
            //     }else{
            //         throw new \Exception("Stockloc not exists for item: ".$request->chgcode." dept: ".$request->deptcode." uom: ".$request->uom,500);
            //     }
                
            //     $ivdspdt = DB::table('material.ivdspdt')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('recno','=',$chargetrx_obj->auditno);

            //     if($edit_lain_chggroup){
            //         if($updinv == 1){
            //             $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
            //         }
            //         $this->crtgltran($chargetrx_obj,$updinv);
            //     }else{
            //         if($ivdspdt->exists()){
            //             if($updinv == 1){
            //                 $this->updivdspdt($chargetrx_obj);
            //             }
            //             $this->updgltran($chargetrx_obj,$updinv);
            //         }else{
            //             if($updinv == 1){
            //                 $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
            //             }
            //             $this->crtgltran($chargetrx_obj,$updinv);
            //         }
            //     }
            // }
            
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
            }
            $this->delgltran($chargetrx_obj);
            

            //pindah yang lama ke billsumlog sebelum update
            //recstatus->delete

            $chargetrx_lama = DB::table("hisdb.chargetrx")
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$request->id)
                            ->first();

            // $this->sysdb_log('delete',$chargetrx_lama,'sysdb.chargetrxlog');

            DB::table("hisdb.chargetrx")
                    ->where('compcode','=',session('compcode'))
                    ->where('id','=',$request->id)
                    ->delete();

            DB::commit();

            return $this->get_ordcom_totamount($request);

            // return json_encode($responce);

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
        return $taxamount;
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
                            'bm.idno as bm_idno',
                            'bs.idno as bs_idno',
                            'bi.idno as bi_idno',
                            'bm.billtype as bm_billtype',
                            'bm.service as bm_service',
                            'bm.percent_ as bm_percent',
                            'bm.amount as bm_amount',
                            'bm.discchgcode as bm_discchgcode',
                            'bs.chggroup as bs_chggroup',
                            'bs.allitem as bs_allitem',
                            'bs.percent_ as bs_percent',
                            'bs.discchgcode as bs_discchgcode',
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
            $code = null;
            $latest_lvl = 'bm';

            foreach ($billtype as $key => $value) {

                if($latest_lvl != 'bs' && $latest_lvl != 'bi'){
                    $latest_lvl = 'bm';
                    $percent = $value->bm_percent;
                    $amount = $value->bm_amount;
                    $code = $value->bm_discchgcode;
                }

                if($chgmast->chggroup == $value->bs_chggroup){

                    if($latest_lvl != 'bi'){
                        $latest_lvl = 'bs';
                        $percent = $value->bs_percent;
                        $amount = $value->bs_amount;
                        $code = $value->bs_discchgcode;
                    }

                    if($chgmast->chgcode == $value->bi_chgcode){
                        $latest_lvl = 'bi';
                        $percent = $value->bi_percent;
                        $amount = $value->bi_amount;
                        $code = $value->bs_discchgcode;
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


            $disc_ = new stdClass();
            $disc_->latest_lvl = $latest_lvl;
            $disc_->code = $code;
            $disc_->amount = $discamount;

            return $disc_;
    }

    public function calc_discamt_2(Request $request,$pkgdet,$unitprce_lama,$new_quantity){
        $episode = DB::table('hisdb.episode')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->first();

        $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode',session('compcode'))
                        ->where('chgcode',$pkgdet->chgcode)
                        ->where('uom',$pkgdet->uom)
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
                            'bs.discchgcode as discchgcode',
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
            $code=null;

            foreach ($billtype as $key => $value) {
                if($chgmast->chggroup == $value->bs_chggroup){
                    $percent = $value->bs_percent;
                    $amount = $value->bs_amount;
                    $code = $value->discchgcode;
                    if($chgmast->chgcode == $value->bi_chgcode){
                        $percent = $value->bi_percent;
                        $amount = $value->bi_amount;
                    }
                }
            }

            $discamount = ((((100-$percent)/100)*floatval($request->unitprce_lama)*-1)*$new_quantity) - $amount;

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


            $disc_ = new stdClass();
            $disc_->code = $code;
            $disc_->amount = $discamount;

            return $disc_;
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

            //pkgdet
                // kalau pkgdet.issdept != null, than chargetrx.issdept = pkgdet.issdept
                // kalau tak amik dari request
            $oe_exists=false;
            $issdept = (empty($pkgdet->issdept))?$request->deptcode:$pkgdet->issdept;

            $chg_oe = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $pkgdet->chgcode)
                        ->where('uom', $pkgdet->uom)
                        ->where('issdept', $issdept)
                        ->where('trxtype', 'OE');

            if($chg_oe->exists()){
                $oe_exists=true;

                $quan_oe = $chg_oe->first()->quantity;
                $quan_pd = 0;

                $quantity_sum = $quan_oe + $quan_pd;
                $quantity_cur = $quantity_sum - ($pkgdet->quantity * $request->quantity);

                if($quantity_cur <= 0){
                    $quan_oe = 0;
                    $quan_pd = ($pkgdet->quantity * $request->quantity);

                    $qtyused_ = ($pkgdet->quantity * $request->quantity);
                    $qtybal_ = 0;
                }else{
                    $quan_oe = $quantity_cur;
                    $quan_pd = ($pkgdet->quantity * $request->quantity);

                    $qtyused_ = ($pkgdet->quantity * $request->quantity);
                    $qtybal_ = 0;
                }
                
            }else{

                $qtybal_ = 0;
                $qtyused_ = $pkgdet->quantity * $request->quantity;
            }

            if($oe_exists){
                if($quan_oe != 0){
                    $this->upd_chargetrx_oe_2($request,$pkgdet,$issdept,$quan_oe);
                }else if($quan_oe == 0){
                    $this->del_chargetrx_oe_2($request,$pkgdet,$issdept,$quan_oe);
                }

                $chg_pd = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $pkgdet->chgcode)
                        ->where('pkgcode', $request->chgcode)
                        ->where('uom', $pkgdet->uom)
                        ->where('issdept', $issdept)
                        ->where('trxtype', 'PD');

                if($chg_pd->exists()){

                    $this->upd_chargetrx_pd_2($request,$pkgdet,$issdept,$quan_pd);
                }else{

                    $this->add_chargetrx_pd($request,$pkgdet,$issdept,$quan_pd);
                }
            }else{

                $this->add_chargetrx_pd($request,$pkgdet,$issdept,$pkgdet->quantity);
            }

        }else{
            $qtybal_ = $pkgdet->quantity * $request->quantity;
            $qtyused_ = 0;
        }


        //utk pkgpat
        $pkgpat = DB::table("hisdb.pkgpat")
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->where('pkgcode',$request->chgcode)
                        ->where('chgcode',$pkgdet->chgcode);

        if($pkgpat->exists()){
            $pkgpat_lama = $pkgpat->first();
            $pkgqty_lama = $pkgpat_lama->pkgqty;
            $qtyused_lama = $pkgpat_lama->qtyused;
            $qtybal_lama = $pkgpat_lama->qtybal;
            
            if($pkgmast->autopull == 1){

                DB::table("hisdb.pkgpat")
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->where('episno',$request->episno)
                    ->where('pkgcode',$request->chgcode)
                    ->where('chgcode',$pkgdet->chgcode)
                    ->update([
                        'pkgqty' => $pkgqty_lama + ($pkgdet->quantity * $request->quantity),
                        'qtyused' => $qtyused_lama + ($pkgdet->quantity * $request->quantity),
                    ]);
            }else{
                
                DB::table("hisdb.pkgpat")
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->where('episno',$request->episno)
                    ->where('pkgcode',$request->chgcode)
                    ->where('chgcode',$pkgdet->chgcode)
                    ->update([
                        'pkgqty' => $pkgqty_lama + ($pkgdet->quantity * $request->quantity),
                        'qtybal' => $qtybal_lama + ($pkgdet->quantity * $request->quantity),
                    ]);
            }

        }else{

            DB::table("hisdb.pkgpat")
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'pkgcode' => $pkgmast->pkgcode,
                    'pkgqty' => $pkgdet->quantity * $request->quantity,
                    'pkgprice' => $pkgdet->pkgprice1,
                    'pkgtotprice' => $pkgdet->totprice1,
                    'chgcode' => $pkgdet->chgcode,
                    'uom' => $pkgdet->uom,
                    'doctorcode' => $request->doctorcode,
                    // 'ipacccode' => $request-> ,
                    // 'opacccode' => $request-> ,
                    'qtyused' => $qtyused_ ,
                    'qtybal' => $qtybal_,
                    // 'amtbal' => $request-> ,
                    // 'updategl' => $request-> ,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'costcd' => $request-> ,
                    // 'memberno' => $request-> ,
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser' => session('username'),
                    'issdept' => $issdept,
                    // 'agreementdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'expirydate' => $request-> ,
                    // 'AgreementID' => $request-> ,
                ]);
        }
    }

    public function order_entry_pkg_del(Request $request){
        
        DB::beginTransaction();

        try {
            $chargetrx_obj = DB::table("hisdb.chargetrx")
                        ->where('compcode','=',session('compcode'))
                        ->where('id','=',$request->id)
                        ->first();

            $pkgcode = $chargetrx_obj->chgcode;
            $mrn = $chargetrx_obj->mrn;
            $episno = $chargetrx_obj->episno;

            $pkgmast = DB::table('hisdb.pkgmast')
                            ->where('compcode','=',session('compcode'))
                            ->where('pkgcode','=',$pkgcode)
                            ->whereDate('effectdate','<=', Carbon::now("Asia/Kuala_Lumpur"))
                            ->orderBy('effectdate','desc');

            if($pkgmast->exists()){
                $pkgmast = $pkgmast->first();

                if($pkgmast->autopull == 1){

                    // DB::table("hisdb.chargetrx")
                    //         ->where('compcode','=',session('compcode'))
                    //         ->where('mrn','=',$mrn)
                    //         ->where('episno','=',$episno)
                    //         ->where('pkgcode','=',$pkgcode)
                    //         ->delete();

                    // DB::table("hisdb.chargetrx")
                    //         ->where('compcode','=',session('compcode'))
                    //         ->where('id','=',$request->id)
                    //         ->delete();

                    $chargetrx = DB::table("hisdb.chargetrx")
                                    ->where('compcode','=',session('compcode'))
                                    ->where('mrn','=',$mrn)
                                    ->where('episno','=',$episno)
                                    ->where('pkgcode','=',$pkgcode)
                                    ->get();

                    foreach ($chargetrx as $my_obj) {
                        $chargetrx_lama = DB::table("hisdb.chargetrx")
                                    ->where('compcode','=',session('compcode'))
                                    ->where('id','=',$my_obj->id)
                                    ->first();

                        $chgmast_lama = DB::table('hisdb.chgmast')
                                ->where('compcode','=',session('compcode'))
                                ->where('uom','=',$chargetrx_lama->uom_recv)
                                ->where('chgcode','=',$chargetrx_lama->chgcode)
                                ->first();

                        if($chgmast_lama->invflag != '0'){
                            $this->delivdspdt($chargetrx_lama);
                        }
                        $this->delgltran($chargetrx_lama);
                        

                        //pindah yang lama ke billsumlog sebelum update
                        //recstatus->delete

                        // $this->sysdb_log('delete',$chargetrx_lama,'sysdb.chargetrxlog');

                    }

                    DB::table("hisdb.chargetrx")
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$mrn)
                        ->where('episno','=',$episno)
                        ->where('pkgcode','=',$pkgcode)
                        ->delete();

                    // $this->sysdb_log('delete',$chargetrx_obj,'sysdb.chargetrxlog');

                    DB::table("hisdb.chargetrx")
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$request->id)
                            ->delete();

                }else{

                    DB::table("hisdb.chargetrx")
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$mrn)
                            ->where('episno','=',$episno)
                            ->where('pkgcode','=',$pkgcode)
                            ->update(['trxtype' => 'OE']);

                    // $this->sysdb_log('delete',$chargetrx_obj,'sysdb.chargetrxlog');

                    DB::table("hisdb.chargetrx")
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$request->id)
                            ->delete();

                }
            }

            DB::table("hisdb.pkgpat")
                ->where('compcode',session('compcode'))
                ->where('mrn',$mrn)
                ->where('episno',$episno)
                ->where('pkgcode',$pkgcode)
                ->delete();
            
            DB::commit();

            return $this->get_ordcom_totamount($request);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
    }

    public function check_pkgdet_exists(Request $request){
        $chg_pd = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'PD');

        if($chg_pd->exists()){
            $pkgpat = DB::table('hisdb.pkgpat')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom);

            $chg_oe = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'OE');

            $quan_pd_old = $chg_pd->first()->quantity;
            $qtybal_old = $pkgpat->first()->qtybal;

            if($chg_oe->exists()){
                $quan_oe = $chg_oe->first()->quantity;
                $quan_pd = $pkgpat->first()->qtyused;
                $pkgpat_qty = $pkgpat->first()->pkgqty;
            }else{
                $quan_oe = 0;
                $quan_pd = $pkgpat->first()->qtyused;
                $pkgpat_qty = $pkgpat->first()->pkgqty;
            }

            $quantity_sum = $quan_oe + $quan_pd;
            $quantity_cur = $quantity_sum + $request->quantity;

            if($quantity_cur<0){
                $quan_oe = $quantity_cur;//-ve value
                $quan_pd = 0;

                $qtyused = 0;
                $qtybal = $pkgpat_qty;
            }else if($quantity_cur==0){
                $quan_oe = 0;//delete oe 
                $quan_pd = 0;

                $qtyused = 0;
                $qtybal = $pkgpat_qty;
            }else{
                $quan_oe = $quantity_cur - $pkgpat_qty;

                if($quan_oe < 0){
                    $quan_oe = 0; //delete oe
                    $quan_pd = $quantity_cur;

                    $qtyused = $quantity_cur;
                    $qtybal = $pkgpat_qty - $quantity_cur;

                }else if($quan_oe == 0){
                    $quan_oe = 0; //delete oe
                    $quan_pd = $quantity_cur;

                    $qtyused = $quantity_cur;
                    $qtybal = 0;

                }else{
                    $quan_oe = $quan_oe;
                    $quan_pd = $pkgpat_qty;

                    $qtyused = $pkgpat_qty;
                    $qtybal = 0;
                }
            }

            // $chg_oe = DB::table('hisdb.chargetrx')
            //             ->where('compcode',session('compcode'))
            //             ->where('mrn', $request->mrn)
            //             ->where('episno', $request->episno)
            //             ->where('chgcode', $request->chgcode)
            //             ->where('uom', $request->uom)
            //             ->where('issdept', $request->deptcode)
            //             ->where('trxtype', 'OE');

            if(!$chg_oe->exists() && $quan_oe != 0){
                //add
                $this->add_chargetrx_oe($request,$quan_oe);
            }else if($chg_oe->exists() && $quan_oe != 0){
                //upd
                $this->upd_chargetrx_oe($request,$quan_oe);
            }else if($chg_oe->exists() && $quan_oe == 0){
                //del
                $this->del_chargetrx_oe($request,$quan_oe);
            }

            if($quan_pd_old != $quan_pd){
                $this->upd_chargetrx_pd($request,$quan_pd);
            }

            if($qtybal_old != $qtybal){
                $pkgpat = DB::table('hisdb.pkgpat')
                            ->where('compcode',session('compcode'))
                            ->where('mrn', $request->mrn)
                            ->where('episno', $request->episno)
                            ->where('chgcode', $request->chgcode)
                            ->where('uom', $request->uom)
                            ->update([
                                'qtyused' => $qtyused,
                                'qtybal' => $qtybal,
                            ]);
            }

            return true;
        }else{
            return false;
        }
    }

    public function add_chargetrx_oe(Request $request,$quan_oe){
        $new_quantity = $quan_oe;
        $new_amount = $new_quantity * $request->unitprce;
        $new_discamt = $this->calc_discamt($request,$new_quantity);
        $new_taxamount = $this->calc_taxamount($request,$new_amount,$new_discamt->amount);

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
                    'discamt' => $new_discamt->amount,
                    'disccode' => $new_discamt->code,
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
            if($updinv == 1){
                $ivdspdt_idno = $this->crtivdspdt($chargetrx_obj);
            }
            $this->crtgltran($chargetrx_obj,$updinv);
        }
    }

    public function upd_chargetrx_oe(Request $request,$quan_oe){
        $new_quantity = $quan_oe;
        $new_amount = $new_quantity * $request->unitprce;
        $new_discamt = $this->calc_discamt($request,$new_quantity);
        $new_taxamount = $this->calc_taxamount($request,$new_amount,$new_discamt->amount);

        $chargetrx_lama = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'OE')
                        ->first();
        $id_lama = $chargetrx_lama->id;

        // $this->sysdb_log('update',$chargetrx_lama,'sysdb.chargetrxlog');

        $chgmast = DB::table("hisdb.chgmast")
                ->where('compcode','=',session('compcode'))
                ->where('chgcode','=',$request->chgcode)
                ->where('uom','=',$request->uom)
                ->first();

        $updinv = ($chgmast->invflag == '1')? 1 : 0;

        DB::table('hisdb.chargetrx')
                ->where('compcode',session('compcode'))
                ->where('id', '=', $id_lama)
                ->update([
                    'trxdate' => $request->trxdate,
                    'chgcode' => $request->chgcode,
                    'chg_class' => $chgmast->chgclass,
                    'unitprce' => $request->unitprce,
                    'quantity' => $new_quantity,
                    'amount' => $new_amount,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chggroup' => $chgmast->chggroup,
                    'taxamount' => $new_taxamount,
                    'uom' => $request->uom,
                    'uom_recv' => $request->uom_recv,
                    'invgroup' => $chgmast->invgroup,
                    'reqdept' => $request->deptcode,
                    'issdept' => $request->deptcode,
                    'invcode' => $chgmast->chggroup,
                    'inventory' => $updinv,
                    'updinv' =>  $updinv,
                    'discamt' => $new_discamt->amount,
                    'disccode' => $new_discamt->code,
                    'qtyorder' => $new_quantity,
                    'qtyissue' => $new_quantity,
                    'unit' => session('unit'),
                    'chgtype' => $chgmast->chgtype,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'qtydispense' => $new_quantity,
                    'taxcode' => $request->taxcode,
                    'remarks' => $request->remarks,
                    'drugindicator' => $this->givenullifempty($request->drugindicator),
                    'frequency' => $this->givenullifempty($request->frequency),
                    'doscode' => $this->givenullifempty($request->doscode),
                    'ftxtdosage' => $this->givenullifempty($request->ftxtdosage),
                    'addinstruction' => $this->givenullifempty($request->addinstruction),
                ]);

        $chargetrx_obj = db::table('hisdb.chargetrx')
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$id_lama)
                            ->first();
            
        $product = DB::table('material.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$request->uom_recv)
                        ->where('itemcode','=',$request->chgcode);
        
        if($product->exists()){
            
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
    }

    public function upd_chargetrx_oe_2(Request $request,$pkgdet,$issdept,$quan_oe){

        $chargetrx_lama = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $pkgdet->chgcode)
                        ->where('uom', $pkgdet->uom)
                        ->where('issdept', $issdept)
                        ->where('trxtype', 'OE')
                        ->first();
        $id_lama = $chargetrx_lama->id;
        $unitprce_lama = $chargetrx_lama->unitprce;
        $uom_recv_lama = $chargetrx_lama->uom_recv;

        $new_quantity = $quan_oe;
        $new_amount = $new_quantity * $request->unitprce;
        $new_discamt = $this->calc_discamt_2($request,$pkgdet,$unitprce_lama,$new_quantity);
        $new_taxamount = $this->calc_taxamount($request,$new_amount,$new_discamt->amount);

        // $this->sysdb_log('update',$chargetrx_lama,'sysdb.chargetrxlog');

        $chgmast = DB::table("hisdb.chgmast")
                ->where('compcode','=',session('compcode'))
                ->where('chgcode','=',$pkgdet->chgcode)
                ->where('uom','=',$pkgdet->uom)
                ->first();

        $updinv = ($chgmast->invflag == '1')? 1 : 0;

        DB::table('hisdb.chargetrx')
                ->where('compcode',session('compcode'))
                ->where('id', '=', $id_lama)
                ->update([
                    'quantity' => $new_quantity,
                    'amount' => $new_amount,
                    'taxamount' => $new_taxamount,
                    'discamt' => $new_discamt->amount,
                    'disccode' => $new_discamt->code,
                    'qtyorder' => $new_quantity,
                    'qtyissue' => $new_quantity,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'qtydispense' => $new_quantity
                ]);

        $chargetrx_obj = db::table('hisdb.chargetrx')
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$id_lama)
                            ->first();
            
        $product = DB::table('material.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$uom_recv_lama)
                        ->where('itemcode','=',$pkgdet->chgcode);
        
        if($product->exists()){
            
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
    }

    public function del_chargetrx_oe(Request $request,$quan_oe){

        $chargetrx_lama = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'OE')
                        ->first();
        $id_lama = $chargetrx_lama->id;

        $chgmast_lama = DB::table('hisdb.chgmast')
                ->where('compcode','=',session('compcode'))
                ->where('uom','=',$chargetrx_lama->uom_recv)
                ->where('chgcode','=',$chargetrx_lama->chgcode)
                ->first();

        if($chgmast_lama->invflag != '0'){
            $this->delivdspdt($chargetrx_lama);
        }
        $this->delgltran($chargetrx_lama);
        

        //pindah yang lama ke billsumlog sebelum update
        //recstatus->delete
        
        // $this->sysdb_log('delete',$chargetrx_lama,'sysdb.chargetrxlog');

        DB::table("hisdb.chargetrx")
                ->where('compcode','=',session('compcode'))
                ->where('id','=',$id_lama)
                ->delete();
    }

    public function del_chargetrx_oe_2(Request $request,$pkgdet,$issdept,$quan_oe){

        $chargetrx_lama = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $pkgdet->chgcode)
                        ->where('uom', $pkgdet->uom)
                        ->where('issdept', $issdept)
                        ->where('trxtype', 'OE')
                        ->first();
        $id_lama = $chargetrx_lama->id;

        $chgmast_lama = DB::table('hisdb.chgmast')
                ->where('compcode','=',session('compcode'))
                ->where('uom','=',$chargetrx_lama->uom_recv)
                ->where('chgcode','=',$chargetrx_lama->chgcode)
                ->first();

        if($chgmast_lama->invflag != '0'){
            $this->delivdspdt($chargetrx_lama);
        }
        $this->delgltran($chargetrx_lama);
        

        //pindah yang lama ke billsumlog sebelum update
        //recstatus->delete

        // $this->sysdb_log('delete',$chargetrx_lama,'sysdb.chargetrxlog');

        DB::table("hisdb.chargetrx")
                ->where('compcode','=',session('compcode'))
                ->where('id','=',$id_lama)
                ->delete();
    }

    public function add_chargetrx_pd(Request $request,$pkgdet,$issdept ,$quan_pd){

        $new_quantity = $quan_pd;
        $new_amount = $pkgdet->totprice1;

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
                'quantity' => $new_quantity,
                'amount' => $new_amount,
                'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                'chggroup' => $chgmast->chggroup,
                // 'taxamount' => $request->taxamount,
                'uom' => $pkgdet->uom,
                'uom_recv' => $pkgdet->uom,
                'invgroup' => $invgroup,
                'reqdept' => $issdept,
                'issdept' => $issdept,//sini
                'invcode' => $chgmast->chggroup,
                'inventory' => $updinv,
                'updinv' =>  $updinv,
                // 'discamt' => $request->discamt,
                'qtyorder' => $new_quantity,
                'qtyissue' => $new_quantity,
                'unit' => session('unit'),
                'chgtype' => $chgmast->chgtype,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'qtydispense' => $new_quantity,
                // 'taxcode' => $request->taxcode,
                // 'remarks' => $request->remarks,
                'recstatus' => 'POSTED',
                'pkgcode' => $request->chgcode,
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
    }

    public function upd_chargetrx_pd(Request $request,$quan_pd){

        $chargetrx_lama = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $request->chgcode)
                        ->where('uom', $request->uom)
                        ->where('issdept', $request->deptcode)
                        ->where('trxtype', 'PD')
                        ->first();
        $id_lama = $chargetrx_lama->id;
        $unitprce_lama = $chargetrx_lama->unitprce;

        $new_quantity = $quan_pd;
        $new_amount = $new_quantity * $request->unitprce_lama;

        // $this->sysdb_log('update_pd',$chargetrx_lama,'sysdb.chargetrxlog');

        $chgmast = DB::table("hisdb.chgmast")
                ->where('compcode','=',session('compcode'))
                ->where('chgcode','=',$request->chgcode)
                ->where('uom','=',$request->uom)
                ->first();

        $updinv = ($chgmast->invflag == '1')? 1 : 0;

        DB::table('hisdb.chargetrx')
                ->where('compcode',session('compcode'))
                ->where('id', '=', $id_lama)
                ->update([
                    'quantity' => $new_quantity,
                    'amount' => $new_amount,
                    'qtyorder' => $new_quantity,
                    'qtyissue' => $new_quantity,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'qtydispense' => $new_quantity,
                ]);

        $chargetrx_obj = db::table('hisdb.chargetrx')
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$id_lama)
                            ->first();
            
        $product = DB::table('material.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$request->uom_recv)
                        ->where('itemcode','=',$request->chgcode);
        
        if($product->exists()){
            
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
    }

    public function upd_chargetrx_pd_2(Request $request,$pkgdet,$issdept,$quan_pd_added){

        $chargetrx_lama = DB::table('hisdb.chargetrx')
                        ->where('compcode',session('compcode'))
                        ->where('mrn', $request->mrn)
                        ->where('episno', $request->episno)
                        ->where('chgcode', $pkgdet->chgcode)
                        ->where('pkgcode', $request->chgcode)
                        ->where('uom', $pkgdet->uom)
                        ->where('issdept', $issdept)
                        ->where('trxtype', 'PD')
                        ->first();

        $id_lama = $chargetrx_lama->id;
        $unitprce_lama = $chargetrx_lama->unitprce;
        $qty_lama = $chargetrx_lama->quantity;

        $new_quantity = $qty_lama + $quan_pd_added;
        $new_amount = $new_quantity * $request->unitprce_lama;

        // $this->sysdb_log('update_pd',$chargetrx_lama,'sysdb.chargetrxlog');

        $chgmast = DB::table("hisdb.chgmast")
                ->where('compcode','=',session('compcode'))
                ->where('chgcode','=',$pkgdet->chgcode)
                ->where('uom','=',$pkgdet->uom)
                ->first();

        $updinv = ($chgmast->invflag == '1')? 1 : 0;

        DB::table('hisdb.chargetrx')
                ->where('compcode',session('compcode'))
                ->where('id', '=', $id_lama)
                ->update([
                    'quantity' => $new_quantity,
                    'amount' => $new_amount,
                    'qtyorder' => $new_quantity,
                    'qtyissue' => $new_quantity,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'qtydispense' => $new_quantity,
                ]);

        $chargetrx_obj = db::table('hisdb.chargetrx')
                            ->where('compcode','=',session('compcode'))
                            ->where('id','=',$id_lama)
                            ->first();
            
        $product = DB::table('material.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$pkgdet->uom)
                        ->where('itemcode','=',$pkgdet->chgcode);
        
        if($product->exists()){
            
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
                // ->where('Year','=',defaultController::toYear($chargetrx_obj->trxdate))
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

        // $this->sysdb_log('update',$ivdspdt_lama,'sysdb.ivdspdtlog');

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
                // ->where('Year','=',$my_year)
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

            $my_amount = round($ivdspdt->amount, 2);
        }else{
            $my_amount = round($chargetrx_obj->amount, 2);
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

        if(!$ivdspdt_lama->exists()){
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
                // ->where('Year','=',$my_year)
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

        // $this->sysdb_log('delete',$ivdspdt_lama,'sysdb.ivdspdtlog');

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

            $table = $table->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','uom.convfactor','cm.constype','cm.revcode','doc.doctorcode','doc.doctorname');
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

        if(!empty($request->whereInCol)){
            foreach ($request->whereInCol as $key => $value) {
                $table = $table->whereIn($value,explode(",",$request->whereInVal[$key][0]));
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
        // $deptcode = $request->deptcode;
        $priceuse = $request->price;
        // $entrydate = $request->entrydate;
        if($request->from == 'chgcode_oth'){
            $chgcode = $request->filterVal[0];
        }else{
            $chgcode = $request->filterVal[1];
        }
        // $uom = $request->uom;
        // $dfee = $request->dfee;
        // $billtype_obj = $this->billtype_obj_get($request);

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
                        // ->select('cm.chgcode','cm.chggroup','cm.invflag','cm.description','cm.brandname','cm.overwrite','cm.uom','st.idno as st_idno','st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price','pt.idno as pt_idno','pt.avgcost','uom.convfactor','cm.constype','cm.revcode')
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE')
                        ->where('cm.chgcode','=',$chgcode);
        // if(!$dfee){
        //     $table = $table->where('cm.uom','=',$uom);
        // }

        // $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld,$entrydate){
        //                     $join = $join->where('cp.compcode', '=', session('compcode'));
        //                     $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
        //                     $join = $join->on('cp.uom', '=', 'cm.uom');
        //                     if($request->from != 'chgcode_dfee'){
        //                         $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
        //                     }
        //                     $join = $join->where('cp.effdate', '<=', $entrydate);
        //                 });

        // $table = $table->leftjoin('material.stockloc as st', function($join) use ($deptcode,$entrydate){
        //                     $join = $join->on('st.itemcode', '=', 'cm.chgcode');
        //                     $join = $join->on('st.uomcode', '=', 'cm.uom');
        //                     $join = $join->where('st.compcode', '=', session('compcode'));
        //                     $join = $join->where('st.unit', '=', session('unit'));
        //                     $join = $join->where('st.deptcode', '=', $deptcode);
        //                     $join = $join->where('st.year', '=', Carbon::parse($entrydate)->format('Y'));
        //                 });

        // $table = $table->leftjoin('material.product as pt', function($join) use ($deptcode,$entrydate){
        //                     $join = $join->where('pt.compcode', '=', session('compcode'));
        //                     $join = $join->on('pt.itemcode', '=', 'cm.chgcode');
        //                     $join = $join->on('pt.uomcode', '=', 'cm.uom');
        //                     $join = $join->where('pt.unit', '=', session('unit'));
        //                 });

        // $table = $table->leftjoin('hisdb.taxmast as tm', function($join){
        //                     $join = $join->where('cp.compcode', '=', session('compcode'));
        //                     $join = $join->on('cp.optax', '=', 'tm.taxcode');
        //                 });

        // $table = $table->join('material.uom as uom', function($join){
        //                     $join = $join->on('uom.uomcode', '=', 'cm.uom')
        //                                 ->where('uom.compcode', '=', session('compcode'))
        //                                 ->where('uom.recstatus','=','ACTIVE');
        //             });

        $rows = $table->get();

        // $array_return = [];
        // foreach ($rows as $key => $value) {
        //     $billtype_amt_percent = $this->get_billtype_amt_percent($billtype_obj,$value);
        //     $value->billty_amount = $billtype_amt_percent->amount; 
        //     $value->billty_percent = $billtype_amt_percent->percent_;

        //     $chgprice_obj = DB::table('hisdb.chgprice as cp')
        //         ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
        //         ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
        //         ->where('cp.compcode', '=', session('compcode'))
        //         ->where('cp.chgcode', '=', $value->chgcode);
        //         if(!$dfee){
        //             $chgprice_obj = $chgprice_obj->where('cp.uom', '=', $value->uom);
        //         }
        //     $chgprice_obj = $chgprice_obj->whereDate('cp.effdate', '<=', $entrydate)
        //         ->orderBy('cp.effdate','desc');

        //     if($chgprice_obj->exists()){
        //         $chgprice_obj = $chgprice_obj->first();

        //         if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
        //             unset($rows[$key]);
        //             continue;
        //         }
        //     }
        //     array_push($array_return,$value);
        // }

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
                        ->where('trx.taxflag',0)
                        ->where('trx.discflag',0)
                        ->get();

        // dd($this->getQueries($chargetrx));

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

        if(empty($request->billtype)){
            $episode = DB::table('hisdb.episode')
                            ->where('compcode',session('compcode'))
                            ->where('mrn',$request->mrn)
                            ->where('episno',$request->episno)
                            ->first();

            $billtymst = DB::table('hisdb.billtymst')
                        ->where('compcode','=',session('compcode'))
                        ->where('billtype','=',$episode->billtype);
        }else{

            $billtymst = DB::table('hisdb.billtymst')
                        ->where('compcode','=',session('compcode'))
                        ->where('billtype','=',$request->billtype);
        }

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

    public function final_bill_init(Request $request){

        $mrn = $request->mrn;
        $episno = $request->episno;

        $chargetrx_obj = DB::table('hisdb.chargetrx')
                            ->where('compcode',session('compcode'))
                            ->where('mrn' ,'=', $mrn)
                            ->where('episno' ,'=', $episno)
                            ->where('trxtype','!=','PD')
                            ->where('recstatus','<>','DELETE')
                            ->whereNotNull('billno');

        if($chargetrx_obj->exists()){
            $this->final_bill_reverse($request);
            
            $this->final_bill($request);
        }else{
            $this->final_bill($request);
        }

    }

    public function final_bill(Request $request){
        DB::beginTransaction();

        try {

            $mrn = $request->mrn;
            $episno = $request->episno;
            $personal_payercode = str_pad($mrn, 7, '0', STR_PAD_LEFT);

            $episode = DB::table('hisdb.episode')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',$mrn)
                                ->where('episno',$episno)
                                ->first();

            $epispayer = DB::table('hisdb.epispayer')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',$mrn)
                                ->where('episno',$episno)
                                ->where('payercode',$personal_payercode);

            if(!$epispayer->exists()){
                $epispayer_count = DB::table('hisdb.epispayer')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',$mrn)
                                ->where('episno',$episno)
                                ->count();

                DB::table('hisdb.epispayer')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $mrn,
                            'episno' => $episno,
                            'payercode' => $personal_payercode,
                            'lineno' => $epispayer_count+1,
                            'epistycode' => $episode->epistycode,
                            'pay_type' => 'PT',
                            'pyrmode' => null,
                            'pyrcharge' => null,
                            'pyrcrdtlmt' => null,
                            'pyrlmtamt' => 9999999.99,
                            'totbal' => 9999999.99,
                            'allgroup' => 1,
                            'alldept' => 1,
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'adduser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'billtype' => $episode->billtype,
                            'refno' => null,
                            'chgrate' => null,
                            'computerid' => session('computerid'),
                        ]);
            }

            $chargetrx_obj = DB::table('hisdb.chargetrx as ctx')
                                ->select('ctx.id','ctx.auditno','ctx.compcode','ctx.idno','ctx.mrn','ctx.episno','ctx.epistype','ctx.trxtype','ctx.docref','ctx.trxdate','ctx.chgcode','ctx.billcode','ctx.costcd','ctx.revcd','ctx.mmacode','ctx.billflag','ctx.billdate','ctx.billtype','ctx.doctorcode','ctx.chg_class','ctx.unitprce','ctx.quantity','ctx.amount','ctx.trxtime','ctx.chggroup','ctx.qstat','ctx.dracccode','ctx.cracccode','ctx.arprocess','ctx.taxamount','ctx.billno','ctx.invno','ctx.uom','ctx.uom_recv','ctx.billtime','ctx.invgroup','ctx.reqdept','ctx.issdept','ctx.invcode','ctx.resulttype','ctx.resultstatus','ctx.inventory','ctx.updinv','ctx.invbatch','ctx.doscode','ctx.duration','ctx.instruction','ctx.discamt','ctx.disccode','ctx.pkgcode','ctx.remarks','ctx.frequency','ctx.ftxtdosage','ctx.addinstruction','ctx.qtyorder','ctx.ipqueueno','ctx.itemseqno','ctx.doseqty','ctx.freqqty','ctx.isudept','ctx.qtyissue','ctx.durationcode','ctx.reqdoctor','ctx.unit','ctx.agreementid','ctx.chgtype','ctx.adduser','ctx.adddate','ctx.lastuser','ctx.lastupdate','ctx.daytaken','ctx.qtydispense','ctx.takehomeentry','ctx.latechargesentry','ctx.taxcode','ctx.recstatus','ctx.drugindicator','ctx.patmedication','ctx.mmaprice','gldp.idno as gldp_idno','glit.idno as glit_idno','gldp.grpcode','gldp.grplimit','gldp.grpbal','gldp.inditemlimit','glit.totitemlimit','glit.totitembal')
                                ->leftjoin('hisdb.gletdept as gldp', function($join) use ($mrn,$episno){
                                    $join = $join->where('gldp.compcode', '=', session('compcode'));
                                    $join = $join->where('gldp.mrn', '=', $mrn);
                                    $join = $join->where('gldp.episno', '=', $episno);
                                    $join = $join->on('gldp.grpcode', '=', 'ctx.chggroup');
                                })
                                ->leftjoin('hisdb.gletitem as glit', function($join) use ($mrn,$episno){
                                    $join = $join->where('glit.compcode', '=', session('compcode'));
                                    $join = $join->where('glit.mrn', '=', $mrn);
                                    $join = $join->where('glit.episno', '=', $episno);
                                    $join = $join->on('glit.grpcode', '=', 'ctx.chggroup');
                                    $join = $join->on('glit.chgcode', '=', 'ctx.chgcode');
                                })
                                ->where('ctx.compcode',session('compcode'))
                                ->where('ctx.mrn' ,'=', $mrn)
                                ->where('ctx.episno' ,'=', $episno)
                                ->where('ctx.trxtype','!=','PD')
                                ->where('ctx.recstatus','<>','DELETE')
                                ->orderBy('glit.totitemlimit','desc')
                                ->orderBy('gldp.grplimit','desc')
                                ->orderBy('ctx.amount','asc');

            if(!$chargetrx_obj->exists()){
                throw new \Exception("This Patient Doesnt Have any Charges!");
            }
            
            $chargetrx_obj = $chargetrx_obj->get();

            $billno = $this->recno('PB','IN');
            $invno = $this->recno('PB','INV');
            $gst = [];
            $disc = [];

            foreach ($chargetrx_obj as $key => $chargetrx) {
                $net_amout = $chargetrx->amount + $chargetrx->taxamount + $chargetrx->discamt;
                if($chargetrx->taxamount != 0){
                    $gst = $this->handle_gst($gst,$chargetrx->taxamount);
                }

                if($chargetrx->discamt != 0){
                    $disc = $this->handle_disc($disc,$chargetrx->discamt,$chargetrx->disccode);
                }

                $this->handle_epispayer($net_amout,$chargetrx,$mrn,$episno,$episode->billtype,$billno,$invno);
            }

            $this->make_gst_discount($gst,$disc,$mrn,$episno,$episode->billtype,$billno,$invno);
            $this->make_billsum_and_round($mrn,$episno);
            $this->make_dbacthdr_and_GL($mrn,$episno);
            // $this->make_discharge($mrn,$episno);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e, 500);
        }
    }

    public function handle_epispayer($net_amout,$chargetrx,$mrn,$episno,$billtype,$billno,$invno){
        $chgcode = $chargetrx->chgcode;
        $chggroup = $chargetrx->chggroup;
        $epispayer_obj = DB::table('hisdb.epispayer as epayr')
                                ->select('epayr.idno as epayr_idno','gldp.idno as gldp_idno','glit.idno as glit_idno','epayr.lineno','epayr.pyrlmtamt','epayr.totbal','epayr.allgroup','gldp.grpcode','gldp.grplimit','gldp.grpbal','gldp.inditemlimit','glit.chgcode','glit.totitemlimit','glit.totitembal')
                                ->leftjoin('hisdb.gletdept as gldp', function($join) use ($mrn,$episno,$chggroup){
                                    $join = $join->where('gldp.compcode', '=', session('compcode'));
                                    $join = $join->where('gldp.mrn', '=', $mrn);
                                    $join = $join->where('gldp.episno', '=', $episno);
                                    $join = $join->where('gldp.grpcode', '=', $chggroup);
                                    $join = $join->on('gldp.payercode', '=', 'epayr.payercode');
                                })
                                ->leftjoin('hisdb.gletitem as glit', function($join) use ($mrn,$episno,$chggroup,$chgcode){
                                    $join = $join->where('glit.compcode', '=', session('compcode'));
                                    $join = $join->where('glit.mrn', '=', $mrn);
                                    $join = $join->where('glit.episno', '=', $episno);
                                    $join = $join->where('glit.grpcode', '=', $chggroup);
                                    $join = $join->where('glit.chgcode', '=', $chgcode);
                                    $join = $join->on('glit.payercode', '=', 'epayr.payercode');
                                })
                                ->where('epayr.compcode',session('compcode'))
                                ->where('epayr.mrn' ,'=', $mrn)
                                ->where('epayr.episno' ,'=', $episno)
                                ->orderBy('epayr.lineno','asc')
                                ->get();

        // dd($epispayer_obj);

        $net_amout = $net_amout;
        foreach ($epispayer_obj as $key => $epispayer) {
            $lineno = $epispayer->lineno;
            $epayr_idno = $epispayer->epayr_idno;
            $gldp_idno = $epispayer->gldp_idno;
            $glit_idno = $epispayer->glit_idno;
            $totbal = $epispayer->totbal;
            $allgroup = $epispayer->allgroup;
            $grpcode = $epispayer->grpcode;
            $grpbal = $epispayer->grpbal;
            $inditemlimit = $epispayer->inditemlimit;
            $totitembal = $epispayer->totitembal;

            $totbal_after = $totbal - $net_amout;
            if($totbal_after<0){
                $boleh_ditolak = $totbal;
                $baki_turun = $net_amout - $boleh_ditolak;
            }else{
                $boleh_ditolak = $net_amout;
                $baki_turun = 0;
            }

            if(!empty($gldp_idno)){

                if(!empty($inditemlimit) && ($boleh_ditolak > $inditemlimit)){
                    $inditemlimit_net = $inditemlimit * $chargetrx->quantity;

                    $inditemlimit_after = $inditemlimit_net - $boleh_ditolak;
                    if($inditemlimit_after<0){
                        $boleh_ditolak = $inditemlimit_net;
                        $baki_turun = $net_amout - $boleh_ditolak;
                    }else{
                        $boleh_ditolak = $boleh_ditolak;
                        $baki_turun = $baki_turun;
                    }
                }

                $grpbal_after = $grpbal - $boleh_ditolak;
                if($grpbal_after<0){
                    $boleh_ditolak = $grpbal;
                    $baki_turun = $net_amout - $boleh_ditolak;
                }else{
                    $boleh_ditolak = $boleh_ditolak;
                    $baki_turun = $baki_turun;
                }

                if(!empty($glit_idno)){
                    $totitembal_after = $totitembal - $boleh_ditolak;
                    if($totitembal_after<0){
                        $boleh_ditolak = $totitembal;
                        $baki_turun = $net_amout - $boleh_ditolak;
                    }else{
                        $boleh_ditolak = $boleh_ditolak;
                        $baki_turun = $baki_turun;
                    }

                    if($boleh_ditolak > 0){
                        DB::table('hisdb.gletitem')
                            ->where('compcode',session('compcode'))
                            ->where('idno' ,'=', $glit_idno)
                            ->update(['totitembal' => $totitembal - $boleh_ditolak]);

                        $totitembal = $totitembal - $boleh_ditolak;
                    }
                }

                if($boleh_ditolak > 0){
                    DB::table('hisdb.gletdept')
                        ->where('compcode',session('compcode'))
                        ->where('idno' ,'=', $gldp_idno)
                        ->update(['grpbal' => $grpbal - $boleh_ditolak]);

                    $grpbal = $grpbal - $boleh_ditolak;
                }
                
            }

            if($boleh_ditolak > 0){
                DB::table('hisdb.epispayer')
                        ->where('compcode',session('compcode'))
                        ->where('idno' ,'=', $epayr_idno)
                        ->update(['totbal' => $totbal - $boleh_ditolak]);

                $totbal = $totbal - $boleh_ditolak;

                DB::table("hisdb.billdet")
                        ->insert([
                            'auditno' => $chargetrx->auditno,
                            'lineno_' => $lineno,
                            // 'idno' => $chargetrx->idno,
                            'compcode' => session('compcode'),
                            'mrn'  => $chargetrx->mrn,
                            'episno'  => $chargetrx->episno,
                            'trxdate' => $chargetrx->trxdate,
                            'chgcode' => $chargetrx->chgcode,
                            'doctorcode' => $chargetrx->doctorcode,
                            'docref' => $chargetrx->docref,
                            'billflag' => 1,
                            'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'billtype'  => $billtype,
                            'chg_class' => $chargetrx->chg_class,
                            'unitprce' => $chargetrx->unitprce,
                            'quantity' => $chargetrx->quantity,
                            'amount' => $boleh_ditolak,
                            'trxtime' => $chargetrx->trxtime,
                            'chggroup' => $chargetrx->chggroup,
                            'taxamount' => $chargetrx->taxamount,
                            'billno' => $billno,
                            'invno' => $invno,
                            'uom' => $chargetrx->uom,
                            'billtime' => $chargetrx->billtime,
                            'invgroup' => $chargetrx->invgroup,
                            'reqdept' => $chargetrx->reqdept,
                            'issdept' => $chargetrx->issdept,
                            'invcode' => $chargetrx->invcode,
                            'discamt' => $chargetrx->discamt,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'taxcode' => $chargetrx->taxcode,
                            'recstatus' => 'POSTED',
                        ]);

                DB::table("hisdb.chargetrx")
                        ->where('compcode',session('compcode'))
                        ->where('id' ,'=', $chargetrx->id)
                        ->update([
                            'billflag' => 1,
                            'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'billtype'  => $billtype,
                            'billno' => $billno,
                            'invno' => $invno,
                            'billtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        ]);

            }

            if($baki_turun == 0){
                break;
            }else{
                $net_amout = $baki_turun;
            }
        }
    }

    public function final_bill_reverse(Request $request){
        DB::beginTransaction();
        try {

            $mrn = $request->mrn;
            $episno = $request->episno;

            DB::table('hisdb.chargetrx')
                    ->where('compcode',session('compcode'))
                    ->where('mrn' ,'=', $mrn)
                    ->where('episno' ,'=', $episno)
                    ->where('trxtype','!=','PD')
                    ->where('recstatus','<>','DELETE')
                    ->update([
                            'billflag' => 0,
                            'billdate' => null,
                            'billno' => null,
                            'invno' => null,
                            'billtime' => null
                    ]);

            DB::table('hisdb.chargetrx')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$mrn)
                    ->where('episno',$episno)
                    ->where('taxflag',1)
                    ->orWhere('discflag',1)
                    ->orWhere('chgcode','round')
                    ->delete();

            DB::table('hisdb.billdet')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$mrn)
                    ->where('episno',$episno)
                    ->delete();

            DB::table('debtor.billsum')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$mrn)
                    ->where('episno',$episno)
                    ->delete();

            $epispayer = DB::table('hisdb.epispayer')
                            ->where('compcode',session('compcode'))
                            ->where('mrn' ,'=', $mrn)
                            ->where('episno' ,'=', $episno);

            if($epispayer->exists()){
                foreach($epispayer->get() as $key => $obj) {
                    DB::table('hisdb.epispayer')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$obj->idno)
                            ->update(['totbal' => $obj->pyrlmtamt]);
                }
            }

            $gletdept = DB::table('hisdb.gletdept')
                            ->where('compcode',session('compcode'))
                            ->where('mrn' ,'=', $mrn)
                            ->where('episno' ,'=', $episno);

            if($gletdept->exists()){
                foreach($gletdept->get() as $key => $obj) {
                    DB::table('hisdb.gletdept')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$obj->idno)
                            ->update(['grpbal' => $obj->grplimit]);
                }
            }

            $gletitem = DB::table('hisdb.gletitem')
                            ->where('compcode',session('compcode'))
                            ->where('mrn' ,'=', $mrn)
                            ->where('episno' ,'=', $episno);

            if($gletitem->exists()){
                foreach($gletitem->get() as $key => $obj) {
                    DB::table('hisdb.gletitem')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$obj->idno)
                            ->update(['totitembal' => $obj->totitemlimit]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e, 500);
        }
    }

    public function handle_gst($gst,$taxamount){
        $gstcode = 'GST';
        $gotgst = false;

        foreach ($gst as $key => $value) {
            if($value->code == 'GST'){
                $value->amount = $value->amount + $taxamount;
                $gotgst = true;
            }
        }

        if(!$gotgst){
            $gst_ = new stdClass();
            $gst_->code = 'GST';
            $gst_->amount = floatval($taxamount);

            array_push($gst,$gst_);
        }

        return $gst;
    }

    public function handle_disc($disc,$discamt,$disccode){
        $gotdisc = false;

        foreach ($disc as $key => $value) {
            if($value->code == $disccode){
                $value->amount = $value->amount + $discamt;
                $gotdisc = true;
            }
        }

        if(!$gotdisc){
            $disc_ = new stdClass();
            $disc_->code = $disccode;
            $disc_->amount = floatval($discamt);

            array_push($disc,$disc_);
        }

        return $disc;
    }

    public function handle_round($round,$lineno,$net_amount,$billdet){
        $gotlineno = false;

        foreach ($round as $key => $value) {
            if($value->lineno == $lineno){
                $value->amount = $value->amount + $net_amount;
                $gotlineno = true;
            }
        }

        if(!$gotlineno){
            $round_ = new stdClass();
            $round_->lineno = $lineno;
            $round_->amount = floatval($net_amount);
            $round_->billdet = $billdet;

            array_push($round,$round_);
        }

        return $round;
    }

    public function make_gst_discount($gst,$disc,$mrn,$episno,$billtype,$billno,$invno){
        foreach ($gst as $key => $value) {
            $recno = $this->recno('OE','IN');

            $chgmast = DB::table("hisdb.chgmast")
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',$value->code)
                    ->first();

            $invgroup = $this->get_invgroup($chgmast,null);

            DB::table("hisdb.chargetrx")
                ->insertGetId([
                    'auditno' => $recno,
                    'compcode'  => session('compcode'),
                    'mrn'  => $mrn,
                    'episno'  => $episno,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'trxtype' => 'OE',
                    'chgcode' => $value->code,
                    'billflag' => 1,
                    'quantity' => 1,
                    'billtype' => $billtype,
                    'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billflag' => 1,
                    'billno' => $billno,
                    'invno' => $invno,
                    'chg_class' => $chgmast->chgclass,
                    'amount' => $value->amount,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chggroup' => $chgmast->chggroup,
                    'uom' => $chgmast->uom,
                    'uom_recv' => $chgmast->uom,
                    'invgroup' => $invgroup,
                    'unit' => session('unit'),
                    'chgtype' => $chgmast->chgtype,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'POSTED',
                    'taxflag' => 1,
                ]);

            DB::table("hisdb.billdet")
                ->insert([
                    'auditno' => $recno,
                    'lineno_' => 1,
                    // 'idno' => $chargetrx->idno,
                    'compcode' => session('compcode'),
                    'mrn'  => $mrn,
                    'episno'  => $episno,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chgcode' => $value->code,
                    'billflag' => 1,
                    'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billtype'  => $billtype,
                    'chg_class' => $chgmast->chgclass,
                    'quantity' => 1,
                    'amount' => $value->amount,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chggroup' => $chgmast->chggroup,
                    'billno' => $billno,
                    'invno' => $invno,
                    'uom' => $chgmast->uom,
                    'billtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'invgroup' => $invgroup,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'POSTED',
                    'taxflag' => 1,
                ]);
        }

        foreach ($disc as $key => $value) {
            $recno = $this->recno('OE','IN');

            DB::table("hisdb.chargetrx")
                ->insertGetId([
                    'auditno' => $recno,
                    'compcode'  => session('compcode'),
                    'mrn'  => $mrn,
                    'episno'  => $episno,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'trxtype' => 'OE',
                    'chgcode' => $value->code,
                    'billflag' => 1,
                    'quantity' => 1,
                    'billtype' => $billtype,
                    'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billflag' => 1,
                    'billno' => $billno,
                    'invno' => $invno,
                    // 'chg_class' => $chgmast->chgclass,
                    'amount' => $value->amount,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'chggroup' => $chgmast->chggroup,
                    // 'uom' => $chgmast->uom,
                    // 'uom_recv' => $request->uom_recv,
                    'invgroup' => $invgroup,
                    'unit' => session('unit'),
                    // 'chgtype' => $chgmast->chgtype,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'POSTED',
                    'discflag' => 1,
                ]);

            DB::table("hisdb.billdet")
                ->insert([
                    'auditno' => $recno,
                    'lineno_' => 1,
                    // 'idno' => $chargetrx->idno,
                    'compcode' => session('compcode'),
                    'mrn'  => $mrn,
                    'episno'  => $episno,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chgcode' => $value->code,
                    'billflag' => 1,
                    'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billtype'  => $billtype,
                    'chg_class' => $chgmast->chgclass,
                    'quantity' => 1,
                    'amount' => $value->amount,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chggroup' => $chgmast->chggroup,
                    'billno' => $billno,
                    'invno' => $invno,
                    'uom' => $chgmast->uom,
                    'billtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'invgroup' => $invgroup,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'POSTED',
                    'discflag' => 1,
                ]);
        }
    }

    public function make_billsum_and_round($mrn,$episno){
        $billdet_obj = DB::table('hisdb.billdet as bd')
                        ->select('bd.chgcode','bd.uom','bd.mrn','bd.episno','chgm.description','bd.lineno_','bd.trxdate','bd.unitprce','bd.taxcode','bd.invno','bd.docref','bd.invcode','bd.billno','bd.billtype','bd.quantity','bd.amount','bd.discamt','bd.taxamount','chgm.invgroup','chgm.chgclass','chgm.chggroup','dbmst.debtorcode','dbmst.debtortype','chgc.description as chgc_desc','chgc.classlevel','chgg.description as chgg_desc','chgt.description as chgt_desc','doc.doctorname','doc.doctorcode')
                        ->where('bd.compcode',session('compcode'))
                        ->where('bd.mrn',$mrn)
                        ->where('bd.episno',$episno)
                        ->where('bd.taxflag',0)
                        ->where('bd.discflag',0)
                        ->orderBy('bd.lineno_','desc')
                        ->join('hisdb.chgmast as chgm', function($join){
                            $join = $join->where('chgm.compcode', '=', session('compcode'));
                            $join = $join->on('chgm.chgcode', '=', 'bd.chgcode');
                            $join = $join->on('chgm.uom', '=', 'bd.uom');
                        })
                        ->join('hisdb.epispayer as epayr', function($join){
                            $join = $join->where('epayr.compcode', '=', session('compcode'));
                            $join = $join->on('epayr.mrn', '=', 'bd.mrn');
                            $join = $join->on('epayr.episno', '=', 'bd.episno');
                            $join = $join->on('epayr.lineno', '=', 'bd.lineno_');
                        })
                        ->join('debtor.debtormast as dbmst', function($join){
                            $join = $join->where('dbmst.compcode', '=', session('compcode'));
                            $join = $join->on('dbmst.debtorcode', '=', 'epayr.payercode');
                        })
                        ->leftjoin('hisdb.chggroup as chgg', function($join){
                            $join = $join->where('chgg.compcode', '=', session('compcode'));
                            $join = $join->on('chgg.grpcode', '=', 'chgm.chggroup');
                        })
                        ->leftjoin('hisdb.chgtype as chgt', function($join){
                            $join = $join->where('chgt.compcode', '=', session('compcode'));
                            $join = $join->on('chgt.chgtype', '=', 'chgm.chgtype');
                        })
                        ->leftjoin('hisdb.doctor as doc', function($join){
                            $join = $join->where('doc.compcode', '=', session('compcode'));
                            $join = $join->on('doc.doctorcode', '=', 'bd.doctorcode');
                        })
                        ->leftjoin('hisdb.chgclass as chgc', function($join){
                            $join = $join->where('chgc.compcode', '=', session('compcode'));
                            $join = $join->on('chgc.classcode', '=', 'chgm.chgclass');
                        })
                        ->get();

        $sum_amt=[];
        $sum_disc=[];
        $sum_tax=[];
        $lineno_array=[];
        $round=[];

        foreach ($billdet_obj as $key => $billdet) {

            $round = $this->handle_round($round,$billdet->lineno_,$billdet->amount,$billdet);

            if(!in_array($billdet->lineno_, $lineno_array)){
                array_push($lineno_array,$billdet->lineno_);
            }

            if(strtoupper($billdet->invgroup) == 'CC'){
                $billdet->pdescription = $billdet->description;
            }else if(strtoupper($billdet->invgroup) == 'CT'){
                $billdet->pdescription = $billdet->chgt_desc;
            }else{
                $billdet->pdescription = $billdet->chgg_desc;
            }

            if(empty($sum_amt[intval($billdet->lineno_)])){
                $sum_amt[$billdet->pdescription.'_'.$billdet->lineno_] = $billdet->amount;
            }else{
                $sum_amt[$billdet->pdescription.'_'.$billdet->lineno_] = $sum_amt[$billdet->pdescription.'_'.$billdet->lineno_] + $billdet->amount;
            }

            if(empty($sum_disc[intval($billdet->lineno_)])){
                $sum_disc[$billdet->pdescription.'_'.$billdet->lineno_] = $billdet->discamt;
            }else{
                $sum_disc[$billdet->pdescription.'_'.$billdet->lineno_] = $sum_disc[$billdet->pdescription.'_'.$billdet->lineno_] + $billdet->discamt;
            }

            if(empty($sum_tax[intval($billdet->lineno_)])){
                $sum_tax[$billdet->pdescription.'_'.$billdet->lineno_] = $billdet->taxamount;
            }else{
                $sum_tax[$billdet->pdescription.'_'.$billdet->lineno_] = $sum_tax[$billdet->pdescription.'_'.$billdet->lineno_] + $billdet->taxamount;
            }
        }

        $rowno=1;
        foreach ($lineno_array as $lineno_) {
            $invgroup = $billdet_obj->unique('pdescription')->where('lineno_', $lineno_);;

            foreach ($invgroup as $billdet_) {
                $insertGetId = DB::table('debtor.billsum')
                    ->insertGetId([
                        'compcode' => session('compcode') ,
                        'source' => 'PB' ,
                        'trantype' => 'IN' ,
                        // 'auditno' => $billdet_-> ,
                        'description' => $billdet_->pdescription ,
                        'quantity' => $billdet_->quantity ,
                        'amount' => $sum_amt[$billdet_->pdescription.'_'.$billdet_->lineno_] ,
                        'outamt' => $sum_amt[$billdet_->pdescription.'_'.$billdet_->lineno_] ,
                        'taxamt' => $sum_tax[$billdet_->pdescription.'_'.$billdet_->lineno_] ,
                        // 'totamt' => $billdet_-> ,
                        'mrn' => $mrn ,
                        'episno' => $episno ,
                        // 'paymode' => $billdet_-> ,
                        // 'cardno' => $billdet_-> ,
                        'debtortype' => $billdet_->debtortype ,
                        'debtorcode' => $billdet_->debtorcode ,
                        'invno' => $billdet_->invno ,
                        'billno' => $billdet_->billno ,
                        'lineno_' => $billdet_->lineno_ ,
                        'rowno' => $rowno ,
                        'billtype' => $billdet_->billtype ,
                        'chgclass' => $billdet_->chgclass ,
                        'classlevel' => $billdet_->classlevel ,
                        'chggroup' => $billdet_->chgcode ,
                        'lastuser' => session('username') ,
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur") ,
                        'invcode' => $billdet_->invcode ,
                        // 'seqno' => $billdet_-> ,
                        'discamt' => $sum_disc[$billdet_->pdescription.'_'.$billdet_->lineno_] ,
                        'docref' => $billdet_->docref ,
                        'uom' => $billdet_->uom ,
                        'uom_recv' => $billdet_->uom ,
                        'recstatus' => 'ACTIVE' ,
                        'unitprice' => $billdet_->unitprce ,
                        'taxcode' => $billdet_->taxcode ,
                        // 'billtypeperct' => $billdet_-> ,
                        // 'billtypeamt' => $billdet_-> ,
                        // 'totamount' => $billdet_-> ,
                        // 'qtyonhand' => $billdet_-> ,
                    ]);

            Db::table('debtor.billsum')
                    ->where('compcode',session('compcode'))
                    ->where('idno', '=', $insertGetId)
                    ->update(['auditno' => $insertGetId]);
            
                $rowno = $rowno + 1;
            }
        }

        foreach ($round as $round_obj) {
            $billdet_ = $round_obj->billdet;
            $amount = $round_obj->amount;
            $rounded = $amount - round($amount,1);

            $recno = $this->recno('OE','IN');

            DB::table("hisdb.chargetrx")
                ->insertGetId([
                    'auditno' => $recno,
                    'compcode'  => session('compcode'),
                    'mrn'  => $billdet_->mrn,
                    'episno'  => $billdet_->episno,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'trxtype' => 'OE',
                    'chgcode' => 'ROUND',
                    'billflag' => 1,
                    'quantity' => 1,
                    'billtype' => $billdet_->billtype,
                    'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billno' => $billdet_->billno,
                    'invno' => $billdet_->invno,
                    // 'chg_class' => $chgmast->chgclass,
                    'amount' => $rounded,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'chggroup' => $chgmast->chggroup,
                    // 'uom' => $chgmast->uom,
                    // 'uom_recv' => $chgmast->uom,
                    // 'invgroup' => $invgroup,
                    'unit' => session('unit'),
                    // 'chgtype' => $chgmast->chgtype,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'POSTED',
                    'taxflag' => 1,
                ]);

            DB::table("hisdb.billdet")
                ->insert([
                    'auditno' => $recno,
                    'lineno_' => 1,
                    // 'idno' => $chargetrx->idno,
                    'compcode' => session('compcode'),
                    'mrn'  => $billdet_->mrn,
                    'episno'  => $billdet_->episno,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chgcode' => 'ROUND',
                    'billflag' => 1,
                    'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'billtype'  => $billdet_->billtype,
                    // 'chg_class' => $chgmast->chgclass,
                    'quantity' => 1,
                    'amount' => $rounded,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'chggroup' => $chgmast->chggroup,
                    'billno' => $billdet_->billno,
                    'invno' => $billdet_->invno,
                    // 'uom' => $chgmast->uom,
                    'billtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'invgroup' => $invgroup,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'POSTED',
                    'taxflag' => 1,
                ]);
        }
    }

    public function make_dbacthdr_and_GL($mrn,$episno){
        $billdet_obj = DB::table('hisdb.billdet as bd')
                        ->select('bd.auditno','bd.idno','bd.chgcode','bd.uom','bd.mrn','bd.episno','chgm.description','bd.lineno_','bd.trxdate','bd.unitprce','bd.taxcode','bd.invno','bd.invcode','bd.billno','bd.billtype','bd.quantity','bd.amount','bd.discamt','bd.taxamount','chgm.invgroup','chgm.chgclass','dbmst.debtorcode','dbmst.debtortype','dbmst.actdebccode','dbmst.actdebglacc','chgt.ipacccode','chgt.opacccode','ep.epistycode','dept.costcode as dept_costcode')
                        ->where('bd.compcode',session('compcode'))
                        ->where('bd.mrn',$mrn)
                        ->where('bd.episno',$episno)
                        // ->where('bd.taxflag',0)
                        // ->where('bd.discflag',0)
                        ->orderBy('bd.lineno_','desc')
                        ->join('hisdb.chgmast as chgm', function($join){
                            $join = $join->where('chgm.compcode', '=', session('compcode'));
                            $join = $join->on('chgm.chgcode', '=', 'bd.chgcode');
                            $join = $join->on('chgm.uom', '=', 'bd.uom');
                        })
                        ->join('hisdb.episode as ep', function($join){
                            $join = $join->where('ep.compcode', '=', session('compcode'));
                            $join = $join->on('ep.mrn', '=', 'bd.mrn');
                            $join = $join->on('ep.episno', '=', 'bd.episno');
                        })
                        ->join('hisdb.epispayer as epayr', function($join){
                            $join = $join->where('epayr.compcode', '=', session('compcode'));
                            $join = $join->on('epayr.mrn', '=', 'bd.mrn');
                            $join = $join->on('epayr.episno', '=', 'bd.episno');
                            $join = $join->on('epayr.lineno', '=', 'bd.lineno_');
                        })
                        ->join('debtor.debtormast as dbmst', function($join){
                            $join = $join->where('dbmst.compcode', '=', session('compcode'));
                            $join = $join->on('dbmst.debtorcode', '=', 'epayr.payercode');
                        })
                        ->leftjoin('sysdb.department as dept', function($join){
                            $join = $join->where('dept.compcode', '=', session('compcode'));
                            $join = $join->on('dept.deptcode', '=', 'bd.issdept');
                        })
                        ->leftjoin('hisdb.chgtype as chgt', function($join){
                            $join = $join->where('chgt.compcode', '=', session('compcode'));
                            $join = $join->on('chgt.chgtype', '=', 'chgm.chgtype');
                        })
                        ->get();

        $billdet_unq = $billdet_obj->unique('lineno_');
        $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
        $sysparam = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->where('source','AR')
                    ->where('trantype','AD')
                    ->first();

        foreach ($billdet_unq as $key_unq => $value_unq) {
            $billdet_feu = $billdet_obj->where('lineno_',$value_unq->lineno_);
            $sum_amt = $billdet_feu->sum('amount');

            DB::table('debtor.dbacthdr')
                ->insert([
                    'compcode' => session('compcode'),
                    'source' => 'PB',
                    'trantype' => 'IN',
                    'auditno' => $value_unq->billno,
                    'lineno_' => $value_unq->lineno_,
                    'amount' => $sum_amt,
                    'outamount' => $sum_amt,
                    'recstatus' => 'POSTED',
                    'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'entryuser' => session('username'),
                    'reference' => 'Bill No @ '.$value_unq->billno,
                    // 'recptno' => ,
                    // 'paymode' => ,
                    // 'tillcode' => ,
                    // 'tillno' => ,
                    'debtortype' => $value_unq->debtortype,
                    'debtorcode' => $value_unq->debtorcode,
                    'payercode' => $value_unq->debtorcode,
                    'billdebtor' => $value_unq->debtorcode,
                    'remark' => 'Final Bill',
                    'mrn' => $value_unq->mrn,
                    'episno' => $value_unq->episno,
                    // 'authno' => ,
                    // 'expdate' => ,
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser' => session('username'),
                    // 'upddate' => ,
                    // 'upduser' => ,
                    // 'deldate' => ,
                    // 'deluser' => ,
                    'epistype' => $value_unq->epistycode,
                    // 'cbflag' => ,
                    // 'conversion' => ,
                    // 'payername' => ,
                    // 'hdrtype' => ,
                    // 'currency' => ,
                    // 'rate' => ,
                    'unit' => session('unit'),
                    'invno' => $value_unq->invno,
                    // 'paytype' => ,
                    // 'bankcharges' => ,
                    // 'RCCASHbalance' => ,
                    // 'RCOSbalance' => ,
                    // 'RCFinalbalance' => ,
                    // 'PymtDescription' => ,
                    // 'orderno' => ,
                    // 'ponum' => ,
                    // 'podate' => ,
                    // 'termdays' => ,
                    // 'termmode' => ,
                    // 'deptcode' => ,
                    'posteddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'approvedby' => ,
                    // 'approveddate' => ,
                    // 'unallocated' => ,
                    // 'datesend' => ,
                ]);

            DB::table('finance.gltran')
                ->insert([
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'auditno' => $value_unq->invno,
                    'lineno_' => $value_unq->lineno_,
                    'source' => 'PB', //kalau stock 'IV', lain dari stock 'DO'
                    'trantype' => 'IN',
                    'reference' => 'Bill No @ '.$value_unq->billno,
                    'description' => 'Final Bill', 
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'year' => $yearperiod->year,
                    'period' => $yearperiod->period,
                    'drcostcode' => $value_unq->actdebccode,
                    'dracc' => $value_unq->actdebglacc,
                    'crcostcode' => $sysparam->pvalue1,
                    'cracc' => $sysparam->pvalue2,
                    'amount' => $sum_amt
                ]);

            $this->init_glmastdtl(
                        $value_unq->actdebccode,//drcostcode
                        $value_unq->actdebglacc,//dracc
                        $sysparam->pvalue1,//crcostcode
                        $sysparam->pvalue2,//cracc
                        $yearperiod,
                        $sum_amt
                    );
        }

        foreach ($billdet_obj as $key_obj => $value_obj){

            if($value_obj->epistycode == 'IP' || $value_obj->epistycode == 'DP'){
                $cracc_ = $value_obj->ipacccode;
            }else{
                $cracc_ = $value_obj->opacccode;
            }

            DB::table('finance.gltran')
                ->insert([
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'auditno' => $value_obj->auditno,
                    'lineno_' => $value_obj->lineno_,
                    'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                    'trantype' => 'IN',
                    'reference' => $value_obj->invno,
                    'description' => $value_obj->chgcode, 
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'year' => $yearperiod->year,
                    'period' => $yearperiod->period,
                    'drcostcode' => $sysparam->pvalue1,
                    'dracc' => $sysparam->pvalue2,
                    'crcostcode' => $value_obj->dept_costcode,
                    'cracc' => $cracc_,
                    'amount' => $value_obj->amount 
                ]);

            $this->init_glmastdtl(
                    $sysparam->pvalue1,//drcostcode
                    $sysparam->pvalue2,//dracc
                    $value_obj->dept_costcode,//crcostcode
                    $cracc_,//cracc
                    $yearperiod,
                    $value_obj->amount
            );

            // if(!empty(floatval($value_obj->taxamount))){

            //     $sysparam_tx = DB::table('sysdb.sysparam')
            //                     ->where('compcode',session('compcode'))
            //                     ->where('source','TX')
            //                     ->where('trantype','BS')
            //                     ->first();

            //     DB::table('finance.gltran')
            //         ->insert([
            //             'compcode' => session('compcode'),
            //             'adduser' => session('username'),
            //             'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'auditno' => $value_obj->billno,
            //             'lineno_' => $value_obj->lineno_,
            //             'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
            //             'trantype' => 'TX',
            //             'reference' => $value_obj->invno,
            //             'description' => $value_obj->chgcode, 
            //             'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'year' => $yearperiod->year,
            //             'period' => $yearperiod->period,
            //             'drcostcode' => $sysparam->pvalue1,
            //             'dracc' => $sysparam->pvalue2,
            //             'crcostcode' => $sysparam_tx->pvalue1,
            //             'cracc' => $sysparam_tx->pvalue2,
            //             'amount' => $value_obj->taxamount
            //         ]);

            //     $this->init_glmastdtl(
            //         $sysparam->pvalue1,//drcostcode
            //         $sysparam->pvalue2,//dracc
            //         $sysparam_tx->pvalue1,//crcostcode
            //         $sysparam_tx->pvalue2,//cracc
            //         $yearperiod,
            //         $value_obj->taxamount
            //     );
            // }
            
            // if(!empty(floatval($value_obj->discamt))){
            //     $sysparam_dis = DB::table('sysdb.sysparam')
            //                     ->where('compcode',session('compcode'))
            //                     ->where('source','OE')
            //                     ->where('trantype','DIS')
            //                     ->first();

            //     DB::table('finance.gltran')
            //         ->insert([
            //             'compcode' => session('compcode'),
            //             'adduser' => session('username'),
            //             'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'auditno' => $value_obj->billno,
            //             'lineno_' => $value_obj->lineno_,
            //             'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
            //             'trantype' => 'DIS',
            //             'reference' => $value_obj->invno,
            //             'description' => $value_obj->chgcode, 
            //             'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'year' => $yearperiod->year,
            //             'period' => $yearperiod->period,
            //             'drcostcode' => $sysparam->pvalue1,
            //             'dracc' => $sysparam->pvalue2,
            //             'crcostcode' => $value_obj->dept_costcode,
            //             'cracc' => $sysparam_dis->pvalue1,
            //             'amount' => -$value_obj->discamt
            //         ]);

            //     $this->init_glmastdtl(
            //         $sysparam->pvalue1,//drcostcode
            //         $sysparam->pvalue2,//dracc
            //         $dept->costcode,//crcostcode
            //         $sysparam_dis->pvalue1,//cracc
            //         $yearperiod,
            //         -$value_obj->discamt
            //     );
            // }
        }
    }

    public function make_discharge($mrn,$episno){
        DB::table('hisdb.episode')
            ->where('compcode',session('compcode'))
            ->where('mrn',$mrn)
            ->where('episno',$episno)
            ->update([
                'dischargedate' => Carbon::now('Asia/Kuala_Lumpur'),
                'dischargeuser' => session('username'),
                'dischargetime' => Carbon::now('Asia/Kuala_Lumpur'),
                'episstatus' => 'BILL',
                'episactive' => 0,
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now('Asia/Kuala_Lumpur'),
            ]);

        DB::table('hisdb.pat_mast')
            ->where('compcode',session('compcode'))
            ->where('mrn',$mrn)
            ->update([
                'patstatus' => 0,
                'upduser' => session('username'),
                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
            ]);

        DB::table('hisdb.queue')
            ->where('compcode',session('compcode'))
            ->where('mrn',$mrn)
            ->where('episno',$episno)
            ->delete();
    }

    public function final_bill_invoice(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;

        if(empty($mrn) || empty($episno)){
            abort(403, 'Patient Not Exist');
        }

        $billdet = DB::table('hisdb.billdet as bd')
                        ->select('bd.idno','bd.mrn','bd.episno','bd.billno','bd.invno','bd.billdate','bd.trxdate','bd.billtype','btm.description as billtype_desc','bd.chgcode','chgm.description','bd.uom','bd.quantity','bd.unitprce','bd.amount','bd.taxamount','bd.discamt','bd.lineno_','ep.payercode','dm.name as debtorname','dm.address1','dm.address2','dm.address3','dm.address4','dm.contact','ep.refno','chgc.description as chgc_desc','chgc.classlevel','chgg.description as chgg_desc','chgt.description as chgt_desc','chgm.invgroup','chgm.chgclass','epis.pay_type','epis.reg_date','epis.reg_time','pm.name as pat_name','pm.newic','doc.doctorname as doc_name')
                        ->join('hisdb.chgmast as chgm', function($join) use ($mrn,$episno){
                            $join = $join->where('chgm.compcode',session('compcode'));
                            $join = $join->on('chgm.chgcode', '=', 'bd.chgcode');
                            $join = $join->on('chgm.uom', '=', 'bd.uom');
                        })
                        ->join('hisdb.pat_mast as pm', function($join) use ($mrn,$episno){
                            $join = $join->where('pm.compcode', '=', session('compcode'));
                            $join = $join->where('pm.mrn',$mrn);
                        })
                        ->join('hisdb.episode as epis', function($join) use ($mrn,$episno){
                            $join = $join->where('epis.compcode', '=', session('compcode'));
                            $join = $join->where('epis.mrn',$mrn);
                            $join = $join->where('epis.episno',$episno);
                        })
                        ->join('hisdb.doctor as doc', function($join) use ($request){
                            $join = $join->where('doc.compcode', '=', session('compcode'));
                            $join = $join->on('doc.doctorcode', '=', 'epis.admdoctor');
                        })
                        ->join('hisdb.epispayer as ep', function($join) use ($mrn,$episno){
                            $join = $join->where('ep.compcode',session('compcode'));
                            $join = $join->on('ep.lineno', '=', 'bd.lineno_');
                            $join = $join->where('ep.mrn',$mrn);
                            $join = $join->where('ep.episno',$episno);
                        })
                        ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->where('dm.compcode',session('compcode'));
                            $join = $join->on('dm.debtorcode', '=', 'ep.payercode');
                        })
                        ->leftJoin('hisdb.billtymst as btm', function($join) use ($request){
                            $join = $join->where('btm.compcode',session('compcode'));
                            $join = $join->on('btm.billtype', '=', 'bd.billtype');
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
                        ->where('bd.compcode',session('compcode'))
                        ->where('bd.mrn',$mrn)
                        ->where('bd.episno',$episno)
                        ->where('bd.taxflag',0)
                        ->where('bd.discflag',0)
                        ->orderBy('bd.chgcode','asc')
                        ->orderBy('chgm.invgroup','desc')
                        ->get();

        foreach ($billdet as $key => $value) {
            if(strtoupper($value->invgroup) == 'CC'){
                $value->pdescription = $value->description;
            }else if(strtoupper($value->invgroup) == 'CT'){
                $value->pdescription = $value->chgt_desc;
            }else{
                $value->pdescription = $value->chgg_desc;
            }
            $value->net_amount =  $value->amount;
        }

        $chgclass = $billdet->unique('chgclass')->sortBy('classlevel');
        $epispayer = $billdet->unique('lineno_')->sortBy('lineno_');

        foreach ($epispayer as $key => $value) {

            $value->LHDNStatus = '';
            $value->LHDNSubID = '';
            $value->LHDNCodeNo = '';
            $value->LHDNDocID = '';
            $value->LHDNSubBy = '';

            $einvoice = DB::table('sysdb.einvoice_log')
                            ->where('invno',$value->invno)
                            ->where('status','ACCEPTED')
                            ->orWhere('status','REJECTED')
                            ->orderBy('idno','DESC');

            if($einvoice->exists()){
                $einvoice = $einvoice->first();
                $value->LHDNStatus = $einvoice->status;
                $value->LHDNSubID = $einvoice->submissionUid;
                $value->LHDNCodeNo = $einvoice->invoiceCodeNumber;
                $value->LHDNDocID = $einvoice->uuid;
                $value->LHDNSubBy = $einvoice->subby;
            }
        }

        $invgroup = $billdet->unique('pdescription')->sortBy('pdescription');
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
        // dd($epispayer);

        return view('hisdb.ordcom.final_bill_invoice',compact('billdet','epispayer','invgroup','chgclass','username','footer'));
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
                        ->orderBy('trx.chgcode','asc')
                        ->orderBy('chgm.invgroup','desc')
                        ->where('trx.taxflag',0)
                        ->where('trx.discflag',0)
                        // ->orderBy('trx.adddate','asc')
                        ->leftjoin('hisdb.chgmast as chgm', function($join) use ($request){
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
        $invgroup = $chargetrx->unique('pdescription')->sortBy('pdescription');
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
                        ->select('trx.chgcode','trx.uom','trx.billno','chgm.description','trx.trxdate','trx.quantity','trx.amount','trx.discamt','trx.taxamount','chgm.invgroup','chgm.chgclass','chgc.description as chgc_desc','chgc.classlevel','chgg.description as chgg_desc','chgt.description as chgt_desc','doc.doctorname','doc.doctorcode')
                        ->where('trx.compcode',session('compcode'))
                        ->where('trx.trxtype','!=','PD')
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('trx.chgcode','asc')
                        ->orderBy('chgm.invgroup','desc')
                        ->where('trx.taxflag',0)
                        ->where('trx.discflag',0)
                        // ->orderBy('trx.adddate','asc')
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
        $invgroup = $chargetrx->unique('pdescription')->sortBy('pdescription');
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

    public function showpdf_summ_final(Request $request){
        $mrn = $request->mrn;
        $episno = $request->episno;

        if(empty($mrn) || empty($episno)){
            abort(403, 'Patient Not Exist');
        }

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
                        ->select('trx.chgcode','trx.uom','trx.billno','chgm.description','trx.trxdate','trx.quantity','trx.amount','trx.discamt','trx.taxamount','chgm.invgroup','chgm.chgclass','chgc.description as chgc_desc','chgc.classlevel','chgg.description as chgg_desc','chgt.description as chgt_desc','doc.doctorname','doc.doctorcode')
                        ->where('trx.compcode',session('compcode'))
                        ->where('trx.trxtype','!=','PD')
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('trx.chgcode','asc')
                        ->orderBy('chgm.invgroup','desc')
                        ->where('trx.taxflag',0)
                        ->where('trx.discflag',0)
                        // ->orderBy('trx.adddate','asc')
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
        $invgroup = $chargetrx->unique('pdescription')->sortBy('pdescription');
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