<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class CreditNoteARController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AR.CreditNoteAR.CreditNoteAR');
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){

        $table = DB::table('debtor.dbacthdr AS db')
                    ->select(
                        'db.debtorcode AS db_debtorcode',
                        'db.payercode AS db_payercode',
                        'dm.name AS dm_name', 
                        'db.entrydate AS db_entrydate',
                        'db.auditno AS db_auditno', //search
                        'db.invno AS db_invno',
                        'db.ponum AS db_ponum',
                        'db.amount AS db_amount',
                        'db.remark AS db_remark',
                        'db.lineno_ AS db_lineno_',
                        'db.orderno AS db_orderno',
                        'db.outamount AS db_outamount',
                        'db.debtortype AS db_debtortype',
                        'db.billdebtor AS db_billdebtor',
                        'db.approvedby AS db_approvedby',
                        'db.mrn AS db_mrn', 
                        'db.unit AS db_unit',
                        'db.source AS db_source',
                        'db.trantype AS db_trantype', //search
                        'db.termdays AS db_termdays',
                        'db.termmode AS db_termmode',
                        'db.hdrtype AS db_hdrtype',
                        'db.podate AS db_podate',
                        'db.posteddate AS db_posteddate',
                        'db.deptcode AS db_deptcode',
                        'db.recstatus AS db_recstatus',
                        'db.idno AS db_idno',
                        'db.adduser AS db_adduser',
                        'db.adddate AS db_adddate',
                        'db.upduser AS db_upduser',
                        'db.upddate AS db_upddate',
                        'db.reference AS db_reference',
                        'db.paymode AS db_paymode',
                        
                    )
                    ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                    ->where('db.source','=','PB');

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->filterdate)){
            $table = $table->where('db.entrydate','>',$request->filterdate[0]);
            $table = $table->where('db.entrydate','<',$request->filterdate[1]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.auditno','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.invno','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'db_trantype'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.trantype','like',$request->searchVal[0]);
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

        foreach ($paginate->items() as $key => $value) {
            $dbactdtl = DB::table('debtor.dbactdtl')
                        ->where('source','=',$value->db_source)
                        ->where('trantype','=',$value->db_trantype)
                        ->where('auditno','=',$value->db_auditno);
            
            if($dbactdtl->exists()){
                $value->dbactdtl_outamt = $dbactdtl->sum('amount');
            }else{
                $value->dbactdtl_outamt = $value->db_outamount;
            }
            
            $dballoc = DB::table('debtor.dballoc')
                        ->where('docsource','=',$value->db_source)
                        ->where('doctrantype','=',$value->db_trantype)
                        ->where('docauditno','=',$value->db_auditno);
            
            if($dballoc->exists()){
                $value->unallocated = false;
            }else{
                $value->unallocated = true;
            }
        }

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
            case 'posted_single':
                return $this->posted_single($request);break;
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
            case 'save_alloc':
                return $this->save_alloc($request);break;
            default:
                return 'Errors happen';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        $table = DB::table("debtor.dbacthdr");

        try { 

            $auditno = $this->recno('PB','CN');
            $auditno = str_pad($auditno, 5, "0", STR_PAD_LEFT);

            $array_insert = [
                'source' => 'PB',
                'trantype' => 'CN',
                'auditno' => $auditno,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'lineno_' => 1,
                // 'invno' => $invno,
                'deptcode' => strtoupper($request->db_deptcode),
                'unit' => strtoupper($request->db_deptcode),//department.sector
                'debtorcode' => strtoupper($request->db_debtorcode),
                'payercode' => strtoupper($request->db_debtorcode),
                'posteddate' => $request->posteddate,
                'entrydate' => $request->posteddate,
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'hdrtype' => strtoupper($request->db_hdrtype),
                // 'mrn' => strtoupper($request->db_mrn),
                // 'billno' => $invno,
                'episno' => (!empty($request->db_mrn))?$pat_mast->Episno:null,
                //'termdays' => strtoupper($request->db_termdays),
                'termmode' => strtoupper($request->db_termmode),
                'orderno' => strtoupper($request->db_orderno),
                'ponum' => strtoupper($request->db_ponum),
                'remark' => strtoupper($request->db_remark),
                'approvedby' => $request->db_approvedby,
                // 'approveddate' => $request->db_approveddate,
                'reference' => $request->db_reference,
                'paymode' => $request->db_paymode,
            ];

            //////////where//////////
            // $table = $table->where('idno','=',$request->idno);
            $idno_apacthdr = $table->insertGetId($array_insert);

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->idno = $idno_apacthdr;
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
            'unit' => strtoupper($request->db_deptcode),
            'debtorcode' => strtoupper($request->db_debtorcode),
            'payercode' => strtoupper($request->db_debtorcode),
            'entrydate' => $request->db_entrydate,
            'hdrtype' => strtoupper($request->db_hdrtype),
            'mrn' => strtoupper($request->db_mrn),
            //'termdays' => strtoupper($request->db_termdays),
            'termmode' => strtoupper($request->db_termmode),
            'orderno' => strtoupper($request->db_orderno),
            'ponum' => strtoupper($request->db_ponum),
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

    public function posted(Request $request){
        DB::beginTransaction();
        try {


            foreach ($request->idno_array as $idno){

                $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('idno','=',$idno)
                    ->first();

                // $this->gltran($idno);

                DB::table('debtor.dbacthdr')
                    ->where('idno','=',$idno)
                    ->update([
                        'recstatus' => 'POSTED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // $apalloc = DB::table('finance.apalloc')
                //     ->where('compcode','=',session('compcode'))
                //     ->where('unit','=',session('unit'))
                //     ->where('source','=', $apacthdr->source)
                //     ->where('trantype','=', $apacthdr->trantype)
                //     ->where('auditno','=', $apacthdr->auditno)
                //     ->update([
                //         'recstatus' => 'POSTED',
                //         'lastuser' => session('username'),
                //         'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                //     ]);

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function save_alloc(Request $request){
        DB::beginTransaction();
        try {

            $dbacthdr = DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
                ->first();

            foreach ($request->data_detail as $key => $value){
                $dbacthdr_IV = DB::table('debtor.dbacthdr')
                        ->where('idno','=',$value['idno'])
                        ->first();

                $outamount = floatval($value['outamount']);
                $balance = floatval($value['balance']);
                $allocamount = floatval($value['outamount']) - floatval($value['balance']);
                $newoutamount_IV = floatval($outamount - $allocamount);

                DB::table('debtor.dballoc')
                        ->insert([                            
                            'compcode' => session('compcode'),
                            'source' => 'AR',
                            'trantype' => 'CN',
                            'auditno' => $dbacthdr->auditno,
                            'lineno_' => $key+1,
                            'docsource' => 'AR',
                            'doctrantype' => 'CN',
                            'paymode' => $dbacthdr->paymode, 
                            'docauditno' => $dbacthdr->auditno,
                            'refsource' => $dbacthdr_IV->source,
                            'reftrantype' => $dbacthdr_IV->trantype,
                            'refauditno' => $dbacthdr_IV->auditno,
                            'refamount' => $dbacthdr_IV->amount,
                            'debtorcode' => $dbacthdr->debtorcode,
                            'payercode' => $dbacthdr->payercode,
                            'allocdate' => $dbacthdr->posteddate,
                            'recptno' => $request->recptno,
                            'amount' => $allocamount,
                            'outamount' => $outamount,
                            'balance' => $balance,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'OPEN'
                        ]);

                $dbacthdr_IV = DB::table('debtor.dbacthdr')
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'outamount' => $newoutamount_IV
                    ]);
            }

            //calculate total amount from detail
            $totalAmount = DB::table('debtor.dballoc')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$dbacthdr->auditno)
                ->where('source','=','AR')
                ->where('trantype','=','CN')
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            //then update to header
            DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
                ->update([
                    'amount' => $totalAmount,
                    'outamount' => $totalAmount,
                ]);  

            DB::commit();

            $responce = new stdClass();
            $responce->result = 'success';

            return json_encode($responce);
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function posted_single(Request $request){

        DB::beginTransaction();

        try {
            
            $dbacthdr = DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
                ->first();
            
            // $this->gltran($request->idno);
            
            DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
                ->update([
                    'recstatus' => 'POSTED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);
            
            DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('unit','=',session('unit'))
                ->where('source','=', $dbacthdr->source)
                ->where('trantype','=', $dbacthdr->trantype)
                ->where('auditno','=', $dbacthdr->auditno)
                ->update([
                    'recstatus' => 'POSTED'
                ]);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->result = 'success';
            
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function gltran($idno){
        $apacthdr_obj = DB::table('finance.dbacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->recdate);

        $credit_obj = $this->gltran_frompaymode($apacthdr_obj->paymode,$apacthdr_obj->source);
        $debit_obj = $this->gltran_fromsupp($apacthdr_obj->suppcode,$apacthdr_obj->trantype);

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => $apacthdr_obj->compcode,
                'adduser' => $apacthdr_obj->adduser,
                'adddate' => $apacthdr_obj->adddate,
                'auditno' => $apacthdr_obj->auditno,
                'lineno_' => 1,
                'source' => $apacthdr_obj->source,
                'trantype' => $apacthdr_obj->trantype,
                'reference' => $apacthdr_obj->document,
                'postdate' => $apacthdr_obj->recdate,
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $debit_obj->costcode,
                'dracc' => $debit_obj->glaccno,
                'crcostcode' => $credit_obj->glccode,
                'cracc' => $credit_obj->glaccno,
                'amount' => $apacthdr_obj->amount,
                'idno' => null
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  defaultController::isGltranExist_($debit_obj->costcode,$debit_obj->glaccno,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$debit_obj->costcode)
                ->where('glaccount','=',$debit_obj->glaccno)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $apacthdr_obj->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $debit_obj->costcode,
                    'glaccount' => $debit_obj->glaccno,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $apacthdr_obj->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($credit_obj->glccode,$credit_obj->glaccno,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$credit_obj->glccode)
                ->where('glaccount','=',$credit_obj->glaccno)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $apacthdr_obj->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $credit_obj->glccode,
                    'glaccount' => $credit_obj->glaccno,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$apacthdr_obj->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
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

        $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }

        $pdf = PDF::loadView('finance.CreditNoteAR.CreditNoteAR_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
        return $pdf->stream();      

        
        return view('finance.CreditNoteAR.CreditNoteAR_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    }

}