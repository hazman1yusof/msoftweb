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

            $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('compcode','=',session('compcode'))
                            ->where('MRN','=',$request->db_mrn)
                            ->first();

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
                'episno' => $pat_mast->Episno,
                'termdays' => strtoupper($request->db_termdays),
                'termmode' => strtoupper($request->db_termmode),
                'orderno' => strtoupper($request->db_orderno),
                'ponum' => strtoupper($request->db_ponum),
                'podate' => strtoupper($request->db_podate),
                'remark' => strtoupper($request->db_remark),
                'approvedby' => $request->db_approvedby
            ];


            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->insert($array_insert);

            $responce = new stdClass();
            // $responce->totalAmount = $request->purreqhd_totamount;
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
            'approvedby' => $request->approvedby
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

                $department = DB::table("sysdb.department")
                            ->where('deptcode','=',$dbacthdr->deptcode)
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
                            ->first();

                    $updinv = ($chgmast->invflag == '1')? 1 : 0;

                    $insertGetId = DB::table("hisdb.chargetrx")
                        ->insertGetId([
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
                            'units' => $department->sector,
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
                            'compcode'  => session('compcode'),
                            'mrn'  => $billsum_obj->mrn,
                            'episno'  => $billsum_obj->episno,
                            'trxdate' => $dbacthdr->entrydate,
                            'chgcode' => $billsum_obj->chggroup,
                            'auditno' => $insertGetId,
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

                }


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

            return response($e->getMessage().$e, 500);
        }
    }

    public function reopen(Request $request){

        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();
                if(!in_array($purreqhd_get->recstatus, ['CANCELLED','REQUEST','SUPPORT','VERIFIED','APPROVED'])){
                    continue;
                }

                $purreqhd->update([
                    'recstatus' => 'OPEN',
                    'requestby' => null,
                    'requestdate' => null,
                    'supportby' => null,
                    'supportdate' => null,
                    'verifiedby' => null,
                    'verifieddate' => null,
                    'approvedby' => null,
                    'approveddate' => null,
                ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'OPEN',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                DB::table("material.queuepr")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->delete();

            }

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function cancel(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();
                if(!in_array($purreqhd_get->recstatus, ['OPEN'])){
                    continue;
                }

                $purreqhd->update([
                    'recstatus' => 'CANCELLED'
                ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepr")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->delete();

            }
           
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
        $mrn = $request->mrn;
        if(!$mrn){
            abort(404);
        }

        $dbacthdr = DB::table('debtor.billsum')
            ->where('mrn','=',$mrn)
            ->first();

        $billsum = DB::table('debtor.billsum AS blsm', 'material.productmaster AS p', 'material.uom as u')
            ->select('blsm.compcode', 'blsm.idno', 'blsm.mrn', 'blsm.auditno', 'blsm.lineno_', 'blsm.chgclass', 'blsm.chggroup', 'p.description', 'blsm.uom', 'blsm.quantity', 'blsm.amount', 'blsm.outamt', 'blsm.taxamt', 'blsm.unitprice', 'blsm.taxcode', 'blsm.discamt', 'blsm.recstatus', 'u.description as uom_desc')
            ->leftJoin('material.productmaster as p', 'blsm.description', '=', 'p.description')
            ->leftJoin('material.uom as u', 'blsm.uom', '=', 'u.uomcode')
            ->where('mrn','=',$mrn)
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

        $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company'));
        return $pdf->stream();      

        
        return view('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company'));
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

    
}

