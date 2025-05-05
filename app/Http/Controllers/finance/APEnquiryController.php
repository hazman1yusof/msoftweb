<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APEnquiryExport;
use Maatwebsite\Excel\Facades\Excel;

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
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
            default:
                return 'error happen..';
        }
    }

    public function showExcel(Request $request){
        return Excel::download(new APEnquiryExport($request->suppcode_from,$request->suppcode_to,$request->datefrom,$request->dateto), 'APStatement.xlsx');
    }

    public function showpdf(Request $request){

        $suppcode_from = $request->suppcode_from;
        if(empty($request->suppcode_from)){
            $suppcode_from = '%';
        }
        $suppcode_to = $request->suppcode_to;
        $datefrom = Carbon::parse($request->datefrom)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');

        $supp_code = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode', 'su.Name AS supplier_name','su.Addr1 AS Addr1','su.Addr2 AS Addr2', 'su.Addr3 AS Addr3', 'su.Addr4 AS Addr4')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereBetween('ap.postdate', [$datefrom, $dateto])
                    ->whereBetween('su.SuppCode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->distinct('ap.suppcode');
    
        $supp_code = $supp_code->get(['ap.suppcode', 'su.supplier_name', 'su.Addr1', 'su.Addr2', 'su.Addr3', 'su.Addr4']);

        $array_report = [];
        foreach ($supp_code as $key => $value){
            $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.compcode','ap.auditno','ap.trantype','ap.doctype','ap.suppcode','su.Name AS supplier_name','su.Addr1 AS Addr1','su.Addr2 AS Addr2', 'su.Addr3 AS Addr3', 'su.Addr4 AS Addr4', 'ap.actdate','ap.document','ap.cheqno','ap.deptcode','ap.amount','ap.outamount','ap.recstatus','ap.payto','ap.recdate','ap.postdate','ap.postuser','ap.category','ap.remarks','ap.adduser','ap.adddate','ap.upduser','ap.upddate','ap.source','ap.idno','ap.unit','ap.pvno','ap.paymode','ap.bankcode','ap.unallocated')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->where('ap.suppcode','=',$value->suppcode)
                    ->whereBetween('ap.postdate', [$datefrom, $dateto])
                    ->orderBy('ap.postdate','ASC')
                    ->get();

            $calc_openbal = DB::table('finance.apacthdr as ap') 
                    ->where('ap.compcode',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->where('ap.suppcode', $value->suppcode)
                    ->whereDate('ap.postdate', '<',$datefrom);

            $openbal = $this->calc_openbal($calc_openbal); 
            $value->openbal = $openbal;
            
            $value->docno = '';
            $value->amount_dr = 0;
            $value->amount_cr = 0;
            $balance = $openbal;

            foreach ($apacthdr as $key => $value){
                switch ($value->trantype) {
                    case 'IN': //dr
                        $value->docno = $value->document;
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'DN': //dr
                        $value->docno = $value->document;
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'CN': //cr
                        $value->docno = $value->document;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'PV': //cr
                        $value->docno = str_pad($value->pvno, 5, "0", STR_PAD_LEFT);
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'PD': //cr
                        $value->docno = $value->document;
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
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->datefrom = Carbon::parse($request->datefrom)->format('d-m-Y');
        $header->dateto = Carbon::parse($request->dateto)->format('d-m-Y');
        $header->suppcode_from = $request->suppcode_from;
        $header->suppcode_to = $request->suppcode_to;
        $header->compname = $company->name;

        return view('finance.AP.apenquiry.apenquiry_pdfmake',compact('array_report','header', 'supp_code'));
        
    }

    public function calc_openbal($obj){
        $balance = 0;
        foreach ($obj->get() as $key => $value){
            
            switch ($value->trantype) {
                 case 'IN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PV': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PD': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }

        return $balance;
    }

    public function get_table_dtl(Request $request){
        $table = DB::table('finance.apactdtl as apdt')
                    ->select('apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.taxamt AS tot_gst', 'apdt.dorecno', 'apdt.grnno')
                    ->where('source','=',$request->source)
                    ->where('trantype','=',$request->trantype)
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

    public function maintable(Request $request){

        $table = DB::table('finance.apacthdr AS ap')
                    ->select(
                        'ap.compcode AS apacthdr_compcode',
                        'ap.auditno AS apacthdr_auditno', //search
                        'ap.trantype AS apacthdr_trantype', //search
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
                        'ap.postdate AS apacthdr_postdate',
                        'ap.postuser AS apacthdr_postuser',
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
                        'ap.bankcode AS apacthdr_bankcode',
                        'ap.unallocated AS apacthdr_unallocated'
                        
                    )
                    ->leftJoin('material.supplier as su', 'su.SuppCode', '=', 'ap.suppcode')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.source','=','AP');
                    
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
            $table = $table->where('ap.actdate','>',$request->fromdate);
            $table = $table->where('ap.actdate','<',$request->todate);
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
                        $table->Where('ap.auditno','<=',$request->wholeword);
                    });
            }else if($request->searchCol[0] == 'apacthdr_pvno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.pvno','<=',$request->wholeword);
                    });
            }else if($request->searchCol[0] == 'apacthdr_trantype'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.trantype','like',$request->searchVal[0]);
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
                        ->where('source','=',$value->apacthdr_source)
                        ->where('trantype','=',$value->apacthdr_trantype)
                        ->where('auditno','=',$value->apacthdr_auditno);

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

        $apacthdr = DB::table('finance.apacthdr')
                        ->where('idno','=',$request->idno)
                        ->first();

        //trantype (PV, CN, PD)
        if($apacthdr->trantype == 'PV' || $apacthdr->trantype =='PD' || $apacthdr->trantype =='CN') {

            $table = DB::table('finance.apalloc as al')
                        ->select(
                            'al.refauditno as auditno',
                            'al.reftrantype as trantype',
                            'al.refsource as source',
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
                            'al.allocdate',
                            'ap.postdate'
                        )
                        ->join('finance.apacthdr as ap', function($join) use ($request){
                                    $join = $join->on('al.docsource', '=', 'ap.source')
                                        ->on('al.doctrantype', '=', 'ap.trantype')
                                        ->on('al.docauditno', '=', 'ap.auditno');
                        })
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.docsource','=',$apacthdr->source)
                        ->where('al.doctrantype','=',$apacthdr->trantype)
                        ->where('al.docauditno','=',$apacthdr->auditno)
                        ->where('al.bankcode','=',$apacthdr->bankcode)
                        // ->where('ap.pvno','=',$invoice->pvno)
                        ->where('al.recstatus','=','POSTED')
                        ->orderBy('al.idno','DESC');

        }else{ //IN/DN

            $table = DB::table('finance.apalloc as al')
                        ->select(
                            'al.docauditno as auditno',
                            'al.doctrantype as trantype',
                            'al.docsource as source',
                            'al.suppcode',
                            'ap.actdate',
                            'ap.document',
                            'ap.recstatus',
                            'ap.recdate',
                            'ap.amount',
                            'al.allocamount',
                            'al.outamount',
                            'al.recstatus',
                            'al.allocdate',
                            'ap.postdate'
                           
                        )
                        ->join('finance.apacthdr as ap', function($join) use ($request){
                                    $join = $join->on('al.refsource', '=', 'ap.source')
                                        ->on('al.reftrantype', '=', 'ap.trantype')
                                        ->on('al.refauditno', '=', 'ap.auditno');
                        })
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.refsource','=',$apacthdr->source)
                        ->where('al.reftrantype','=',$apacthdr->trantype)
                        ->where('al.refauditno','=',$apacthdr->auditno)
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