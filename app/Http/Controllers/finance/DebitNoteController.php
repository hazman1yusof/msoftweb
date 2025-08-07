<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class DebitNoteController extends defaultController
{
    var $gltranAmount;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.DebitNote.DebitNote');
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_alloc_table':
                return $this->get_alloc_table($request);
            default:
                return 'error happen..';
        }
    }
    
    public function maintable(Request $request){
        
        $table = DB::table('debtor.dbacthdr AS db')
                    ->select(
                        'db.compcode AS db_compcode',
                        'db.auditno AS db_auditno',
                        'db.debtorcode AS db_debtorcode',
                        'db.payercode AS db_payercode',
                        'dm.name AS dm_name',
                        'db.entrydate AS db_entrydate',
                        'db.unit AS db_unit',
                        'db.ponum AS db_ponum',
                        'db.amount AS db_amount',
                        'db.paymode AS db_paymode',
                        'db.recstatus AS db_recstatus',
                        'db.remark AS db_remark',
                        'db.source AS db_source',
                        'db.trantype AS db_trantype',
                        'db.lineno_ AS db_lineno_',
                        'db.orderno AS db_orderno',
                        'db.outamount AS db_outamount',
                        'db.debtortype AS db_debtortype',
                        'db.billdebtor AS db_billdebtor',
                        'db.approvedby AS db_approvedby',
                        'db.mrn AS db_mrn',
                        'db.termmode AS db_termmode',
                        'db.hdrtype AS db_hdrtype',
                        'db.source AS db_source',
                        'db.posteddate AS db_posteddate',
                        'db.deptcode AS db_deptcode',
                        'db.idno AS db_idno',
                        'db.adduser AS db_adduser',
                        'db.adddate AS db_adddate',
                        'db.upduser AS db_upduser',
                        'db.upddate AS db_upddate',
                        'db.reference AS db_reference'
                    )
                    ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                    ->where('db.compcode',session('compcode'))
                    ->where('db.source','=','PB')
                    ->where('db.trantype','DN');
        
        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $pieces = explode(".", $request->filterVal[$key], 2);
                if($pieces[0] == 'session'){
                    $table = $table->where($request->filterCol[$key],'=',session($pieces[1]));
                }else if($pieces[0] == '<>'){
                    $table = $table->where($request->filterCol[$key],'<>',$pieces[1]);
                }else if($pieces[0] == '>'){
                    $table = $table->where($request->filterCol[$key],'>',$pieces[1]);
                }else if($pieces[0] == '>='){
                    $table = $table->where($request->filterCol[$key],'>=',$pieces[1]);
                }else if($pieces[0] == '<'){
                    $table = $table->where($request->filterCol[$key],'<',$pieces[1]);
                }else if($pieces[0] == '<='){
                    $table = $table->where($request->filterCol[$key],'<=',$pieces[1]);
                }else if($pieces[0] == 'on'){
                    $table = $table->whereColumn($request->filterCol[$key],$pieces[1]);
                }else if($pieces[0] == 'null'){
                    $table = $table->whereNull($request->filterCol[$key]);
                }else if($pieces[0] == 'raw'){
                    $table = $table->where($request->filterCol[$key],'=',DB::raw($pieces[1]));
                }else{
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }
        }
        
        if(!empty($request->fromdate)){
            $table = $table->where('db.entrydate','>=',$request->fromdate);
            $table = $table->where('db.entrydate','<=',$request->todate);
        }
        
        if(!empty($request->searchCol)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }
            
            $count = array_count_values($searchCol_array);
            // dump($request->searchCol);
            
            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);
                
                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
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
        //     $dbactdtl = DB::table('debtor.dbactdtl')
        //                 ->where('source','=',$value->db_source)
        //                 ->where('trantype','=',$value->db_trantype)
        //                 ->where('auditno','=',$value->db_auditno);
        
        //     if($dbactdtl->exists()){
        //         $value->dbactdtl_outamt = $dbactdtl->sum('amount');
        //     }else{
        //         $value->dbactdtl_outamt = $value->dbacthdr_outamount;
        //     }
        
        //     // $apalloc = DB::table('finance.apalloc')
        //     //             ->select('allocdate')
        //     //             ->where('refsource','=',$value->dbacthdr_source)
        //     //             ->where('reftrantype','=',$value->dbacthdr_trantype)
        //     //             ->where('refauditno','=',$value->dbacthdr_auditno)
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
    
    public function get_alloc_table(Request $request){
        
        $table = DB::table('debtor.dballoc as dc')
                        ->select(
                            'dc.debtorcode as debtorcode',
                            'da.entrydate as entrydate',
                            'da.posteddate as posteddate',
                            'dc.allocdate as allocdate',
                            'dc.recptno as recptno',
                            'dc.trantype as trantype',
                            'dc.refamount as refamount',
                            'dc.outamount as outamount',
                            'dc.amount as amount',
                            'dc.balance as balance',
                            'dc.compcode as compcode',
                            'dc.source as source',
                            'dc.auditno as auditno',
                            'dc.lineno_ as lineno_',
                            'dc.docsource as docsource',
                            'dc.doctrantype as doctrantype',
                            'dc.docauditno as docauditno',
                            'dc.refsource as refsource',
                            'dc.reftrantype as reftrantype',
                            'dc.refauditno as refauditno',
                            'dc.idno as idno'
                        )
                        ->join('debtor.dbacthdr as da', function($join) use ($request){
                                    $join = $join->on('dc.refsource', '=', 'da.source')
                                        ->on('dc.reftrantype', '=', 'da.trantype')
                                        ->on('dc.refauditno', '=', 'da.auditno');
                        })
                        ->where('dc.compcode','=',session('compcode'))
                        ->where('dc.refsource','=',$request->source)
                        ->where('dc.reftrantype','=',$request->trantype)
                        ->where('dc.refauditno','=',$request->auditno)
                        ->where('dc.recstatus','=',"POSTED");
        
        //////////paginate/////////
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
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

            if(strtoupper($request->db_debtorcode) == 'ND0001'){
                throw new \Exception('Debtorcode ND0001 - Non Debtor invalid', 500);
            }
            
            $auditno = $this->recno('PB','DN');
            // $auditno = str_pad($auditno, 5, "0", STR_PAD_LEFT);
            
            $array_insert = [
                'source' => 'PB',
                'trantype' => 'DN',
                'auditno' => $auditno,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'lineno_' => 1,
                // 'invno' => $invno,
                'deptcode' => strtoupper($request->db_deptcode),
                'unit' => session('unit'),
                'debtorcode' => strtoupper($request->db_debtorcode),
                'payercode' => strtoupper($request->db_debtorcode),
                'entrydate' => $request->db_entrydate,
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'hdrtype' => strtoupper($request->db_hdrtype),
                'paymode' => strtoupper($request->db_paymode),
                // 'mrn' => strtoupper($request->db_mrn),
                // 'billno' => $invno,
                'episno' => (!empty($request->db_mrn))?$pat_mast->Episno:null,
                //'termdays' => strtoupper($request->db_termdays),
                'termmode' => strtoupper($request->db_termmode),
                'orderno' => strtoupper($request->db_orderno),
                'ponum' => strtoupper($request->db_ponum),
                'remark' => strtoupper($request->db_remark),
                'approvedby' => $request->db_approvedby,
                'approveddate' => strtoupper($request->db_approveddate),
                'reference' => $request->db_reference,
            ];
            
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $idno = $table->insertGetId($array_insert);
            
            $responce = new stdClass();
            $responce->db_auditno = $auditno;
            $responce->idno = $idno;
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
            'unit' => session('unit'),
            'debtorcode' => strtoupper($request->db_debtorcode),
            'payercode' => strtoupper($request->db_debtorcode),
            'entrydate' => $request->db_entrydate,
            'hdrtype' => strtoupper($request->db_hdrtype),
            'paymode' => strtoupper($request->db_paymode),
            'mrn' => $request->db_mrn,
            //'termdays' => strtoupper($request->db_termdays),
            'termmode' => strtoupper($request->db_termmode),
            'orderno' => strtoupper($request->db_orderno),
            'ponum' => strtoupper($request->db_ponum),
            'remark' => strtoupper($request->db_remark),
            'approvedby' => $request->approvedby,
            'reference' => $request->db_reference,
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
            
            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=',session('compcode'))
                            ->first();
                            
            if($dbacthdr->outamount != $dbacthdr->amount){
                throw new \Exception('Already allocate, cant cancel', 500);
            }
            
            if($dbacthdr->recstatus == 'POSTED'){
                
                $this->gltran_cancel($request->idno);
                
                DB::table('debtor.dbacthdr')
                    ->where('idno','=',$request->idno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'CANCELLED' 
                    ]);
                    
            }else{
                
                DB::table('debtor.dbacthdr')
                    ->where('idno','=',$request->idno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'CANCELLED' 
                    ]);
                    
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function posted(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            foreach ($request->idno_array as $value){
                
                $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('idno','=',$value)
                    ->first();

                if($dbacthdr->recstatus != 'OPEN'){
                    continue;
                }
                
                if($dbacthdr->amount == 0){
                    throw new \Exception('Debit Note auditno: '.$dbacthdr->auditno.' amount cant be zero', 500);
                }
                
                $yearperiod = defaultController::getyearperiod_($dbacthdr->entrydate);
                if($yearperiod->status == 'C'){
                    throw new \Exception('Debit Note auditno: '.$dbacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
                }
                    
                $this->gltran($value);
                
                DB::table('debtor.dbacthdr')
                    ->where('idno','=',$value)
                    ->update([
                        'posteddate' => $dbacthdr->entrydate,
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
                    
                // $purreqhd = DB::table("material.purreqhd")
                //     ->where('idno','=',$value);
                
                // $purreqhd_get = $purreqhd->first();
                // if(!in_array($purreqhd_get->recstatus, ['POSTED'])){
                //     continue;
                // }
                
                // $purreqhd->update([
                //     'recstatus' => 'CANCELLED'
                // ]);
                
                // DB::table("material.purreqdt")
                //     ->where('recno','=',$purreqhd_get->recno)
                //     ->update([  
                //         'recstatus' => 'CANCELLED',
                //         'upduser' => session('username'),
                //         'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                //     ]);
                
                // DB::table("material.queuepr")
                //     ->where('recno','=',$purreqhd_get->recno)
                //     ->delete();
                
            }
           
            DB::commit();
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function gltran($idno){
        $dbacthdr_obj = DB::table('debtor.dbacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        $dbactdtl_obj = DB::table('debtor.dbactdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$dbacthdr_obj->source)
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno);

        if($dbactdtl_obj->exists()){

            $dbactdtl_get = $dbactdtl_obj->get();

            foreach ($dbactdtl_get as $key => $value){
                $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->entrydate);

                $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
                $dept_obj = $this->gltran_fromdept($value->deptcode);
                $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

                //1. buat gltran
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => $dbacthdr_obj->compcode,
                        'auditno' => $dbacthdr_obj->auditno,
                        'lineno_' => $key+1,
                        'source' => $dbacthdr_obj->source,
                        'trantype' => $dbacthdr_obj->trantype,
                        'reference' => $value->document,
                        'description' => $dbacthdr_obj->remark,
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $debtormast_obj->actdebccode,
                        'dracc' => $debtormast_obj->actdebglacc,
                        'crcostcode' => $dept_obj->costcode,
                        'cracc' => $paymode_obj->glaccno,
                        'amount' => $value->amount,
                        'postdate' => $dbacthdr_obj->entrydate,
                        'adduser' => $dbacthdr_obj->adduser,
                        'adddate' => $dbacthdr_obj->adddate,
                        'idno' => null
                    ]);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                $gltranAmount =  defaultController::isGltranExist_($debtormast_obj->actdebccode,$debtormast_obj->actdebglacc,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$debtormast_obj->actdebccode)
                        ->where('glaccount','=',$debtormast_obj->actdebglacc)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $value->amount + $gltranAmount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $debtormast_obj->actdebccode,
                            'glaccount' => $debtormast_obj->actdebglacc,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($dept_obj->costcode,$paymode_obj->glaccno,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$dept_obj->costcode)
                        ->where('glaccount','=',$paymode_obj->glaccno)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $gltranAmount - $value->amount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $dept_obj->costcode,
                            'glaccount' => $paymode_obj->glaccno,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => - $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }
            }
        }
    }

    public function gltran_cancel($idno){
        $dbacthdr_obj = DB::table('debtor.dbacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        $dbactdtl_obj = DB::table('debtor.dbactdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$dbacthdr_obj->source)
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno);

        if($dbactdtl_obj->exists()){

            $dbactdtl_get = $dbactdtl_obj->get();

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$dbacthdr_obj->source)
                ->where('trantype','=',$dbacthdr_obj->trantype)
                ->where('auditno','=',$dbacthdr_obj->auditno)
                ->delete();

            foreach ($dbactdtl_get as $key => $value){
                $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->entrydate);

                $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
                $dept_obj = $this->gltran_fromdept($value->deptcode);
                $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                $gltranAmount =  defaultController::isGltranExist_($debtormast_obj->actdebccode,$debtormast_obj->actdebglacc,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$debtormast_obj->actdebccode)
                        ->where('glaccount','=',$debtormast_obj->actdebglacc)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $gltranAmount - $value->amount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($dept_obj->costcode,$paymode_obj->glaccno,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$dept_obj->costcode)
                        ->where('glaccount','=',$paymode_obj->glaccno)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $gltranAmount + $value->amount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }
            }
        }
    }
    
    public function showpdf(Request $request){
        
        $auditno = $request->auditno;
        if(!$auditno){
            abort(404);
        }
        
        $dbacthdr = DB::table('debtor.dbacthdr as h', 'debtor.debtormast as m')
                    ->select('h.idno', 'h.compcode', 'h.auditno', 'h.lineno_', 'h.amount', 'h.outamount', 'h.recstatus', 'h.entrydate', 'h.debtortype', 'h.debtorcode', 'h.remark', 'h.mrn', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'h.reference', 'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4')
                    ->leftJoin('debtor.debtormast as m', 'h.debtorcode', '=', 'm.debtorcode')
                    ->where('h.compcode',session('compcode'))
                    ->where('h.source','=','PB')
                    ->where('h.trantype','=','DN')
                    ->where('h.auditno','=',$auditno)
                    ->first();
        
        if ($dbacthdr->recstatus == "OPEN") {
            $title = "DRAFT";
        } elseif ($dbacthdr->recstatus == "POSTED"){
            $title = "DEBIT NOTE";
        }
        
        $dbactdtl = DB::table('debtor.dbactdtl as t')
                    ->select('t.idno', 't.compcode', 't.source', 't.trantype', 't.auditno', 't.lineno_', 't.entrydate', 't.document', 't.amount', 't.paymode', 't.deptcode', 't.recstatus', 't.GSTCode', 't.AmtB4GST', 't.unit', 't.tot_gst', 'd.description as dept_description')
                    ->leftJoin('sysdb.department as d', 't.deptcode', '=', 'd.deptcode')
                    ->where('t.compcode',session('compcode'))
                    ->where('t.source','=','PB')
                    ->where('t.trantype','=','DN')
                    ->where('t.auditno','=',$auditno)
                    ->get();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $sysparam = DB::table('sysdb.sysparam')
                    ->select('pvalue2')
                    ->where('compcode',session('compcode'))
                    ->where('source','=','HIS')
                    ->where('trantype','=','BANK')
                    ->first();
        
        $totamount_expld = explode(".", (float)$dbacthdr->amount);
        
        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])." RINGGIT ";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        // $pdf = PDF::loadView('finance.AR.DebitNote.DebitNote_pdf',compact('dbacthdr','title','dbactdtl','company','sysparam','totamt_eng'));
        // return $pdf->stream();


        return view('finance.AR.DebitNote.DebitNote_pdfmake',compact('dbacthdr','title','dbactdtl','company','sysparam','totamt_eng'));
        
        return view('finance.AR.DebitNote.DebitNote_pdf',compact('dbacthdr','title','dbactdtl','company','sysparam','totamt_eng'));
        
    }
    
    public function gltran_fromdept($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        return $obj;
    }

    public function gltran_frompaymode($paymode){
        $obj = DB::table('debtor.paymode')
                ->select('glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AR')
                ->where('paymode','=',$paymode)
                ->first();

        return $obj;
    }

    public function gltran_fromdebtormast($payercode){
        $obj = DB::table('debtor.debtormast')
                ->select('actdebglacc','actdebccode')
                ->where('compcode','=',session('compcode'))
                ->where('debtorcode','=',$payercode)
                ->first();

        return $obj;
    }

}