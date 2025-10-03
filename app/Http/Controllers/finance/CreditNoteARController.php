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
            case 'get_alloc_when_edit':
                return $this->get_alloc_when_edit($request);
            case 'get_alloc_table':
                return $this->get_alloc_table($request);
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
                        'db.unallocated AS db_unallocated',
                        
                    )
                    ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                    ->where('db.source','=','PB')
                    ->where('db.trantype','CN');
        
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
        
        // if(!empty($request->searchCol)){
        //     if($request->searchCol[0] == 'db_auditno'){
        //         $table = $table->Where(function ($table) use ($request) {
        //                 $table->Where('db.auditno','like',$request->searchVal[0]);
        //             });
        //     }else if($request->searchCol[0] == 'db_invno'){
        //         $table = $table->Where(function ($table) use ($request) {
        //                 $table->Where('db.invno','like',$request->searchVal[0]);
        //             });
        //     }else if($request->searchCol[0] == 'db_trantype'){
        //         $table = $table->Where(function ($table) use ($request) {
        //                 $table->Where('db.trantype','like',$request->searchVal[0]);
        //             });
        //     }else{
        //         $table = $table->Where(function ($table) use ($request) {
        //                 $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
        //             });
        //     }          
        // }
        
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

    public function get_alloc_when_edit(Request $request){
        
        $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('debtorcode',$request->filterVal[0])
                    ->where('compcode',session('compcode'))
                    ->where('recstatus','!=','CANCELLED')
                    ->where('outamount','>',0)
                    ->where('source','PB')
                    ->where('posteddate','<=',$request->posteddate)
                    ->whereIn('trantype',['IN','DN']);
        
        $dballoc = DB::table('debtor.dballoc')
                    ->where('compcode',session('compcode'))
                    ->where('docsource','PB')
                    ->where('doctrantype','CN')
                    ->where('docauditno',$request->auditno)
                    ->where('recstatus','!=','CANCELLED');
        
        $return_array=[];
        $got_array=[];
        if($dballoc->exists()){
            foreach ($dbacthdr->get() as $obj_dbacthdr) {
                foreach ($dballoc->get() as $obj_dballoc) {
                    if(!in_array($obj_dbacthdr->idno,$got_array)){
                        if(
                            $obj_dballoc->refsource == $obj_dbacthdr->source
                            && $obj_dballoc->reftrantype == $obj_dbacthdr->trantype
                            && $obj_dballoc->refauditno == $obj_dbacthdr->auditno
                        ){
                            $obj_dbacthdr->can_alloc=false;
                            $obj_dbacthdr->outamount = $obj_dballoc->outamount;
                            $obj_dbacthdr->refamount = $obj_dballoc->refamount;
                            $obj_dbacthdr->amount = $obj_dballoc->amount;
                            $obj_dbacthdr->source = $obj_dballoc->source;
                            $obj_dbacthdr->trantype = $obj_dballoc->trantype;
                            $obj_dbacthdr->auditno = $obj_dballoc->auditno;
                            $obj_dbacthdr->lineno_ = $obj_dballoc->lineno_;
                            $obj_dbacthdr->idno = $obj_dballoc->idno;
                            
                            if(!in_array($obj_dbacthdr, $return_array)){
                                array_push($return_array,$obj_dbacthdr);
                            }
                            
                            array_push($got_array,$obj_dbacthdr->idno);
                        }else{
                            $obj_dbacthdr->refamount = $obj_dbacthdr->outamount;
                            $obj_dbacthdr->can_alloc=true;
                            
                            if(!in_array($obj_dbacthdr, $return_array)){
                                array_push($return_array,$obj_dbacthdr);
                            }
                        }
                    }
                }
            }
        }else{
            $return_array = $dbacthdr->get();
        }
        
        $responce = new stdClass();
        $responce->rows = $return_array;
        
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
                                    $join = $join->on('dc.docsource', '=', 'da.source')
                                        ->on('dc.doctrantype', '=', 'da.trantype')
                                        ->on('dc.docauditno', '=', 'da.auditno');
                        })
                        ->where('dc.compcode','=',session('compcode'))
                        ->where('dc.docsource','=',$request->source)
                        ->where('dc.doctrantype','=',$request->trantype)
                        ->where('dc.docauditno','=',$request->auditno)
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
            case 'del_alloc':
                return $this->del_alloc($request);
            default:
                return 'Errors happen';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $dbacthdr = DB::table('debtor.dbacthdr')
            //             ->where('idno','=',$request->idno)
            //             ->first();

            if(strtoupper($request->db_debtorcode) == 'xxND0001'){
                throw new \Exception('Debtorcode ND0001 - Non Debtor invalid', 500);
            }
            
            if($request->db_unallocated == '1') {
            
                $this->check_alloc_exists($request);
                
                $auditno = $this->recno('PB','CN');
                // $auditno = str_pad($auditno, 5, "0", STR_PAD_LEFT);
                
                $table = DB::table("debtor.dbacthdr");
                
                $array_insert = [
                    'source' => 'PB',
                    'trantype' => 'CN',
                    'auditno' => $auditno,
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    'lineno_' => 1,
                    'recptno' => 'CN-'.$auditno,
                    // 'invno' => $invno,
                    'deptcode' => strtoupper($request->db_deptcode),
                    'unit' => session('unit'),
                    'debtorcode' => strtoupper($request->db_debtorcode),
                    'payercode' => strtoupper($request->db_debtorcode),
                    'entrydate' => $request->db_entrydate,
                    'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'entryuser' => session('username'),
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
                    'unallocated' => $request->db_unallocated,
                    
                ];
                
            } else if($request->db_unallocated == '0'){
                
                $auditno = $this->recno('PB','CN');
                // $auditno = str_pad($auditno, 5, "0", STR_PAD_LEFT);
                
                $table = DB::table("debtor.dbacthdr");
                
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
                    'unit' => session('unit'),
                    'debtorcode' => strtoupper($request->db_debtorcode),
                    'payercode' => strtoupper($request->db_debtorcode),
                    'entrydate' => $request->db_entrydate,
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
                    'unallocated' => $request->db_unallocated,
                    
                ];
                
            }
            
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
        
        try {
            
            // $dbacthdr = DB::table('debtor.dbacthdr')
            //             ->where('idno','=',$request->idno)
            //             ->first();
            
            if($request->db_unallocated == '1'){
            
                $this->check_alloc_exists($request);
                
                $table = DB::table("debtor.dbacthdr");
                
                $array_update = [
                    'deptcode' => strtoupper($request->db_deptcode),
                    'unit' => session('unit'),
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
                    'approvedby' => $request->approvedby,
                    'unallocated' => $request->db_unallocated,
                    'reference' => $request->db_reference,
                ];
                
            }else if($request->db_unallocated == '0'){
                
                $table = DB::table("debtor.dbacthdr");
                
                $array_update = [
                    'deptcode' => strtoupper($request->db_deptcode),
                    'unit' => session('unit'),
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
                    'approvedby' => $request->approvedby,
                    'unallocated' => $request->db_unallocated,
                    'reference' => $request->db_reference,
                ];
                
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
        
        try {
            
            foreach ($request->idno_array as $idno){
                
                $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('idno','=',$idno)
                    ->first();
                    
                if($dbacthdr->amount == 0){
                    throw new \Exception('Credit Note auditno: '.$dbacthdr->auditno.' amount cant be zero', 500);
                }
                
                $yearperiod = defaultController::getyearperiod_($dbacthdr->entrydate);
                if($yearperiod->status == 'C'){
                    throw new \Exception('Credit Note auditno: '.$dbacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
                }
                
                $this->gltran($idno);
                
                DB::table('debtor.dbacthdr')
                    ->where('idno','=',$idno)
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
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function save_alloc(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // FROM CN
            $dbacthdr = DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
                ->first();
                
            foreach ($request->data_detail as $key => $value){
                $auditno_al = $this->defaultSysparam('AR','AL');
                
                // FROM DN
                $dbacthdr_IV = DB::table('debtor.dbacthdr')
                        ->where('idno','=',$value['idno'])
                        ->first();
                        
                $outamount = floatval($value['outamount']);
                $balance = floatval($value['balance']);
                $allocamount = floatval($value['outamount']) - floatval($value['balance']);
                $newoutamount_IV = floatval($outamount - $allocamount);
                
                if($allocamount == 0 || $value['can_alloc'] == 'false'){
                    continue;
                }
                
                $lineno_ = DB::table('debtor.dballoc') 
                                ->where('compcode','=',session('compcode'))
                                ->where('docauditno','=',$dbacthdr->auditno)
                                ->where('docsource','=','PB')
                                ->where('doctrantype','=','CN')->max('lineno_');
                
                if($lineno_ == null){
                    $lineno_ = 1;
                }else{
                    $lineno_ = $lineno_+1;
                }
                
                // buat allocation
                DB::table('debtor.dballoc')
                        ->insert([                            
                            'compcode' => session('compcode'),
                            'source' => 'AR',
                            'trantype' => 'AL',
                            'auditno' => $auditno_al,
                            'lineno_' => $lineno_,
                            'docsource' => $dbacthdr->source,
                            'doctrantype' => $dbacthdr->trantype,
                            'docauditno' => $dbacthdr->auditno,
                            'paymode' => $dbacthdr->paymode, 
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
                            'recstatus' => 'POSTED'
                        ]);
                        
                // update kat header (debit note)
                $dbacthdr_IV = DB::table('debtor.dbacthdr')
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'outamount' => $newoutamount_IV
                    ]);
            }
            
            // calculate total amount from alloc
            $totalAllocAmount = DB::table('debtor.dballoc')
                ->where('compcode','=',session('compcode'))
                ->where('docauditno','=',$dbacthdr->auditno)
                ->where('docsource','=','PB')
                ->where('doctrantype','=','CN')
                ->where('recstatus','=','POSTED')
                ->sum('amount');
            
            // calculate total amount from detail
            $totalDtlAmount = DB::table('debtor.dbactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','PB')
                    ->where('trantype','=','CN')
                    ->where('auditno','=',$dbacthdr->auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');
                
            $outamount_hdr = floatval($totalDtlAmount - $totalAllocAmount);
            
            // then update to header (credit note)
            DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
                ->update([
                    'outamount' => $outamount_hdr,
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

    public function del_alloc(Request $request){
        
        DB::beginTransaction();
        
        try {
                    
            $dballoc = DB::table('debtor.dballoc')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->where('lineno_','=',$request->lineno_)
                    ->where('source','=',$request->source)
                    ->where('trantype','=',$request->trantype)
                    ->where('auditno','=',$request->auditno)
                    ->first();
            
            $amount = floatval($dballoc->amount);
            $balance = floatval($dballoc->balance);
            $newoutamount_IV = floatval($amount + $balance);
            
            // update kat header (debit note)
            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$dballoc->refsource)
                ->where('trantype','=',$dballoc->reftrantype)
                ->where('auditno','=',$dballoc->refauditno)
                ->update([
                    'outamount' => $newoutamount_IV
                ]);
        
            // change status to CANCELLED
            DB::table('debtor.dballoc')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$request->source)
                ->where('trantype','=',$request->trantype)
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'recstatus' => 'CANCELLED',
                ]);
            
            $db_outamount = floatval($request->db_outamount);
            $outamount_hdr = floatval($db_outamount + $amount);
            
            // then update to header (credit note)
            DB::table('debtor.dbacthdr')
                ->where('source','=',$dballoc->docsource)
                ->where('trantype','=',$dballoc->doctrantype)
                ->where('auditno','=',$dballoc->docauditno)
                ->update([
                    'outamount' => $outamount_hdr,
                ]);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->outamount_hdr = $outamount_hdr;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function posted_single(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $dbacthdr = DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
                ->first();
                    
            if($dbacthdr->amount == 0){
                throw new \Exception('Credit Note auditno: '.$dbacthdr->auditno.' amount cant be zero', 500);
            }
            
            $yearperiod = defaultController::getyearperiod_($dbacthdr->entrydate);
            if($yearperiod->status == 'C'){
                throw new \Exception('Credit Note auditno: '.$dbacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
            }
            
            $this->gltran($request->idno);
            
            DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno)
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
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->result = 'success';
            
            return json_encode($responce);
            
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
                        'drcostcode' => $dept_obj->costcode,
                        'dracc' => $paymode_obj->glaccno,
                        'crcostcode' => $debtormast_obj->actdebccode,
                        'cracc' => $debtormast_obj->actdebglacc,
                        'amount' => $value->amount,
                        'postdate' => $dbacthdr_obj->entrydate,
                        'adduser' => $dbacthdr_obj->adduser,
                        'adddate' => $dbacthdr_obj->adddate,
                        'idno' => null
                    ]);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                $gltranAmount =  defaultController::isGltranExist_($dept_obj->costcode,$paymode_obj->glaccno,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$dept_obj->costcode)
                        ->where('glaccount','=',$paymode_obj->glaccno)
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
                            'costcode' => $dept_obj->costcode,
                            'glaccount' => $paymode_obj->glaccno,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($debtormast_obj->actdebccode,$debtormast_obj->actdebglacc,$yearperiod->year,$yearperiod->period);

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
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $debtormast_obj->actdebccode,
                            'glaccount' => $debtormast_obj->actdebglacc,
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
                $gltranAmount =  defaultController::isGltranExist_($dept_obj->costcode,$paymode_obj->glaccno,$yearperiod->year,$yearperiod->period);

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
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($debtormast_obj->actdebccode,$debtormast_obj->actdebglacc,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$debtormast_obj->actdebccode)
                        ->where('glaccount','=',$debtormast_obj->actdebglacc)
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
        
        $dbacthdr = DB::table('debtor.dbacthdr as h', 'debtor.debtormast as m', 'hisdb.pat_mast as p')
                    ->select('h.idno', 'h.compcode', 'h.trantype', 'h.auditno', 'h.lineno_', 'h.amount', 'h.outamount', 'h.recstatus', 'h.entrydate', 'h.entrytime', 'h.entryuser', 'h.reference', 'h.paymode', 'h.debtortype', 'h.debtorcode', 'h.remark', 'h.mrn', 'h.adduser', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4', 'p.MRN as pt_mrn', 'p.Episno as pt_episno', 'p.Name as pt_name')
                    ->leftJoin('debtor.debtormast as m', 'h.debtorcode', '=', 'm.debtorcode')
                    ->leftJoin('hisdb.pat_mast as p', 'h.mrn', '=', 'p.newmrn')
                    // ->leftJoin('debtor.debtormast as m', function($join) use ($request){
                    //             $join = $join->on('m.debtorcode', '=', 'h.debtorcode')
                    //                         ->on('m.compcode', '=', session('compcode'));
                    // })
                    // ->leftJoin('hisdb.pat_mast as p', function($join) use ($request){
                    //             $join = $join->on('p.MRN', '=', 'h.mrn')
                    //                         ->on('p.CompCode', '=', session('compcode'));
                    // })
                    ->where('h.compcode',session('compcode'))
                    ->where('h.source','=','PB')
                    ->where('h.trantype','=','CN')
                    ->where('h.auditno','=',$auditno)
                    ->first();
        // dd($dbacthdr);
        
        $dballoc = DB::table('debtor.dballoc as a', 'debtor.dbacthdr as h')
                    ->select('a.compcode', 'a.source', 'a.trantype', 'a.auditno', 'a.lineno_', 'a.docsource', 'a.doctrantype', 'a.docauditno', 'a.refsource', 'a.reftrantype', 'a.refauditno', 'a.refamount', 'a.mrn', 'a.episno', 'a.amount', 'a.outamount', 'a.debtortype', 'a.debtorcode', 'a.payercode', 'a.paymode', 'a.allocdate', 'a.remark', 'a.balance', 'a.adddate', 'a.adduser', 'a.recstatus', 'a.idno', 'h.entrydate as entrydate_hdr', 'p.Name as pt_name')
                    ->join('debtor.dbacthdr as h', function($join) use ($request){
                                $join = $join->on('a.docsource', '=', 'h.source')
                                            ->on('a.doctrantype', '=', 'h.trantype')
                                            ->on('a.docauditno', '=', 'h.auditno');
                    })->join('debtor.dbacthdr as h2', function($join){
                                $join = $join->on('a.refsource', '=', 'h2.source')
                                            ->on('a.reftrantype', '=', 'h2.trantype')
                                            ->on('a.refauditno', '=', 'h2.auditno');
                    })
                    ->leftJoin('hisdb.pat_mast as p', 'h2.mrn', '=', 'p.newmrn')
                    ->where('a.compcode',session('compcode'))
                    ->where('a.docsource','=','PB')
                    ->where('a.doctrantype','=','CN')
                    ->where('a.docauditno','=',$auditno)
                    ->where('a.recstatus','!=','CANCELLED')
                    ->get();
        // dd($dballoc);
        
        // $dballoc_dtl = DB::table('debtor.dballoc as a', 'debtor.dbacthdr as h')
        //             ->select('a.compcode', 'a.source', 'a.trantype', 'a.auditno', 'a.lineno_', 'a.docsource', 'a.doctrantype', 'a.docauditno', 'a.refsource', 'a.reftrantype', 'a.refauditno', 'a.refamount', 'h.mrn', 'a.episno', 'a.amount', 'a.outamount', 'a.debtortype', 'a.debtorcode', 'a.payercode', 'a.paymode', 'a.allocdate', 'a.remark', 'a.balance', 'a.adddate', 'a.adduser', 'a.recstatus', 'a.idno', 'h.entrydate as entrydate_hdr', 'p.Name as pt_name')
        //             ->join('debtor.dbacthdr as h', function($join) use ($request){
        //                         $join = $join->on('a.refsource', '=', 'h.source')
        //                                     ->on('a.reftrantype', '=', 'h.trantype')
        //                                     ->on('a.refauditno', '=', 'h.auditno');
        //             })
        //             ->leftJoin('hisdb.pat_mast as p', 'h.mrn', '=', 'p.newmrn')
        //             ->where('a.compcode',session('compcode'))
        //             ->where('a.docsource','=','PB')
        //             ->where('a.doctrantype','=','CN')
        //             ->where('a.docauditno','=',$auditno)
        //             ->where('a.recstatus','!=','CANCELLED')
        //             ->first();
        // dd($dballoc_dtl);
        
        if($dbacthdr->recstatus == "OPEN"){
            $title = "DRAFT";
        }else if($dbacthdr->recstatus == "POSTED"){
            $title = "CREDIT NOTE";
        }
       
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
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
        
        // $pdf = PDF::loadView('finance.AR.CreditNoteAR.CreditNoteAR_pdf',compact('dbacthdr','dballoc','dballoc_dtl','title','company','totamt_eng'));
        // return $pdf->stream();

        return view('finance.AR.CreditNoteAR.CreditNoteAR_pdfmake',compact('dbacthdr','dballoc','title','company','totamt_eng'));
        
        return view('finance.AR.CreditNoteAR.CreditNoteAR_pdf',compact('dbacthdr','dballoc','dballoc_dtl','title','company','totamt_eng'));
        
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

    public function check_alloc_exists(Request $request){
        
        $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('debtorcode',$request->db_debtorcode)
                    ->where('compcode',session('compcode'))
                    ->where('recstatus','!=','CANCELLED')
                    ->where('outamount','>',0)
                    ->where('source','PB')
                    ->where('entrydate','<=',$request->db_entrydate)
                    ->whereIn('trantype',['IN','DN']);
                    
        if(!$dbacthdr->exists()){
            throw new \Exception('This debtor doesnt have any invoice until date: '.Carbon::parse($request->db_entrydate)->format('d-m-Y'), 500);
        }
        
    }

}