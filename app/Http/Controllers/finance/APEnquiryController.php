<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class APEnquiryController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.apenquiry.apenquiry');
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_alloc_detail':
                    return $this->get_alloc_detail($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){

        $table = DB::table('finance.apacthdr AS ap')
                    ->select(
                        'ap.compcode AS apacthdr_compcode',
                        'ap.auditno AS apacthdr_auditno', //search
                        'ap.trantype AS apacthdr_trantype', //search
                        // 'ap.trantype2 AS apacthdr_trantype2', 
                        'ap.doctype AS apacthdr_doctype',
                        'ap.suppcode AS apacthdr_suppcode', 
                        'su.name AS supplier_name', 
                        'ap.actdate AS apacthdr_actdate',
                        'ap.document AS apacthdr_document', //search
                        'ap.cheqno AS apacthdr_cheqno', //search
                        'ap.deptcode AS apacthdr_deptcode',
                        'ap.amount AS apacthdr_amount',
                        'ap.outamount AS apacthdr_outamount',
                        'ap.recstatus AS apacthdr_recstatus',
                        'ap.payto AS apacthdr_payto',
                        'ap.recdate AS apacthdr_recdate',
                        'ap.category AS apacthdr_category',
                        'ap.remarks AS apacthdr_remarks',
                        'ap.adduser AS apacthdr_adduser',
                        'ap.adddate AS apacthdr_adddate',
                        'ap.upduser AS apacthdr_upduser',
                        'ap.upddate AS apacthdr_upddate',
                        'ap.source AS apacthdr_source',
                        'ap.idno AS apacthdr_idno',
                        'ap.unit AS apacthdr_unit',
                        'ap.pvno AS apacthdr_pvno', //search
                        'ap.paymode AS apacthdr_paymode',
                        'ap.bankcode AS apacthdr_bankcode'
                        
                    )
                    ->leftJoin('material.supplier as su', 'su.SuppCode', '=', 'ap.suppcode')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.source','=','AP');
                    
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->filterdate)){
            $table = $table->where('ap.actdate','>',$request->filterdate[0]);
            $table = $table->where('ap.actdate','<',$request->filterdate[1]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'apacthdr_document'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.document','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'apacthdr_cheqno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.cheqno','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'apacthdr_auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.auditno','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'apacthdr_trantype'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.trantype','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'apacthdr_pvno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.pvno','like',$request->searchVal[0]);
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
                    $value_ = substr_replace($value,"ap.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('ap.idno','DESC');
        }


       $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            $apactdtl = DB::table('finance.apactdtl')
                        ->where('source','=',$value->apacthdr_source)
                        ->where('trantype','=',$value->apacthdr_trantype)
                        ->where('auditno','=',$value->apacthdr_auditno);

            if($apactdtl->exists()){
                $value->apactdtl_outamt = $apactdtl->sum('amount');
            }else{
                $value->apactdtl_outamt = $value->apacthdr_outamount;
            }

            $apalloc = DB::table('finance.apalloc')
                        ->where('refsource','=',$value->apacthdr_source)
                        ->where('reftrantype','=',$value->apacthdr_trantype)
                        ->where('refauditno','=',$value->apacthdr_auditno);

            if($apalloc->exists()){
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

    public function get_alloc_detail(Request $request){

        //trantype (PV, CN, PD)
        if(($request->apacthdr_trantype == 'PV' || 'PD') || ($request->unallocated == 'false')) {

            $invoice = DB::table('finance.apacthdr')
                            ->where('idno','=',$request->idno)
                            ->first();

            $table = DB::table('finance.apalloc as al')
                        ->select(
                            'ap.auditno',
                            'ap.trantype',
                            'ap.suppcode',
                            'ap.actdate',
                            'ap.document',
                            'ap.recstatus',
                            'ap.bankcode',
                            'ap.recdate',
                            'ap.amount',
                            'ap.pvno',
                            'al.allocamount',
                            'al.outamount',
                            'al.recstatus',
                            'al.bankcode',
                            'al.allocdate'
                        )
                        ->join('finance.apacthdr as ap', function($join) use ($request){
                                    $join = $join->on('al.docsource', '=', 'ap.source')
                                        ->on('al.doctrantype', '=', 'ap.trantype')
                                        ->on('al.docauditno', '=', 'ap.auditno');
                        })
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.refsource','=',$invoice->source)
                        ->where('al.reftrantype','=',$invoice->trantype)
                        ->where('al.refauditno','=',$invoice->auditno)
                        ->where('al.bankcode','=',$invoice->bankcode)
                        ->where('ap.pvno','=',$invoice->pvno)
                        ->where('al.recstatus','=','POSTED')
                        ->orderBy('al.idno','DESC');

        } //trantype (IN, DN)
        
            else  { //if (($request->apacthdr_trantype == 'DN' || 'IN') || ($request->unallocated == 'true'))

            $invoice = DB::table('finance.apacthdr')
                ->where('idno','=',$request->idno)
                ->first();

            $table = DB::table('finance.apalloc as al')
                        ->select(
                            'ap.auditno',
                            'ap.trantype',
                            'ap.suppcode',
                            'ap.actdate',
                            'ap.document',
                            'ap.recstatus',
                            'ap.recdate',
                            'ap.amount',
                            'al.allocamount',
                            'al.outamount',
                            'al.recstatus',
                            'al.bankcode',
                            'al.allocdate',
                           
                        )
                        ->join('finance.apacthdr as ap', function($join) use ($request){
                                    $join = $join->on('al.refsource', '=', 'ap.source')
                                        ->on('al.reftrantype', '=', 'ap.trantype')
                                        ->on('al.refauditno', '=', 'ap.auditno');
                        })
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.docsource','=',$invoice->source)
                        ->where('al.doctrantype','=',$invoice->trantype)
                        ->where('al.docauditno','=',$invoice->auditno)
                        ->where('al.recstatus','=','POSTED')
                        ->orderBy('al.idno','DESC');

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
}