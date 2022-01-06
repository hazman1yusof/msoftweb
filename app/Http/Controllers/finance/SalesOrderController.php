<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class SalesOrderController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.SalesOrder.SalesOrder');
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
            case 'reopen':
                return $this->reopen($request);
            case 'cancel':
                return $this->cancel($request);
            case 'support':
                return $this->support($request);
            case 'verify':
                return $this->verify($request);
            case 'approved':
                return $this->approved($request);
            case 'refresh_do':
                return $this->refresh_do($request);
            default:
                return 'Errors happen';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        $table = DB::table("debtor.dbacthdr");

        try { 

            $auditno = $this->recno('PB','IN');

            if(!empty($request->db_mrn)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('compcode','=',session('compcode'))
                            ->where('MRN','=',$request->db_mrn)
                            ->first();
            }

            $array_insert = [
                'source' => 'PB',
                'trantype' => 'IN',
                'auditno' => $auditno,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'lineno_' => 1,
                // 'invno' => $invno,
                'deptcode' => strtoupper($request->db_deptcode),
                'units' => strtoupper($request->db_deptcode),//department.sector
                'debtorcode' => strtoupper($request->db_debtorcode),
                'payercode' => strtoupper($request->db_debtorcode),
                'entrydate' => strtoupper($request->db_entrydate),
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'hdrtype' => strtoupper($request->db_hdrtype),
                'mrn' => strtoupper($request->db_mrn),
                // 'billno' => $invno,
                'episno' => (!empty($request->db_mrn))?$pat_mast->Episno:null,
                'termdays' => strtoupper($request->db_termdays),
                'termmode' => strtoupper($request->db_termmode),
                'orderno' => strtoupper($request->db_orderno),
                'ponum' => strtoupper($request->db_ponum),
                'podate' => $request->db_podate,
                'remark' => strtoupper($request->db_remark),
                'approvedby' => $request->db_approvedby
            ];


            //////////where//////////
            // $table = $table->where('idno','=',$request->idno);
            $idno = $table->insertGetId($array_insert);

            $responce = new stdClass();
            $responce->totalAmount = 0.00;
            $responce->idno = $idno;
            $responce->auditno = $auditno;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        $table = DB::table("debtor.dbacthdr");

        $array_update = [
            'deptcode' => strtoupper($request->db_deptcode),
            'units' => strtoupper($request->db_deptcode),
            'debtorcode' => strtoupper($request->db_debtorcode),
            'payercode' => strtoupper($request->db_debtorcode),
            'entrydate' => strtoupper($request->db_entrydate),
            'hdrtype' => strtoupper($request->db_hdrtype),
            'mrn' => strtoupper($request->db_mrn),
            'termdays' => strtoupper($request->db_termdays),
            'termmode' => strtoupper($request->db_termmode),
            'orderno' => strtoupper($request->db_orderno),
            'ponum' => strtoupper($request->db_ponum),
            'podate' => strtoupper($request->db_podate),
            'remark' => strtoupper($request->db_remark),
            'approvedby' => strtoupper($request->db_approvedby)
        ];

        try {
            //////////where//////////
            $table = $table->where('idno','=',$request->db_idno);
            $table->update($array_update);

            $responce = new stdClass();
            $responce->totalAmount = $request->purreqhd_totamount;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function del(Request $request){

    }

    public function posted(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $invno = $this->recno('PB','INV');

                $dbacthdr = DB::table("debtor.dbacthdr")
                            ->where('idno','=',$value)
                            ->first();

                $billsum = DB::table("debtor.billsum")
                            ->where('source','=',$dbacthdr->source)
                            ->where('trantype','=',$dbacthdr->trantype)
                            ->where('auditno','=',$dbacthdr->auditno)
                            ->get();

                foreach ($billsum as $billsum_obj){

                    $chgmast = DB::table("hisdb.chgmast")
                            ->where('compcode','=',session('compcode'))
                            ->where('chgcode','=',$billsum_obj->chggroup)
                            ->where('uom','=',$billsum_obj->uom)
                            ->first();

                    $updinv = ($chgmast->invflag == '1')? 1 : 0;

                    $insertGetId = DB::table("hisdb.chargetrx")
                        ->insertGetId([
                            'auditno' => $billsum_obj->auditno,
                            'idno' => $billsum_obj->idno,
                            'compcode'  => session('compcode'),
                            'mrn'  => $billsum_obj->mrn,
                            'episno'  => $billsum_obj->episno,
                            'trxdate' => $dbacthdr->entrydate,
                            'chgcode' => $billsum_obj->chggroup,
                            'billflag' => 1,
                            'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'billtype'  => $billsum_obj->billtype,
                            'chg_class' => $chgmast->chgclass,
                            'unitprce' => $billsum_obj->unitprice,
                            'quantity' => $billsum_obj->quantity,
                            'amount' => $billsum_obj->amount,
                            'trxtime' => $dbacthdr->entrytime,
                            'chggroup' => $chgmast->chggroup,
                            'taxamount' => $billsum_obj->taxamt,
                            'billno' => $invno,
                            'uom' => $billsum_obj->uom,
                            'billtime' => $dbacthdr->entrytime,
                            'invgroup' => $chgmast->invgroup,
                            'reqdept' => $dbacthdr->deptcode,
                            'isudept' => $dbacthdr->deptcode,
                            'invcode' => $chgmast->chggroup,
                            'inventory' => $chgmast->invflag,
                            'updinv' =>  $updinv,
                            'discamt' => $billsum_obj->discamt,
                            'qtyorder' => $billsum_obj->quantity,
                            'qtyissue' => $billsum_obj->quantity,
                            'unit' => session('unit'),
                            'chgtype' => $chgmast->chgtype,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'qtydispense' => $billsum_obj->quantity,
                            'taxcode' => $billsum_obj->taxcode,
                            'recstatus' => 'POSTED',
                        ]);

                    DB::table("hisdb.billdet")
                        ->insert([
                            'auditno' => $billsum_obj->auditno,
                            'idno' => $billsum_obj->idno,
                            'compcode'  => session('compcode'),
                            'mrn'  => $billsum_obj->mrn,
                            'episno'  => $billsum_obj->episno,
                            'trxdate' => $dbacthdr->entrydate,
                            'chgcode' => $billsum_obj->chggroup,
                            'billflag' => 1,
                            'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'billtype'  => $billsum_obj->billtype,
                            'chg_class' => $chgmast->chgclass,
                            'unitprce' => $billsum_obj->unitprice,
                            'quantity' => $billsum_obj->quantity,
                            'amount' => $billsum_obj->amount,
                            'trxtime' => $dbacthdr->entrytime,
                            'chggroup' => $chgmast->chggroup,
                            'taxamount' => $billsum_obj->taxamt,
                            'billno' => $invno,
                            'uom' => $billsum_obj->uom,
                            'billtime' => $dbacthdr->entrytime,
                            'invgroup' => $chgmast->invgroup,
                            'reqdept' => $dbacthdr->deptcode,
                            'isudept' => $dbacthdr->deptcode,
                            'invcode' => $chgmast->chggroup,
                            // 'inventory' => $chgmast->invflag,
                            // 'updinv' =>  $updinv,
                            'discamt' => $billsum_obj->discamt,
                            // 'qtyorder' => $billsum_obj->quantity,
                            // 'qtyissue' => $billsum_obj->quantity,
                            // 'units' => $department->sector,
                            // 'chgtype' => $chgmast->chgtype,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'qtydispense' => $billsum_obj->quantity,
                            'taxcode' => $billsum_obj->taxcode,
                            'recstatus' => 'POSTED',
                        ]);

                    // //product
                    // /*update Product qtyonhand*/
                    // $product = DB::table('material.product')
                    //         ->where('compcode','=',session('compcode'))
                    //         ->where('uomcode','=',$billsum_obj->uom)
                    //         ->where('itemcode','=',$billsum_obj->chggroup);

                    // $stockloc = DB::table('material.stockloc')
                    //         ->where('compcode','=',session('compcode'))
                    //         ->where('uomcode','=',$billsum_obj->uom)
                    //         ->where('itemcode','=',$billsum_obj->chggroup)
                    //         ->where('deptcode','=',$dbacthdr->deptcode)
                    //         ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

                    // if($stockloc->exists()){
                    //     $stockloc = $stockloc->first();
                    // }else{
                    //     throw new \Exception("Stockloc not exists for item: ".$billsum_obj->chggroup." dept: ".$dbacthdr->deptcode." uom: ".$billsum_obj->uom,500);
                    // }

                    // if($product->exists()){
                    //     $product = $product->first();
                    //     if($product->groupcode == 'Stock'){

                    //         $ivdspdt = DB::table('material.ivdspdt')
                    //             ->where('compcode','=',session('compcode'))
                    //             ->where('recno','=',$billsum_obj->idno);

                    //         if($ivdspdt->exists()){
                    //             $this->updivdspdt($billsum_obj,$product,$dbacthdr,$stockloc,$insertGetId);
                    //         }else{
                    //             $this->crtivdspdt($billsum_obj,$product,$dbacthdr,$stockloc,$insertGetId);
                    //         }

                    //     }
                    // }

                    // if($stockloc->disptype == 'DS'){
                    //     //ignore uom
                    //     // $qtyonhand = $product->qtyonhand - $billsum_obj->quantity;
                        
                    // }

                }

                DB::table("debtor.billsum")
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('auditno','=',$dbacthdr->auditno)
                    ->update([
                        'invno' => $invno,
                        'recstatus' => 'POSTED',
                    ]);

                DB::table("debtor.dbacthdr")
                    ->where('idno','=',$value)
                    ->update([
                        'invno' => $invno,
                        'recstatus' => 'POSTED',
                        'posteddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);


            }
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    // public function reopen(Request $request){

    //     DB::beginTransaction();

    //     try{

    //         foreach ($request->idno_array as $value){

    //             $purreqhd = DB::table("material.purreqhd")
    //                 ->where('idno','=',$value);

    //             $purreqhd_get = $purreqhd->first();
    //             if(!in_array($purreqhd_get->recstatus, ['CANCELLED','REQUEST','SUPPORT','VERIFIED','APPROVED'])){
    //                 continue;
    //             }

    //             $purreqhd->update([
    //                 'recstatus' => 'OPEN',
    //                 'requestby' => null,
    //                 'requestdate' => null,
    //                 'supportby' => null,
    //                 'supportdate' => null,
    //                 'verifiedby' => null,
    //                 'verifieddate' => null,
    //                 'approvedby' => null,
    //                 'approveddate' => null,
    //             ]);

    //             DB::table("material.purreqdt")
    //                 ->where('recno','=',$purreqhd_get->recno)
    //                 ->update([
    //                     'recstatus' => 'OPEN',
    //                     'upduser' => session('username'),
    //                     'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
    //                 ]);

    //             DB::table("material.queuepr")
    //                 ->where('recno','=',$purreqhd_get->recno)
    //                 ->delete();

    //         }

    //         DB::commit();
        
    //     } catch (\Exception $e) {
    //         DB::rollback();

    //         return response($e->getMessage(), 500);
    //     }
    // }

    public function cancel(Request $request){
        DB::beginTransaction();

        try{


            $dbacthdr = DB::table("debtor.dbacthdr")
                        ->where('idno','=',$request->idno)
                        ->first();

            $billsum = DB::table("debtor.billsum")
                        ->where('source','=',$dbacthdr->source)
                        ->where('trantype','=',$dbacthdr->trantype)
                        ->where('auditno','=',$dbacthdr->auditno)
                        ->get();

            DB::table("debtor.billsum")
                ->where('source','=',$dbacthdr->source)
                ->where('trantype','=',$dbacthdr->trantype)
                ->where('auditno','=',$dbacthdr->auditno)
                ->update([
                    'recstatus' => 'CANCELLED',
                ]);

            DB::table("debtor.dbacthdr")
                ->where('idno','=',$request->idno)
                ->update([
                    'recstatus' => 'CANCELLED',
                ]);
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function support(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$value)){

                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','VERIFIED')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','VERIFIED')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('maxlimit','>=',$purreqhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("Authorization for this purchase request doesnt exists",500);
                            }
                            
                    }

                    $authorise_use = $authorise->first();

                    $purreqhd->update([
                            'verifiedby' => $authorise_use->authorid,
                            'supportdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'SUPPORT'
                        ]);

                    DB::table("material.purreqdt")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'SUPPORT',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    DB::table("material.queuepr")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'AuthorisedID' => $authorise_use->authorid,
                            'recstatus' => 'SUPPORT',
                            'trantype' => 'VERIFIED',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                }


                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'SUPPORT';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';
            
                //$this->sendemail($data);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function verify(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$value)){

                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','APPROVED')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','APPROVED')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('maxlimit','>=',$purreqhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("Authorization for this purchase request doesnt exists",500);
                            }
                            
                    }

                    $authorise_use = $authorise->first();

                    $purreqhd->update([
                            'approvedby' => $authorise_use->authorid,
                            'verifieddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'VERIFIED'
                        ]);

                    DB::table("material.purreqdt")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'VERIFIED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    DB::table("material.queuepr")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'AuthorisedID' => $authorise_use->authorid,
                            'recstatus' => 'VERIFIED',
                            'trantype' => 'APPROVED',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                }


                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'APPROVED';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';

                //$this->sendemail($data);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function approved(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                $purreqhd->update([
                        'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'APPROVED'
                    ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'APPROVED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepr")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function showpdf(Request $request){
        $auditno = $request->auditno;
        if(!$auditno){
            abort(404);
        }

        $dbacthdr = DB::table('debtor.dbacthdr as h', 'debtor.debtormast as m')
            ->select('h.compcode', 'h.idno', 'h.auditno', 'h.lineno_', 'h.amount', 'h.outamount', 'h.recstatus', 'h.debtortype', 'h.debtorcode', 'h.mrn', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'h.entrydate',
            'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4')
            ->leftJoin('debtor.debtormast as m', 'h.debtorcode', '=', 'm.debtorcode')

            ->where('auditno','=',$auditno)
            ->first();

        if ( $dbacthdr->recstatus == "OPEN") {
            $title = "DRAFT INVOICE";
        } elseif ( $dbacthdr->recstatus == "POSTED"){
            $title = " INVOICE";
        }

        $billsum = DB::table('debtor.billsum AS b', 'material.productmaster AS p', 'material.uom as u', 'debtor.debtormast as d', 'hisdb.chgmast as m')
            ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.auditno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus',
            'u.description as uom_desc', 
            'd.debtorcode as debt_debtcode','d.name as debt_name', 
            'm.description as chgmast_desc')
            ->leftJoin('hisdb.chgmast as m', 'b.chggroup', '=', 'm.chgcode')
            //->leftJoin('material.productmaster as p', 'b.description', '=', 'p.description')
            ->leftJoin('material.uom as u', 'b.uom', '=', 'u.uomcode')
            ->leftJoin('debtor.debtormast as d', 'b.debtorcode', '=', 'd.debtorcode')

            ->where('auditno','=',$auditno)
            ->get();

        $chgmast = DB::table('debtor.billsum AS b', 'hisdb.chgmast as m')
            ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.auditno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus', 'm.description as chgmast_desc')
            ->leftJoin('hisdb.chgmast as m', 'b.description', '=', 'm.description')

            ->where('auditno','=',$auditno)
            ->get();

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $totamount_expld = explode(".", (float)$dbacthdr->amount);

        $totamt_bm_rm = $this->convertNumberToWord($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWord($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }

        $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
        return $pdf->stream();      

        
        return view('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    }

    //function sendmeail($data) -- nak kena ada atau tak

    function skip_authorization(Request $request, $deptcode, $idno){
        $authdtl = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PR')
                    ->where(function($q) use ($deptcode) {
                          $q->where('deptcode', $deptcode)
                            ->orWhere('deptcode', 'ALL');
                      })
                    ->where('recstatus','=','APPROVED');
        // dd($authdtl->first());

        if($authdtl->count() > 0){

            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$idno);

            $purreqhd_get = $purreqhd->first();

            $authdtl = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PR')
                    ->where(function($q) use ($deptcode) {
                          $q->where('deptcode', $deptcode)
                            ->orWhere('deptcode', 'ALL');
                      });

            $array_update = [];
            $array_update['requestby'] = session('username');
            $array_update['requestdate'] = Carbon::now("Asia/Kuala_Lumpur");
            $array_update['recstatus'] = 'APPROVED';
            foreach ($authdtl->get() as $key => $value) {

                switch ($value->recstatus) {
                    case 'SUPPORT':
                        $array_update['supportby'] = session('username');
                        $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
                        break;
                    case 'VERIFIED':
                        $array_update['verifiedby'] = session('username');
                        $array_update['verifieddate'] = Carbon::now("Asia/Kuala_Lumpur");
                        break;
                    case 'APPROVED':
                        $array_update['approvedby'] = session('username');
                        $array_update['approveddate'] = Carbon::now("Asia/Kuala_Lumpur");
                        break;
                }
                
            }

            $purreqhd->update($array_update);
            

            DB::table("material.purreqdt")
                ->where('recno','=',$purreqhd_get->recno)
                ->update([
                    'recstatus' => 'APPROVED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $queuepr = DB::table("material.queuepr")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->where('AuthorisedID','=',session('username'))
                        ->where('deptcode','=',$purreqhd_get->reqdept);

            if($queuepr->exists()){

                $queuepr
                    ->update([
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                    ]);
                    
            }else{

                DB::table("material.queuepr")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purreqhd_get->recno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => $purreqhd_get->reqdept,
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            return true;
        }
        
        return false;   
    }

    public function updivdspdt($billsum_obj,$product,$dbacthdr,$stockloc,$insertGetId){
        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $billsum_obj->idno,
            'lineno_' => $billsum_obj->lineno_,
            'itemcode' => $billsum_obj->chggroup,
            'txnqty' => $billsum_obj->quantity,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $billsum_obj->unitprice,
            'productcat' => $product->productcat,
            'reqdept' => $dbacthdr->deptcode,
            'saleamt' => $billsum_obj->amount,
            'trantype' => $billsum_obj->trantype,
            'trandate' => $dbacthdr->entrydate,
            'trxaudno' => $billsum_obj->idno,
            'mrn' => $dbacthdr->mrn,
            'episno' => $dbacthdr->episno,
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        $ivtntype = DB::table('material.ivtntype')
                        ->where('compcode','=', session('compcode'))
                        ->where('trantype','=', $stockloc->disptype)
                        ->first();

        if($ivtntype->updamt = 1){
            $category = DB::table('material.category')
                        ->where('compcode','=', session('compcode'))
                        ->where('catcode','=', $product->productcat)
                        ->first();

            $department = DB::table('sysdb.departmet')
                        ->where('compcode','=', session('compcode'))
                        ->where('deptcode','=',$dbacthdr->deptcode)
                        ->first();

            $ivdspdt_arr['DrCcode'] = $department->costcode;
            $ivdspdt_arr['DrAccNo'] = $category->cosacct;
            $ivdspdt_arr['CrCcode'] = $department->costcode;
            $ivdspdt_arr['CrAccNo'] = $category->stockacct;

            $glinface_arr = [
                'compcode' => session('compcode'),
                'Source' => 'IV',
                'TranType' => $stockloc->disptype,
                'AuditNo' => $insertGetId,
                'LineNo' => 1,
                'Reference' => $dbacthdr->deptcode.' - '.$billsum_obj->chggroup,
                'IdNo' => $dbacthdr->mrn.' - '.$dbacthdr->episno,
                'Description' => 'Posted from Online-Dispensing',
                'Amount' => $billsum_obj->amount,
                'PostDate' => $dbacthdr->entrydate,
                'OprType' => 'A',
                'LastUser' => session('username'),
                'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur")
            ];

            if($ivtntype->crdbfl = 'OUT'){
                $glinface_arr['DrCcode'] = $department->costcode;
                $glinface_arr['DrAccNo'] = $category->cosacct;
                $glinface_arr['CrCcode'] = $department->costcode;
                $glinface_arr['CrAccNo'] = $category->stockacct;
            }

            DB::table('finance.glinface')
                ->insert($glinface_arr);
        }

        DB::table('material.ivdspdt')
                ->insert($ivdspdt_arr);
    }

    public function crtivdspdt($billsum_obj,$product,$dbacthdr,$stockloc){
        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $billsum_obj->idno,
            'lineno_' => $billsum_obj->lineno_,
            'itemcode' => $billsum_obj->chggroup,
            'txnqty' => $billsum_obj->quantity,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $billsum_obj->unitprice,
            'productcat' => $product->productcat,
            'reqdept' => $dbacthdr->deptcode,
            'saleamt' => $billsum_obj->amount,
            'trantype' => $billsum_obj->trantype,
            'trandate' => $dbacthdr->entrydate,
            'trxaudno' => $billsum_obj->idno,
            'mrn' => $dbacthdr->mrn,
            'episno' => $dbacthdr->episno,
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        $ivtntype = DB::table('material.ivtntype')
                        ->where('compcode','=', session('compcode'))
                        ->where('trantype','=', $stockloc->disptype)
                        ->first();

        if($ivtntype->updamt = 1){
            $category = DB::table('material.category')
                        ->where('compcode','=', session('compcode'))
                        ->where('catcode','=', $product->productcat)
                        ->first();

            $department = DB::table('sysdb.departmet')
                        ->where('compcode','=', session('compcode'))
                        ->where('deptcode','=',$dbacthdr->deptcode)
                        ->first();

            $ivdspdt_arr['DrCcode'] = $department->costcode;
            $ivdspdt_arr['DrAccNo'] = $category->cosacct;
            $ivdspdt_arr['CrCcode'] = $department->costcode;
            $ivdspdt_arr['CrAccNo'] = $category->stockacct;

            $glinface_arr = [
                'compcode' => session('compcode'),
                'Source' => 'IV',
                'TranType' => $stockloc->disptype,
                'AuditNo' => $insertGetId,
                'LineNo' => 1,
                'Reference' => $dbacthdr->deptcode.' - '.$billsum_obj->chggroup,
                'IdNo' => $dbacthdr->mrn.' - '.$dbacthdr->episno,
                'Description' => 'Posted from Online-Dispensing',
                'Amount' => $billsum_obj->amount,
                'PostDate' => $dbacthdr->entrydate,
                'OprType' => 'A',
                'LastUser' => session('username'),
                'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur")
            ];

            if($ivtntype->crdbfl = 'OUT'){
                $glinface_arr['DrCcode'] = $department->costcode;
                $glinface_arr['DrAccNo'] = $category->cosacct;
                $glinface_arr['CrCcode'] = $department->costcode;
                $glinface_arr['CrAccNo'] = $category->stockacct;
            }

            DB::table('finance.glinface')
                ->insert($glinface_arr);
        }

        DB::table('material.ivdspdt')
                ->insert($ivdspdt_arr);
    }
    
}

