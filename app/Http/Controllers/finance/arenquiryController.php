<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

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
            case 'populate_rc':
                return $this->populate_rc($request);
            case 'get_alloc':
                return $this->get_alloc($request);
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
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
                        'db.recptno AS db_recptno'
                    )
                    ->leftJoin('debtor.debtormast as dm', 'dm.debtorcode', '=', 'db.debtorcode')
                    ->where('db.compcode','=',session('compcode'))
                    ->where('db.source','=','PB');
                    // ->where('db.trantype','=','IN','DN',);

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

    public function populate_rc(Request $request){
            $table = DB::table('debtor.dbacthdr')
                            ->where('idno','=',$request->idno);

            $table = DB::table('debtor.dbacthdr')
                    ->select($this->fixPost($request->field,"_"))
                    ->leftjoin('hisdb.pat_mast', function($join) use ($request){
                        $join = $join->on('pat_mast.MRN', '=', 'dbacthdr.mrn')
                                    ->where('pat_mast.compcode','=',session('compcode'));
                    })->where('dbacthdr.idno','=',$request->idno);

            $responce = new stdClass();
            $responce->rows = $table->first();

            return json_encode($responce);
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function get_alloc(Request $request){

        $dbacthdr = DB::table('debtor.dbacthdr')
                        ->where('idno','=',$request->idno)
                        ->first();

        //if trantype = RC/RD/RF/CN
        if($dbacthdr->trantype == 'RC' || 'RD' || 'RF' || 'CN') {

            $table = DB::table('debtor.dballoc as dc')
                        ->select(
                            'dc.refsource as source',
                            'dc.reftrantype as trantype',
                            'dc.refauditno as auditno',
                            'dc.debtorcode',
                            'dc.payercode',
                            'dc.amount',
                            'dc.recptno',
                            'dc.paymode',
                            'dc.allocdate',
                            'dc.mrn',
                            'dc.episno',
                        )
                        ->join('debtor.dbacthdr as da', function($join) use ($request){
                                    $join = $join->on('dc.docsource', '=', 'da.source')
                                        ->on('dc.doctrantype', '=', 'da.trantype')
                                        ->on('dc.docauditno', '=', 'da.auditno');
                        })
                        ->where('dc.compcode','=',session('compcode'))
                        ->where('dc.docsource','=',$dbacthdr->source)
                        ->where('dc.doctrantype','=',$dbacthdr->trantype)
                        ->where('dc.docauditno','=',$dbacthdr->auditno);

                        // ->whereIn('dc.doctrantype',['RC','RD','RF','CN'])

            /////////////////paginate/////////////////
            $paginate = $table->paginate($request->rows);

            foreach ($paginate->items() as $key => $value) {
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

        }else if($dbacthdr->trantype == 'IN' || 'DN') {//if trantype = IN/DN

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
                        )
                        ->join('debtor.dbacthdr as da', function($join) use ($request){
                                    $join = $join->on('dc.refsource', '=', 'da.source')
                                        ->on('dc.reftrantype', '=', 'da.trantype')
                                        ->on('dc.refauditno', '=', 'da.auditno');
                        })
                        ->where('dc.compcode','=',session('compcode'))
                        ->where('dc.refsource','=',$dbacthdr->source)
                        ->where('dc.reftrantype','=',$dbacthdr->trantype)
                        ->where('dc.refauditno','=',$dbacthdr->auditno);

            /////////////////paginate/////////////////
            $paginate = $table->paginate($request->rows);

            foreach ($paginate->items() as $key => $value) {
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

}