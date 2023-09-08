<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class CancellationController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.cancellation.cancellation');
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'get_jqGrid_rc':
                return $this->get_jqGrid_rc($request);
            case 'get_jqGrid_rd':
                return $this->get_jqGrid_rd($request);
            case 'get_jqGrid_rf':
                return $this->get_jqGrid_rf($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->oper){
            case 'cancel_alloc':
                return $this->cancel_alloc($request);
            default:
                return 'error happen..';
        }
    }
    
    public function get_jqGrid_rc(Request $request){
        
        $table = DB::table('debtor.dbacthdr AS db')
                ->select(
                    'db.compcode AS db_compcode',
                    'db.debtorcode AS db_debtorcode',
                    'db.payercode AS db_payercode',
                    'db.payername AS db_payername',
                    'dm.name AS dm_name', 
                    'db.entrydate AS db_entrydate',
                    'db.auditno AS db_auditno', //search
                    'db.invno AS db_invno', //search
                    'db.recptno AS db_recptno',
                    'db.ponum AS db_ponum',
                    'db.amount AS db_amount',
                    'db.remark AS db_remark',
                    'db.lineno_ AS db_lineno_',
                    'db.orderno AS db_orderno',
                    'db.outamount AS db_outamount',
                    'db.debtortype AS db_debtortype',
                    'db.billdebtor AS db_billdebtor',
                    'db.approvedby AS db_approvedby',
                    'db.mrn AS db_mrn', //search
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
                    'db.upddate AS db_upddate',
                    'db.currency AS db_currency',
                    'db.PymtDescription AS db_PymtDescription',
                    'db.paytype AS db_paytype',
                    'db.tillcode AS db_tillcode',
                    'db.tillno AS db_tillno',
                    'db.recptno AS db_recptno',
                    'db.paymode AS db_paymode',
                    'db.unallocated AS db_unallocated',
                )
                ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                ->where('db.compcode','=',session('compcode'))
                ->where('db.source','=','PB')
                ->where('db.trantype','=','RC');
        
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->filterdate)){
            $table = $table->where('db.entrydate','>=',$request->filterdate[0]);
            $table = $table->where('db.entrydate','<=',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.invno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_mrn'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.mrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.auditno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_trantype'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.trantype','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_debtorcode'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.debtorcode','like',$request->searchVal[0]);
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
            
            if($value->db_trantype == 'RC'){
                $till = DB::table('debtor.till')
                        ->where('tillcode','=',$value->db_tillcode)
                        ->where('compcode',session('compcode'))
                        ->first();
                
                // dd($till->dept);
                $value->db_deptcode = $till->dept;
            }
        }
        
        /////////////////paginate/////////////////
        
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
    
    public function get_jqGrid_rd(Request $request){
        
        $table = DB::table('debtor.dbacthdr AS db')
                ->select(
                    'db.compcode AS db_compcode',
                    'db.debtorcode AS db_debtorcode',
                    'db.payercode AS db_payercode',
                    'db.payername AS db_payername',
                    'dm.name AS dm_name', 
                    'db.entrydate AS db_entrydate',
                    'db.auditno AS db_auditno', //search
                    'db.invno AS db_invno', //search
                    'db.recptno AS db_recptno',
                    'db.ponum AS db_ponum',
                    'db.amount AS db_amount',
                    'db.remark AS db_remark',
                    'db.lineno_ AS db_lineno_',
                    'db.orderno AS db_orderno',
                    'db.outamount AS db_outamount',
                    'db.debtortype AS db_debtortype',
                    'db.billdebtor AS db_billdebtor',
                    'db.approvedby AS db_approvedby',
                    'db.mrn AS db_mrn', //search
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
                    'db.upddate AS db_upddate',
                    'db.currency AS db_currency',
                    'db.PymtDescription AS db_PymtDescription',
                    'db.paytype AS db_paytype',
                    'db.tillcode AS db_tillcode',
                    'db.tillno AS db_tillno',
                    'db.recptno AS db_recptno',
                    'db.paymode AS db_paymode',
                    'db.unallocated AS db_unallocated',
                )
                ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                ->where('db.compcode','=',session('compcode'))
                ->where('db.source','=','PB')
                ->where('db.trantype','=','RD');
        
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->filterdate)){
            $table = $table->where('db.entrydate','>=',$request->filterdate[0]);
            $table = $table->where('db.entrydate','<=',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.invno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_mrn'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.mrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.auditno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_trantype'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.trantype','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_debtorcode'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.debtorcode','like',$request->searchVal[0]);
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
            
            if($value->db_trantype == 'RD'){
                $till = DB::table('debtor.till')
                        ->where('tillcode','=',$value->db_tillcode)
                        ->where('compcode',session('compcode'))
                        ->first();
                
                // dd($till->dept);
                $value->db_deptcode = $till->dept;
            }
        }
        
        /////////////////paginate/////////////////
        
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
    
    public function get_jqGrid_rf(Request $request){
        
        $table = DB::table('debtor.dbacthdr AS db')
                ->select(
                    'db.compcode AS db_compcode',
                    'db.debtorcode AS db_debtorcode',
                    'db.payercode AS db_payercode',
                    'db.payername AS db_payername',
                    'dm.name AS dm_name', 
                    'db.entrydate AS db_entrydate',
                    'db.auditno AS db_auditno', //search
                    'db.invno AS db_invno', //search
                    'db.recptno AS db_recptno',
                    'db.ponum AS db_ponum',
                    'db.amount AS db_amount',
                    'db.remark AS db_remark',
                    'db.lineno_ AS db_lineno_',
                    'db.orderno AS db_orderno',
                    'db.outamount AS db_outamount',
                    'db.debtortype AS db_debtortype',
                    'db.billdebtor AS db_billdebtor',
                    'db.approvedby AS db_approvedby',
                    'db.mrn AS db_mrn', //search
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
                    'db.upddate AS db_upddate',
                    'db.currency AS db_currency',
                    'db.PymtDescription AS db_PymtDescription',
                    'db.paytype AS db_paytype',
                    'db.tillcode AS db_tillcode',
                    'db.tillno AS db_tillno',
                    'db.recptno AS db_recptno',
                    'db.paymode AS db_paymode',
                    'db.unallocated AS db_unallocated',
                )
                ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                ->where('db.compcode','=',session('compcode'))
                ->where('db.source','=','PB')
                ->where('db.trantype','=','RF');
        
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->filterdate)){
            $table = $table->where('db.entrydate','>=',$request->filterdate[0]);
            $table = $table->where('db.entrydate','<=',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.invno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_mrn'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.mrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.auditno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_trantype'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.trantype','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_debtorcode'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.debtorcode','like',$request->searchVal[0]);
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
            // $dbactdtl = DB::table('debtor.dbactdtl')
            //             ->where('source','=',$value->db_source)
            //             ->where('trantype','=',$value->db_trantype)
            //             ->where('auditno','=',$value->db_auditno);
            
            // if($dbactdtl->exists()){
            //     $value->dbactdtl_outamt = $dbactdtl->sum('amount');
            // }else{
            //     $value->dbactdtl_outamt = $value->db_outamount;
            // }
            
            $dballoc = DB::table('debtor.dballoc')
                        ->where('docsource','=',$value->db_source)
                        ->where('doctrantype','=',$value->db_trantype)
                        ->where('docauditno','=',$value->db_auditno);
            
            if($dballoc->exists()){
                $value->unallocated = false;
            }else{
                $value->unallocated = true;
            }
            
            if($value->db_trantype == 'RF'){
                $till = DB::table('debtor.till')
                        ->where('tillcode','=',$value->db_tillcode)
                        ->where('compcode',session('compcode'))
                        ->first();
                
                // dd($till->dept);
                $value->db_deptcode = $till->dept;
            }
        }
        
        /////////////////paginate/////////////////
        
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
    
    public function cancel_alloc(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $dballoc = DB::table('debtor.dballoc')
                        ->where('idno','=',$request->idno)
                        ->first();
            
            $alloc_amt = floatval($dballoc->amount);
            
            $hdr_doc = DB::table('debtor.dbacthdr')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$dballoc->docsource)
                        ->where('trantype','=',$dballoc->doctrantype)
                        ->where('auditno','=',$dballoc->docauditno)
                        ->first();
            
            $doc_outamt = floatval($hdr_doc->outamount);
            $doc_newoutamt = floatval($doc_outamt + $alloc_amt);
            
            $hdr_ref = DB::table('debtor.dbacthdr')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$dballoc->refsource)
                        ->where('trantype','=',$dballoc->reftrantype)
                        ->where('auditno','=',$dballoc->refauditno)
                        ->first();
            
            $ref_outamt = floatval($hdr_ref->outamount);
            $ref_newoutamt = floatval($ref_outamt + $alloc_amt);
            // dd($ref_newoutamt);
            
            DB::table('debtor.dballoc')
                ->where('idno','=',$request->idno)
                ->update([
                    'recstatus' => 'CANCELLED'
                ]);
            
            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$dballoc->docsource)
                ->where('trantype','=',$dballoc->doctrantype)
                ->where('auditno','=',$dballoc->docauditno)
                ->update([
                    'outamount' => $doc_newoutamt
                ]);
            
            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$dballoc->refsource)
                ->where('trantype','=',$dballoc->reftrantype)
                ->where('auditno','=',$dballoc->refauditno)
                ->update([
                    'outamount' => $ref_newoutamt
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

}