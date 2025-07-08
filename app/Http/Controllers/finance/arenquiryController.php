<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\ARStatementListingExport;
use Maatwebsite\Excel\Facades\Excel;

class arenquiryController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.arenquiry.arenquiry');
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'tracking':
                return $this->tracking($request);
            case 'populate_rc':
                return $this->populate_rc($request);
            case 'populate_rf':
                return $this->populate_rf($request);
            case 'get_alloc':
                return $this->get_alloc($request);
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
            case 'get_debtorcode_outamount':
                return $this->get_debtorcode_outamount($request);
            case 'get_outamount':
                return $this->get_outamount($request);
            case 'cancel_allocate':
                return $this->cancel_allocate($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        switch($request->action){
            case 'add_Tracking':
                return $this->add_Tracking($request);
            case 'edit_Tracking':
                return $this->edit_Tracking($request);
            case 'edit_all_Tracking':
                return $this->edit_all_Tracking($request);
            case 'del_Tracking':
                return $this->del_Tracking($request);
            case 'cancel_allocate_form':
                return $this->cancel_allocate_form($request);
            default:
                return 'error happen..';
        }
        
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            case 'allocate':
                return $this->allocate($request);
            default:
                return 'error happen..';
        }
    }
    
    public function maintable(Request $request){
        
        $table = DB::table('debtor.dbacthdr AS db')
                ->select(
                    'db.compcode AS db_compcode',
                    'db.debtorcode AS db_debtorcode',
                    'db.payercode AS db_payercode',
                    'dm.name AS dm_name', 
                    'db.entrydate AS db_entrydate',
                    'db.auditno AS db_auditno', // search
                    'db.invno AS db_invno', // search
                    'db.recptno AS db_recptno', 
                    // 'db.recptno AS db_billno', 
                    'db.ponum AS db_ponum',
                    'db.amount AS db_amount',
                    'db.remark AS db_remark',
                    'db.lineno_ AS db_lineno_',
                    'db.orderno AS db_orderno',
                    'db.outamount AS db_outamount',
                    'db.debtortype AS db_debtortype',
                    'db.billdebtor AS db_billdebtor',
                    'db.approvedby AS db_approvedby',
                    'db.mrn AS db_mrn', // search
                    'db.episno AS db_episno',
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
                    'db.RCCASHbalance AS db_RCCASHbalance',
                    'db.RCFinalbalance AS db_RCFinalbalance',
                    'db.datesend AS db_datesend',
                )
                ->leftjoin('debtor.debtormast as dm', function ($join){
                    $join = $join->on('dm.debtorcode', '=', 'db.debtorcode')
                                ->where('dm.compcode','=',session('compcode'));
                })
                ->where('db.compcode','=',session('compcode'))
                // ->where('db.recstatus','=','POSTED')
                ->where('db.source','=','PB');
                // ->where('db.trantype','=','IN','DN',);
        
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->filterdate)){
            $table = $table->where('db.posteddate','>=',$request->filterdate[0]);
            $table = $table->where('db.posteddate','<=',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.invno','=',$request->wholeword);
                    });
            }else if($request->searchCol[0] == 'db_mrn'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.mrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('db.auditno','=',$request->wholeword);
                    });
            }else if($request->searchCol[0] == 'db_trantype'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.trantype','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_debtorcode'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.debtorcode','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'dm_name'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('dm.name','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_recptno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.recptno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_billno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.auditno','like',$request->searchVal[0]);
                })
                ->where('db.trantype','=','IN');
            }else{
                $table = $table->Where(function ($table) use ($request){
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                });
            }
        }
        
        if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces) == 1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach($pieces as $key => $value){
                    $value_ = substr_replace($value,"db.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('db.idno','DESC');
        }
        
        $paginate = $table->paginate($request->rows);
        
        foreach($paginate->items() as $key => $value){
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
                        ->where('compcode',session('compcode'));
                        // ->first();

                if($till->exists()){
                    $value->db_deptcode = $till->first()->dept;
                }else{
                    $value->db_deptcode = '';
                }
                
                // dd($till->dept);
            }

            switch($value->db_trantype){
                case 'IN':
                    $value->db_recptno = str_pad($value->db_invno, 8, "0", STR_PAD_LEFT);
                    break;
                case 'CN':
                    $value->db_recptno = 'CN-'.str_pad($value->db_auditno, 8, "0", STR_PAD_LEFT);
                    break;
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
    
    public function tracking(Request $request){
        
        $table = DB::table('debtor.billtrack')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$request->filterVal[1])
                ->where('trantype','=',$request->filterVal[2])
                ->where('auditno','=',$request->filterVal[3])
                ->where('lineno_','=',$request->filterVal[4])
                ->orderBy('recstatus','asc');
        
        $paginate = $table->paginate($request->rows);
        
        /////////////////paginate/////////////////
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);
        
        return json_encode($responce);        
    }
    
    public function populate_rc(Request $request){
        
        $table = DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno);
        
        $table = DB::table('debtor.dbacthdr')
                ->select($this->fixPost($request->field,"_"))
                ->leftjoin('hisdb.pat_mast', function ($join) use ($request){
                    $join = $join->on('pat_mast.MRN', '=', 'dbacthdr.mrn')
                                ->where('pat_mast.compcode','=',session('compcode'));
                })
                ->leftjoin('debtor.paymode as paycard', function ($join) use ($request){
                    $join = $join->on('paycard.paymode', '=', 'dbacthdr.paymode')
                                ->where('paycard.compcode','=',session('compcode'))
                                ->where('paycard.source','=','AR')
                                ->where('paycard.paytype','=','CARD');
                })
                ->leftjoin('debtor.paymode as paybank', function ($join) use ($request){
                    $join = $join->on('paybank.paymode', '=', 'dbacthdr.paymode')
                                ->where('paybank.compcode','=',session('compcode'))
                                ->where('paybank.source','=','AR')
                                ->where('paybank.paytype','=','BANK');
                })
                ->where('dbacthdr.idno','=',$request->idno);
        
        $responce = new stdClass();
        $responce->rows = $table->first();
        
        return json_encode($responce);        
    }
    
    public function populate_rf(Request $request){
        
        $table = DB::table('debtor.dbacthdr')
                ->where('idno','=',$request->idno);
        
        $table = DB::table('debtor.dbacthdr')
                ->select($this->fixPost($request->field,"_"))
                ->leftjoin('hisdb.pat_mast', function ($join) use ($request){
                    $join = $join->on('pat_mast.MRN', '=', 'dbacthdr.mrn')
                                ->where('pat_mast.compcode','=',session('compcode'));
                })
                ->leftjoin('debtor.paymode as paycard', function ($join) use ($request){
                    $join = $join->on('paycard.paymode', '=', 'dbacthdr.paymode')
                                ->where('paycard.compcode','=',session('compcode'))
                                ->where('paycard.source','=','AR')
                                ->where('paycard.paytype','=','CARD');
                })
                ->leftjoin('debtor.paymode as paybank', function ($join) use ($request){
                    $join = $join->on('paybank.paymode', '=', 'dbacthdr.paymode')
                                ->where('paybank.compcode','=',session('compcode'))
                                ->where('paybank.source','=','AR')
                                ->where('paybank.paytype','=','BANK');
                })
                ->where('dbacthdr.idno','=',$request->idno);
                // ->where('dbacthdr.trantype','RF');
        
        $responce = new stdClass();
        $responce->rows = $table->first();
        
        return json_encode($responce);        
    }
    
    public function cancel_allocate(Request $request){
        
       $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->first();
        
        // if trantype = RC/RD/RF/CN
        // if($dbacthdr->trantype == 'RC' || 'RD' || 'RF' || 'CN'){
        if($dbacthdr->trantype == 'RC' || $dbacthdr->trantype =='RD' || $dbacthdr->trantype =='RF' || $dbacthdr->trantype =='CN'){
            
            $table = DB::table('debtor.dballoc as dc')
                    ->select(
                        'dc.refsource as source',
                        'dc.reftrantype as trantype',
                        'dc.refauditno as auditno',
                        'dc.doctrantype',
                        'dc.debtorcode',
                        'dc.payercode',
                        'dc.amount',
                        'dc.recptno',
                        'dc.paymode',
                        'dc.allocdate',
                        'dc.mrn',
                        'dc.episno',
                        'dc.compcode',
                        'dc.lineno_',
                        'dc.idno',
                    )
                    ->join('debtor.dbacthdr as da', function ($join) use ($request){
                        $join = $join
                                    ->where('da.compcode', session('compcode'))
                                    ->on('dc.docsource', '=', 'da.source')
                                    ->on('dc.doctrantype', '=', 'da.trantype')
                                    ->on('dc.docauditno', '=', 'da.auditno');
                    })
                    ->where('dc.compcode','=',session('compcode'))
                    ->where('dc.docsource','=',$dbacthdr->source)
                    ->where('dc.doctrantype','=',$dbacthdr->trantype)
                    ->where('dc.docauditno','=',$dbacthdr->auditno)
                    ->where('dc.recstatus','=',"POSTED")
                    ->get();
                    // ->whereIn('dc.doctrantype',['RC','RD','RF','CN'])

            foreach($table as $key => $value){
                $auditno = str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                
                $value->sysAutoNo = $value->source.'-'.$value->trantype.'-'.$auditno;
            }

            $table2 = DB::table('debtor.dballoc as dc')
                    ->select(
                        'dc.docsource as source',
                        'dc.doctrantype as trantype',
                        'dc.docauditno as auditno',
                        'dc.doctrantype',
                        'dc.debtorcode',
                        'dc.payercode',
                        'dc.amount',
                        'dc.recptno',
                        'dc.paymode',
                        'dc.allocdate',
                        'dc.mrn',
                        'dc.episno',
                        'dc.compcode',
                        'dc.lineno_',
                        'dc.idno',
                    )
                    ->join('debtor.dbacthdr as da', function ($join) use ($request){
                        $join = $join
                                    ->where('da.compcode', session('compcode'))
                                    ->on('dc.refsource', '=', 'da.source')
                                    ->on('dc.reftrantype', '=', 'da.trantype')
                                    ->on('dc.refauditno', '=', 'da.auditno');
                    })
                    ->where('dc.compcode','=',session('compcode'))
                    ->where('dc.refsource','=',$dbacthdr->source)
                    ->where('dc.reftrantype','=',$dbacthdr->trantype)
                    ->where('dc.refauditno','=',$dbacthdr->auditno)
                    ->where('dc.reflineno','=',$dbacthdr->lineno_)
                    ->where('dc.recstatus','=',"POSTED")
                    ->get();

            foreach($table2 as $key => $value){
                $auditno = str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                
                $value->sysAutoNo = $value->source.'-'.$value->trantype.'-'.$auditno;
            }

            $table = $table->merge($table2);
            
            /////////////////paginate/////////////////
            // $paginate = $table->paginate($request->rows);

            // dd($table);
            
            $responce = new stdClass();
            $responce->page = 1;
            $responce->total = 1;
            $responce->records = $table->count();
            $responce->rows = $table;
            // $responce->sql = $table->toSql();
            // $responce->sql_bind = $table->getBindings();
            // $responce->sql_query = $this->getQueries($table);
            
            return json_encode($responce);
        }
    }
    
    public function get_alloc(Request $request){
        
        $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('idno','=',$request->idno)
                    ->first();
        
        // if trantype = RC/RD/RF/CN
        // if($dbacthdr->trantype == 'RC' || 'RD' || 'RF' || 'CN'){
        if($dbacthdr->trantype == 'RC' || $dbacthdr->trantype =='RD' || $dbacthdr->trantype =='RF' || $dbacthdr->trantype =='CN'){
            
            $table = DB::table('debtor.dballoc as dc')
                    ->select(
                        'dc.refsource as source',
                        'dc.reftrantype as trantype',
                        'dc.refauditno as auditno',
                        'dc.doctrantype',
                        'dc.debtorcode',
                        'dc.payercode',
                        'dc.amount',
                        'dc.recptno',
                        'dc.paymode',
                        'dc.allocdate',
                        'dc.mrn',
                        'dc.episno',
                        'dc.compcode',
                        'dc.lineno_',
                        'dc.idno',
                    )
                    ->join('debtor.dbacthdr as da', function ($join) use ($request){
                        $join = $join
                                    ->where('da.compcode', session('compcode'))
                                    ->on('dc.docsource', '=', 'da.source')
                                    ->on('dc.doctrantype', '=', 'da.trantype')
                                    ->on('dc.docauditno', '=', 'da.auditno');
                    })
                    ->where('dc.compcode','=',session('compcode'))
                    ->where('dc.docsource','=',$dbacthdr->source)
                    ->where('dc.doctrantype','=',$dbacthdr->trantype)
                    ->where('dc.docauditno','=',$dbacthdr->auditno)
                    ->where('dc.recstatus','=',"POSTED")
                    ->get();
                    // ->whereIn('dc.doctrantype',['RC','RD','RF','CN'])

            foreach($table as $key => $value){
                $auditno = str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                
                $value->sysAutoNo = $value->source.'-'.$value->trantype.'-'.$auditno;
            }

            $table2 = DB::table('debtor.dballoc as dc')
                    ->select(
                        'dc.docsource as source',
                        'dc.doctrantype as trantype',
                        'dc.docauditno as auditno',
                        'dc.doctrantype',
                        'dc.debtorcode',
                        'dc.payercode',
                        'dc.amount',
                        'dc.recptno',
                        'dc.paymode',
                        'dc.allocdate',
                        'dc.mrn',
                        'dc.episno',
                        'dc.compcode',
                        'dc.lineno_',
                        'dc.idno',
                    )
                    ->join('debtor.dbacthdr as da', function ($join) use ($request){
                        $join = $join
                                    ->where('da.compcode', session('compcode'))
                                    ->on('dc.refsource', '=', 'da.source')
                                    ->on('dc.reftrantype', '=', 'da.trantype')
                                    ->on('dc.refauditno', '=', 'da.auditno');
                    })
                    ->where('dc.compcode','=',session('compcode'))
                    ->where('dc.refsource','=',$dbacthdr->source)
                    ->where('dc.reftrantype','=',$dbacthdr->trantype)
                    ->where('dc.refauditno','=',$dbacthdr->auditno)
                    ->where('dc.reflineno','=',$dbacthdr->lineno_)
                    ->where('dc.recstatus','=',"POSTED")
                    ->get();

            foreach($table2 as $key => $value){
                $auditno = str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                
                $value->sysAutoNo = $value->source.'-'.$value->trantype.'-'.$auditno;
            }

            $table = $table->merge($table2);
            
            /////////////////paginate/////////////////
            // $paginate = $table->paginate($request->rows);

            // dd($table);
            
            $responce = new stdClass();
            $responce->page = 1;
            $responce->total = 1;
            $responce->records = $table->count();
            $responce->rows = $table;
            // $responce->sql = $table->toSql();
            // $responce->sql_bind = $table->getBindings();
            // $responce->sql_query = $this->getQueries($table);
            
            return json_encode($responce);
            
        }else{ // if trantype = IN/DN
            
            $table = DB::table('debtor.dballoc as dc')
                    ->select(
                        'dc.docsource as source',
                        'dc.doctrantype as trantype',
                        'dc.docauditno as auditno',
                        'dc.debtorcode',
                        'dc.payercode',
                        'dc.amount',
                        'dc.recptno',
                        'dc.paymode',
                        'dc.allocdate',
                        'dc.mrn',
                        'dc.episno',
                        'dc.compcode',
                        'dc.lineno_',
                        'dc.idno',
                    )
                    ->join('debtor.dbacthdr as da', function ($join) use ($request){
                        $join = $join
                                    ->where('da.compcode', session('compcode'))
                                    ->on('dc.refsource', '=', 'da.source')
                                    ->on('dc.reftrantype', '=', 'da.trantype')
                                    ->on('dc.refauditno', '=', 'da.auditno');
                    })
                    ->where('dc.compcode','=',session('compcode'))
                    ->where('dc.refsource','=',$dbacthdr->source)
                    ->where('dc.reftrantype','=',$dbacthdr->trantype)
                    ->where('dc.refauditno','=',$dbacthdr->auditno)
                    ->where('dc.recstatus','=',"POSTED");
            
            /////////////////paginate/////////////////
            $paginate = $table->paginate($request->rows);
            
            foreach($paginate->items() as $key => $value){
                $auditno = str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                
                $value->sysAutoNo = $value->source.'-'.$value->trantype.'-'.$auditno;
            }
            
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
    }
    
    public function get_table_dtl(Request $request){
        
        $table = DB::table('debtor.dbactdtl')
                ->where('source','=','PB')
                ->where('trantype','=','CN')
                ->where('auditno','=',$request->auditno)
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','<>','DELETE')
                ->orderBy('idno','desc');
        
        /////////////////paginate/////////////////
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
    
    public function get_debtorcode_outamount(Request $request){
        
        $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode',session('compcode'))
                    ->where('payercode',$request->payercode)
                    ->where('source','PB')
                    ->where('recstatus','POSTED')
                    ->where('outamount','>',0)
                    ->whereIn('trantype',['DN','IN']);
        
        $responce = new stdClass();
        
        if($dbacthdr->exists()){
            $responce->result = 'true';
            $responce->outamount = $dbacthdr->sum('dbacthdr.outamount');
        }else{
            $responce->result = 'false';
        }
        
        return json_encode($responce); 
    }
    
    public function get_outamount(Request $request){
        
        $calc_outamount = DB::table('debtor.dbacthdr')
                        ->where('compcode', '=', session('compcode'))
                        ->whereIn('recstatus', ['POSTED','ACTIVE'])
                        ->where('debtorcode', '=', $request->debtorcode);
                        // ->whereDate('posteddate', '<=', Carbon::now("Asia/Kuala_Lumpur"));
        
        $outamount = $this->calc_openbal($calc_outamount);
        $responce = new stdClass();
        $responce->outamount = $outamount;
        
        return json_encode($responce);
    }
    
    public function add_Tracking(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            DB::table('debtor.billtrack')
                ->insert([
                    'compcode' => session('compcode'),
                    'source' => $request->source,
                    'trantype' => $request->trantype,
                    'auditno' => $request->auditno,
                    'lineno_' => $request->lineno_,
                    'trxcode' => $request->trxcode,
                    'trxdate' => $this->turn_date($request->trxdate),
                    'comment_' => $request->comment_,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            if($request->trxcode == 'Send to Debtor'){
                
                DB::table('debtor.dbacthdr')
                    ->where('source','=',$request->source)
                    ->where('trantype','=',$request->trantype)
                    ->where('auditno','=',$request->auditno)
                    ->where('lineno_','=',$request->lineno_)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'datesend' => $this->turn_date($request->trxdate),
                    ]);
                
            }
            
            DB::commit();
            
        }catch(\Exception $e){
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    }
    
    public function edit_Tracking(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            DB::table('debtor.billtrack')
                ->where('idno','=',$request->idno)
                ->update([
                    // 'trxcode' => $request->trxcode,
                    'trxdate' => $this->turn_date($request->trxdate),
                    'comment_' => $request->comment_,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastcomputerid' => session('computerid'),
                ]);
            
            if($request->trxcode == 'Send to Debtor'){
                
                DB::table('debtor.dbacthdr')
                    ->where('source','=',$request->source)
                    ->where('trantype','=',$request->trantype)
                    ->where('auditno','=',$request->auditno)
                    ->where('lineno_','=',$request->lineno_)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'datesend' => $this->turn_date($request->trxdate),
                    ]);
                
            }
            
            DB::commit();
            
        }catch(\Exception $e){
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    }
    
    public function edit_all_Tracking(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            foreach($request->dataobj as $key => $value){
                
                // 1. update detail
                DB::table('debtor.billtrack')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'trxdate' => $this->turn_date($value['trxdate']),
                        'comment_' => $value['comment_'],
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastcomputerid' => session('computerid'),
                    ]);
                
                if($value['trxcode'] == 'Send to Debtor'){
                    
                    DB::table('debtor.dbacthdr')
                        ->where('source','=',$request->source)
                        ->where('trantype','=',$request->trantype)
                        ->where('auditno','=',$request->auditno)
                        ->where('lineno_','=',$request->lineno_)
                        ->where('compcode','=',session('compcode'))
                        ->update([
                            'datesend' => $this->turn_date($value['trxdate']),
                        ]);
                    
                }
                
            }
            
            DB::commit();
            
        }catch(\Exception $e){
            
            DB::rollback();
            
            return response('Error'.$e, 500);
            
        }
    }
    
    public function del_Tracking(Request $request){
        
        // DB::table('debtor.billtrack')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('idno','=',$request->idno)
        //     ->delete();
        
        DB::table('debtor.billtrack')
            ->where('idno','=',$request->idno)
            ->update([
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'lastcomputerid' => session('computerid')
            ]);
        
        if($request->trxcode == 'Send to Debtor'){
            
            DB::table('debtor.dbacthdr')
                ->where('source','=',$request->source)
                ->where('trantype','=',$request->trantype)
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'datesend' => null,
                ]);
            
        }        
    }

    public function cancel_allocate_form(Request $request){

        DB::beginTransaction();
        
        try{
            
            $receipt = DB::table('debtor.dbacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('source','PB')
                        ->where('idno',$request->idno);
                        // ->where('trantype',$request->trantype)
                        // ->where('payercode',$request->payercode)
                        // ->where('auditno',$request->auditno);
            
            if($receipt->exists()){
                $receipt_first = $receipt->first();
            }else{
                throw new \Exception("Error no receipt");
            }
            
            $outamt_bal = 0;
            foreach ($request->allo as $key => $value) {
                $dballoc = DB::table('debtor.dballoc')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$value['obj']['idno']);
                
                if($dballoc->exists()){
                    $dballoc_first = $dballoc->first();
                    
                    $dballoc->update([
                        'recstatus' => 'CANCELLED'
                    ]);
                    
                    $outamt_bal+=floatval($dballoc_first->amount);

                    $dbacthdr_rev1 = DB::table('debtor.dbacthdr')
                                    ->where('compcode',session('compcode'))
                                    ->where('source',$dballoc_first->refsource)
                                    ->where('trantype',$dballoc_first->reftrantype)
                                    ->where('auditno',$dballoc_first->refauditno)
                                    ->first();

                    DB::table('debtor.dbacthdr')
                                    ->where('compcode',session('compcode'))
                                    ->where('source',$dballoc_first->refsource)
                                    ->where('trantype',$dballoc_first->reftrantype)
                                    ->where('auditno',$dballoc_first->refauditno)
                                    ->update([
                                        'outamount' => $dbacthdr_rev1->outamount + $dballoc_first->amount
                                    ]);


                    $dbacthdr_rev2 = DB::table('debtor.dbacthdr')
                                    ->where('compcode',session('compcode'))
                                    ->where('source',$dballoc_first->docsource)
                                    ->where('trantype',$dballoc_first->doctrantype)
                                    ->where('auditno',$dballoc_first->docauditno)
                                    ->first();

                    DB::table('debtor.dbacthdr')
                                    ->where('compcode',session('compcode'))
                                    ->where('source',$dballoc_first->docsource)
                                    ->where('trantype',$dballoc_first->doctrantype)
                                    ->where('auditno',$dballoc_first->docauditno)  
                                    ->update([
                                        'outamount' => $dbacthdr_rev2->outamount + $dballoc_first->amount
                                    ]);

                }else{
                    throw new \Exception("Error no dballoc");
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage().$e, 500);
            
        }
    }
    
    public function allocate(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            $receipt = DB::table('debtor.dbacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('source','PB')
                        ->where('trantype',$request->trantype)
                        ->where('payercode',$request->payercode)
                        ->where('auditno',$request->auditno);
            
            if($receipt->exists()){
                $receipt_first = $receipt->first();
            }else{
                throw new \Exception("Error no receipt");
            }
            
            $amt_paid = 0;
            foreach($request->allo as $key => $value){
                $invoice = DB::table('debtor.dbacthdr')
                            // ->where('compcode',session('compcode'))
                            // ->where('source','PB')
                            // ->whereIn('trantype',['IN','DN'])
                            // ->where('debtorcode',$request->debtorcode)
                            // ->where('auditno',$value['obj']['auditno'])
                            // ->where('outamount','>',0);
                            ->where('idno',$value['obj']['idno']);
                
                if($invoice->exists()){
                    $invoice_first = $invoice->first();
                    
                    $invoice->update([
                        'outamount' => $value['obj']['amtbal']
                    ]);
                    
                    $amt_paid+=floatval($value['obj']['amtpaid']);
                }else{
                    throw new \Exception("Error no Invoice");
                }
                
                $auditno = $this->defaultSysparam('AR','AL');
                
                DB::table('debtor.dballoc')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => 'AR',
                        'trantype' => 'AL',
                        'auditno' => $auditno,
                        'lineno_' => intval($key)+1,
                        'docsource' => $receipt_first->source,
                        'doctrantype' => $receipt_first->trantype,
                        'docauditno' => $receipt_first->auditno,
                        'refsource' => $invoice_first->source,
                        'reftrantype' => $invoice_first->trantype,
                        'refauditno' => $invoice_first->auditno,
                        'refamount' => $invoice_first->amount,
                        'reflineno' => $invoice_first->lineno_,
                        'recptno' => $receipt_first->recptno,
                        'mrn' => $receipt_first->mrn,
                        'episno' => $receipt_first->episno,
                        'allocsts' => 'ACTIVE',
                        'amount' => floatval($value['obj']['amtpaid']),
                        'tillcode' => $receipt_first->tillcode,
                        'debtortype' => $this->get_debtortype($invoice_first->payercode),
                        'debtorcode' => $invoice_first->payercode,
                        'payercode' => $receipt_first->payercode,
                        'paymode' => $receipt_first->paymode,
                        'allocdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'remark' => 'Allocation '.$receipt_first->source,
                        'balance' => $value['obj']['amtbal'],
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'adduser' => session('username'),
                        'recstatus' => 'POSTED'
                    ]);
            }
            
            if($amt_paid > 0){
                $receipt = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->where('trantype',$request->trantype)
                            ->where('payercode',$request->payercode)
                            ->where('auditno',$request->auditno);
                
                if($receipt->exists()){
                    $receipt_first = $receipt->first();
                    
                    $out_amt = floatval($receipt_first->outamount) - floatval($amt_paid);
                    
                    $receipt->update([
                        'outamount' => $out_amt
                    ]);
                }
            }
            
            DB::commit();
            
        }catch(\Exception $e){
            
            DB::rollback();
            
            return response($e->getMessage().$e, 500);
            
        }
        
    }
    
    public function showExcel(Request $request){
        return Excel::download(new ARStatementListingExport($request->debtorcode_from,$request->debtorcode_to,$request->datefr,$request->dateto), 'ARStatementListingExport.xlsx');
    }
    
    public function showpdf_xxx(Request $request){
        
        // $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $debtorcode_from = $request->debtorcode_from;
        if(empty($request->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $request->debtorcode_to;
        
        $debtormast = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.debtorcode', 'dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4')
                    ->leftJoin('debtor.debtormast as dm', function ($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode', '=', session('compcode'))
                    ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                    ->whereBetween('dh.debtorcode',[$debtorcode_from,$debtorcode_to.'%'])
                    ->whereDate('dh.posteddate', '<=', $dateto)
                    ->orderBy('dm.debtorcode', 'ASC')
                    ->distinct('dm.debtorcode');
        
        $debtormast = $debtormast->get(['dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4']);
        
        $array_report = [];
        foreach($debtormast as $key => $value){
            $dbacthdr = DB::table('debtor.dbacthdr as dh')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'pm.Name', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dh.datesend')
                        ->leftJoin('hisdb.pat_mast as pm', function ($join){
                            $join = $join->on('pm.NewMRN', '=', 'dh.mrn')
                                        ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dh.compcode', '=', session('compcode'))
                        ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                        ->where('debtorcode',$value->debtorcode)
                        ->whereDate('dh.posteddate', '<=', $dateto)
                        ->orderBy('dh.posteddate', 'ASC')
                        ->get();
            
            // $calc_openbal = DB::table('debtor.dbacthdr as dh')
            //                 ->where('dh.compcode', '=', session('compcode'))
            //                 ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
            //                 ->where('dh.debtorcode', '=', $value->debtorcode)
            //                 ->whereDate('dh.posteddate', '<', $datefr);
            
            // $openbal = $this->calc_openbal($calc_openbal);
            // $value->openbal = $openbal;
            
            // $value->datesend = '';
            $value->reference = '';
            $value->amount_dr = 0;
            $value->amount_cr = 0;
            $balance = 0;
            foreach($dbacthdr as $key => $value){
                switch($value->trantype){
                    case 'IN':
                        // $value->datesend = $value->datesend;
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->reference = $value->remark;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'DN':
                        // $value->reference = $value->remark;
                        $value->reference = $value->reference;
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'BC':
                        // $value->reference
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RF':
                        if($value->mrn == '0' || $value->mrn == ''){
                            // $value->reference = $value->remark;
                            $value->reference = $value->reference;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'CN':
                        // $value->reference = $value->remark;
                        $value->reference = $value->reference;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RC':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RD':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RT':
                        // $value->reference
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    default:
                        // code...
                        break;
                }
            }
        }
        
        // dd($array_report);
        
        $title = "STATEMENT LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }
        
        return view('finance.AR.arenquiry.ARStatementListingExport_pdfmake_lama', compact('debtormast','array_report','title','company'));        
    }

    public function showpdf(Request $request){
        
        // $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        // $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        
        $date = Carbon::parse($request->dateto)->format('Y-m-d');
        // $debtortype = $this->debtortype;
        $debtorcode_from = $request->debtorcode_from;
        if(empty($request->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $request->debtorcode_to;
        // $grouping = [0,30,60,90,120];

        $debtormast = DB::table('debtor.debtormast as dm')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'pm.Name as pm_name','dm.debtortype','dm.name','dm.address1','dm.address2','dm.address3','dm.address4', 'dh.datesend')
                        // ->join('debtor.debtortype as dt', function($join){
                        //     $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                        //                  ->where('dt.compcode', '=', session('compcode'));
                        // })
                        ->join('debtor.dbacthdr as dh', function($join) use ($date){
                            $join = $join->on('dh.debtorcode', '=', 'dm.debtorcode')
                                         ->whereDate('dh.posteddate', '<=', $date)
                                         ->where('dh.compcode', '=', session('compcode'));
                        })->leftJoin('hisdb.pat_mast as pm', function($join){
                            $join = $join->on('pm.NewMrn', '=', 'dh.mrn')
                                         ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dm.compcode', '=', session('compcode'))
                        ->whereBetween('dm.debtorcode', [$debtorcode_from,$debtorcode_to.'%'])
                        ->orderBy('dm.debtorcode', 'ASC')
                        // ->limit(3000)
                        ->get();

        $array_report = [];

        foreach ($debtormast as $key => $value){
            $value->remark = '';
            $value->doc_no = '';
            $value->newamt = 0;

            $hdr_amount = $value->amount;
            
            // to calculate interval (days)
            // $datetime1 = new DateTime($date);
            // $datetime2 = new DateTime($value->posteddate);
            
            // $interval = $datetime1->diff($datetime2);
            // $days = $interval->format('%a');
            // $value->group = $this->assign_grouping($grouping,$days);
            // $value->days = $days;
            
            if($value->trantype == 'IN' || $value->trantype =='DN') {
                $alloc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->where('da.reflineno', '=', $value->lineno_)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $newamt = $hdr_amount - $alloc_sum;
            }else{
                $doc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.docsource', '=', $value->source)
                        ->where('da.doctrantype', '=', $value->trantype)
                        ->where('da.docauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $ref_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $newamt = -($hdr_amount - $doc_sum - $ref_sum);
            }
            
            switch ($value->trantype) {
                case 'IN':
                    if($value->mrn == '0' || $value->mrn == ''){
                        $value->reference = $value->remark;
                    }else{
                        $value->reference = $value->pm_name;
                    }
                    $value->doc_no = $value->trantype.'/'.str_pad($value->invno, 5, "0", STR_PAD_LEFT);
                    $value->amount_dr = $value->amount;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'DN':
                    $value->reference = $value->reference;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'BC':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RF':
                    if($value->mrn == '0' || $value->mrn == ''){
                        // $value->reference = $value->remark;
                        $value->reference = $value->reference;
                    }else{
                        $value->reference = $value->pm_name;
                    }
                    $value->doc_no = $value->recptno;
                    $value->amount_dr = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'CN':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->amount_cr = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RC':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->amount_cr = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RD':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->amount_cr = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RT':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->amount_cr = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                default:
                    // code...
                    break;
            }
        }

        $title = "STATEMENT LISTING";
        $array_report_dm = collect($array_report)->unique('debtorcode');
        $debtormast = collect($debtormast)->unique('debtorcode');

        foreach ($debtormast as $db_obj) {
            $gotdm = $array_report_dm->where('debtorcode',$db_obj->debtorcode);

            if($gotdm->count() == 0){
            
                $db_obj->posteddate = '';
                $db_obj->datesend = '';
                $db_obj->pm_name = '';
                $db_obj->auditno = '';
                $db_obj->amount_dr = 0;
                $db_obj->amount_cr = 0;
                $db_obj->unit = '';

                array_push($array_report,$db_obj);
            }
        }

        // $comp_name = $this->comp->name;
        $date_at = Carbon::createFromFormat('Y-m-d',$date)->format('d-m-Y');
        
        return view('finance.AR.arenquiry.ARStatementListingExport_pdfmake', compact('debtormast','array_report','title'));        
    }

    public function assign_grouping($grouping,$days){
        $group = 0;

        foreach ($grouping as $key => $value) {
            if(!empty($value) && $days >= intval($value)){
                $group = $key;
            }
        }

        return $group;
    }
    
    public function calc_openbal($obj){
        
        $balance = 0;
        
        foreach($obj->get() as $key => $value){
            switch($value->trantype){
                case 'IN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'BC':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'RF':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RC':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RD':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RT':
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }
        
        return $balance;
    }
    
}