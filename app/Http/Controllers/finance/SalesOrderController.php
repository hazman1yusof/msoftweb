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

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_hdrtype':
                return $this->get_hdrtype($request);
            case 'get_hdrtype_check':
                return $this->get_hdrtype_check($request);
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
                        'db.auditno AS db_auditno',
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
                        'db.trantype AS db_trantype',
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
                        'db.upddate AS db_upddate'
                    );
        
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

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){ //SalesOrder_header_save 
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
            $chk_billtype = $this->chk_billtype($request);

            if($chk_billtype->error){
                throw new \Exception($chk_billtype->msg,500);
            }
            
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
                'unit' => strtoupper($request->db_deptcode),//department.sector
                'debtorcode' => strtoupper($request->db_debtorcode),
                'payercode' => strtoupper($request->db_debtorcode),
                'entrydate' => strtoupper($request->db_entrydate),
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'hdrtype' => strtoupper($request->db_hdrtype),
                // 'mrn' => strtoupper($request->db_mrn),
                'mrn' => '0',
                // 'billno' => $invno,
                'episno' => (!empty($request->db_mrn))?$pat_mast->Episno:null,
                'termdays' => strtoupper($request->db_termdays),
                'termmode' => strtoupper($request->db_termmode),
                'orderno' => strtoupper($request->db_orderno),
                'ponum' => strtoupper($request->db_ponum),
                'podate' => (!empty($request->db_podate))?$request->db_podate:null,
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
            'unit' => strtoupper($request->db_deptcode),
            'debtorcode' => strtoupper($request->db_debtorcode),
            'payercode' => strtoupper($request->db_debtorcode),
            'entrydate' => strtoupper($request->db_entrydate),
            'hdrtype' => strtoupper($request->db_hdrtype),
            // 'mrn' => strtoupper($request->db_mrn),
            'mrn' => '0',
            'termdays' => strtoupper($request->db_termdays),
            'termmode' => strtoupper($request->db_termmode),
            'orderno' => strtoupper($request->db_orderno),
            'ponum' => strtoupper($request->db_ponum),
            'podate' => (!empty($request->db_podate))?$request->db_podate:null,
            'remark' => strtoupper($request->db_remark),
            'approvedby' => strtoupper($request->db_approvedby)
        ];
        
        try {

            $chk_billtype = $this->chk_billtype($request);

            if($chk_billtype->error){
                throw new \Exception($chk_billtype->msg,500);
            }

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
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$value)
                            ->first();
                
                $billsum = DB::table("debtor.billsum")
                            ->where('compcode',session('compcode'))
                            ->where('source','=',$dbacthdr->source)
                            ->where('trantype','=',$dbacthdr->trantype)
                            ->where('billno','=',$dbacthdr->auditno)
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
                            'issdept' => $dbacthdr->deptcode,
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
                            // 'idno' => $billsum_obj->idno,
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
                            'issdept' => $dbacthdr->deptcode,
                            'invcode' => $chgmast->chggroup,
                            // 'inventory' => $chgmast->invflag,
                            // 'updinv' =>  $updinv,
                            'discamt' => $billsum_obj->discamt,
                            // 'qtyorder' => $billsum_obj->quantity,
                            // 'qtyissue' => $billsum_obj->quantity,
                            // 'unit' => $department->sector,
                            // 'chgtype' => $chgmast->chgtype,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'qtydispense' => $billsum_obj->quantity,
                            'taxcode' => $billsum_obj->taxcode,
                            'recstatus' => 'POSTED',
                        ]);
                    
                    //gltran
                    $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                    $chgmast = DB::table('hisdb.chgmast')
                                ->where('compcode',session('compcode'))
                                ->where('chgcode',$billsum_obj->chggroup)
                                ->first();

                    $chgtype = DB::table('hisdb.chgtype')
                                ->where('compcode',session('compcode'))
                                ->where('chgtype',$chgmast->chgtype)
                                ->first();

                    $dept = DB::table('sysdb.department')
                                ->where('compcode',session('compcode'))
                                ->where('deptcode',$dbacthdr->deptcode)
                                ->first();

                    $sysparam = DB::table('sysdb.sysparam')
                                ->where('compcode',session('compcode'))
                                ->where('source','AR')
                                ->where('trantype','AD')
                                ->first();


                    // 1. buat gltran
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => session('compcode'),
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'auditno' => $billsum_obj->auditno,
                            'lineno_' => 1,
                            'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                            'trantype' => 'IN',
                            'reference' => $invno,
                            'description' => $billsum_obj->chggroup, 
                            'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $sysparam->pvalue1,
                            'dracc' => $sysparam->pvalue2,
                            'crcostcode' => $dept->costcode,
                            'cracc' => $chgtype->opacccode,
                            'amount' => $billsum_obj->amount 
                        ]);

                    $this->init_glmastdtl(
                            $sysparam->pvalue1,//drcostcode
                            $sysparam->pvalue2,//dracc
                            $dept->costcode,//crcostcode
                            $chgtype->opacccode,//cracc
                            $yearperiod,
                            $billsum_obj->amount
                    );


                    if(!empty(floatval($billsum_obj->taxamt))){

                        $sysparam_tx = DB::table('sysdb.sysparam')
                                        ->where('compcode',session('compcode'))
                                        ->where('source','TX')
                                        ->where('trantype','BS')
                                        ->first();

                        DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'auditno' => $billsum_obj->auditno,
                                'lineno_' => 1,
                                'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                                'trantype' => 'TX',
                                'reference' => $invno,
                                'description' => $billsum_obj->chggroup, 
                                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $sysparam->pvalue1,
                                'dracc' => $sysparam->pvalue2,
                                'crcostcode' => $sysparam_tx->pvalue1,
                                'cracc' => $sysparam_tx->pvalue2,
                                'amount' => $billsum_obj->taxamt
                            ]);

                        $this->init_glmastdtl(
                            $sysparam->pvalue1,//drcostcode
                            $sysparam->pvalue2,//dracc
                            $sysparam_tx->pvalue1,//crcostcode
                            $sysparam_tx->pvalue2,//cracc
                            $yearperiod,
                            $billsum_obj->taxamt
                        );
                    }
                    
                    if(!empty(floatval($billsum_obj->discamt))){
                        $sysparam_dis = DB::table('sysdb.sysparam')
                                        ->where('compcode',session('compcode'))
                                        ->where('source','OE')
                                        ->where('trantype','DIS')
                                        ->first();

                        DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'auditno' => $billsum_obj->auditno,
                                'lineno_' => 1,
                                'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                                'trantype' => 'DIS',
                                'reference' => $invno,
                                'description' => $billsum_obj->chggroup, 
                                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $sysparam->pvalue1,
                                'dracc' => $sysparam->pvalue2,
                                'crcostcode' => $dept->costcode,
                                'cracc' => $sysparam_dis->pvalue1,
                                'amount' => -$billsum_obj->discamt
                            ]);

                        $this->init_glmastdtl(
                            $sysparam->pvalue1,//drcostcode
                            $sysparam->pvalue2,//dracc
                            $dept->costcode,//crcostcode
                            $sysparam_dis->pvalue1,//cracc
                            $yearperiod,
                            -$billsum_obj->discamt
                        );
                    }
                }
                
                DB::table("debtor.billsum")
                    ->where('compcode',session('compcode'))
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('billno','=',$dbacthdr->auditno)
                    ->update([
                        'invno' => $invno,
                        'recstatus' => 'POSTED',
                    ]);
                
                DB::table("debtor.dbacthdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        'invno' => $invno,
                        'recstatus' => 'POSTED',
                        'posteddate' => Carbon::now("Asia/Kuala_Lumpur")
                        //'amount' => accumalated amount (billsum.amt-billsum.discamt+billsum.taxamt)
                        //'outamount' => accumalated amount (billsum.amt-billsum.discamt+billsum.taxamt)
                    ]);

                $debtormast = DB::table("debtor.debtormast")
                                ->where('compcode',session('compcode'))
                                ->where('debtorcode',$dbacthdr->payercode)
                                ->first();

                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => $dbacthdr->auditno,
                        'lineno_' => 1,
                        'source' => 'PB', //kalau stock 'IV', lain dari stock 'DO'
                        'trantype' => 'IN',
                        'reference' => $invno,
                        'description' => $billsum_obj->chggroup, 
                        'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $debtormast->actdebccode,
                        'dracc' => $debtormast->actdebglacc,
                        'crcostcode' => $sysparam->pvalue1,
                        'cracc' => $sysparam->pvalue2,
                        'amount' => $dbacthdr->amount
                    ]);

                $this->init_glmastdtl(
                            $debtormast->actdebccode,//drcostcode
                            $debtormast->actdebglacc,//dracc
                            $sysparam->pvalue1,//crcostcode
                            $sysparam->pvalue2,//cracc
                            $yearperiod,
                            $dbacthdr->amount
                        );
                
                
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
            
        }
        
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
        $idno = $request->idno;
        if(!$idno){
            abort(404);
        }

        $dbacthdr = DB::table('debtor.dbacthdr as h', 'debtor.debtormast as m', 'debtor.debtortype as dt', 'hisdb.billtymst as bt')
            ->select('h.source','h.trantype','h.compcode', 'h.idno', 'h.auditno', 'h.lineno_', 'h.amount', 'h.outamount', 'h.recstatus', 'h.debtortype', 'h.debtorcode', 'h.mrn', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'h.entrydate',
            'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4', 'm.creditterm as crterm','m.billtype as billtype','dt.debtortycode as dt_debtortycode', 'dt.description as dt_description', 'bt.billtype as billtype', 'bt.description as bt_desc')
            ->leftJoin('debtor.debtormast as m', 'h.debtorcode', '=', 'm.debtorcode')
            ->leftJoin('debtor.debtortype as dt', 'dt.debtortycode', '=', 'm.debtortype')
            ->leftJoin('hisdb.billtymst as bt', 'bt.billtype', '=', 'm.billtype')
            ->where('h.idno','=',$idno)
            ->where('h.mrn','=','0')
            ->where('h.compcode','=',session('compcode'))
            ->first();
// dd($dbacthdr);
        $billsum = DB::table('debtor.billsum AS b', 'material.productmaster AS p', 'material.uom as u', 'debtor.debtormast as d', 'hisdb.chgmast as m')
            ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus',
            'u.description as uom_desc', 
            'd.debtorcode as debt_debtcode','d.name as debt_name', 
            'm.description as chgmast_desc')
            ->leftJoin('hisdb.chgmast as m', function($join) use ($request){
                $join = $join->on('b.chggroup', '=', 'm.chgcode');
                $join = $join->on('b.uom', '=', 'm.uom');
            })
            //->leftJoin('material.productmaster as p', 'b.description', '=', 'p.description')
            ->leftJoin('material.uom as u', 'b.uom', '=', 'u.uomcode')
            ->leftJoin('debtor.debtormast as d', 'b.debtorcode', '=', 'd.debtorcode')
            ->where('b.source','=',$dbacthdr->source)
            ->where('b.trantype','=',$dbacthdr->trantype)
            ->where('b.billno','=',$dbacthdr->auditno)
            ->where('b.compcode','=',session('compcode'))
            ->get();

        // $chgmast = DB::table('debtor.billsum AS b', 'hisdb.chgmast as m')
        //     ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus', 'm.description as chgmast_desc')
        //     ->leftJoin('hisdb.chgmast as m', 'b.description', '=', 'm.description')
        //     ->where('b.source','=',$dbacthdr->source)
        //     ->where('b.trantrype','=',$dbacthdr->trantrype)
        //     ->where('b.billno','=',$dbacthdr->auditno)
        //     ->get();
        
        if ( $dbacthdr->recstatus == "OPEN") {
            $title = "DRAFT INVOICE";
        } elseif ( $dbacthdr->recstatus == "POSTED"){
            $title = " INVOICE";
        }

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
        
        // $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    
        // return $pdf->stream();
        
        return view('finance.SalesOrder.SalesOrder_pdfmake',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
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

    public function crtivdspdt($billsum_obj,$product,$dbacthdr,$stockloc){//xguna
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

    public function get_hdrtype(Request $request){

        $billtymst = collect();
        $today = Carbon::now("Asia/Kuala_Lumpur");


        $billtymst2 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNotNull('effdatefrom')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst2->exists()){
            foreach ($billtymst2->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst3 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->whereNull('effdatefrom')
                            ->whereNotNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst3->exists()){
            foreach ($billtymst3->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst4 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today);
        if($billtymst4->exists()){
            foreach ($billtymst4->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $responce = new stdClass();
        $responce->rows = $billtymst;

        return json_encode($responce);
    }

    public function get_hdrtype_check(Request $request){
        $hdrtype = $request->filterVal[0];
        $billtymst = collect();
        $today = Carbon::now("Asia/Kuala_Lumpur");


        $billtymst2 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNotNull('effdatefrom')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst2->exists()){
            foreach ($billtymst2->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst3 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNull('effdatefrom')
                            ->whereNotNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst3->exists()){
            foreach ($billtymst3->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst4 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today);
        if($billtymst4->exists()){
            foreach ($billtymst4->get() as $key => $value) {
                $billtymst->push($value);
            }
        }
        
        $responce = new stdClass();
        $responce->rows = $billtymst;

        return json_encode($responce);
    }

    public function chk_billtype(Request $request){ 
        $hdrtype = $request->db_hdrtype;
        $posteddate = $request->db_entrydate;
        $responce = new stdClass();
        $responce->error = false;
        $responce->msg = '';

        $billtymst_active = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','!=','ACTIVE');
        if($billtymst_active->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype deactive, please check..';

            return $responce;
        }

        $billtymst_opprice = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('opprice','!=','1');
        if($billtymst_opprice->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype setup incorrect, please check..';

            return $responce;
        }

        $billtymst2 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdateto','<=',$posteddate);
        if($billtymst2->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype date exceed , please check..';

            return $responce;
        }

        $billtymst4 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','>=',$posteddate);
        if($billtymst4->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype date exceed, please check..';

            return $responce;
        }

        return $responce;
    }

    
}
