<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Jobs\SendEmailPV;

class PaymentVoucherController extends defaultController
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   
        return view('finance.AP.paymentVoucher.paymentVoucher');
    }

    public function show_mobile(Request $request){
        $oper = strtolower($request->scope);//delivered shj sekarang
        $scope = ucfirst(strtolower($request->scope));//Delivered
        $auditno = $request->auditno;
        $type = $request->type;

        $ap_hd = DB::table('finance.apacthdr AS ap')
                        ->select('ap.compcode','ap.auditno','ap.trantype','ap.doctype','ap.suppcode','su.name as suppcode_desc','ap.actdate','ap.document','ap.cheqno','ap.deptcode','ap.amount','ap.outamount','ap.recstatus','ap.payto','ap.recdate','ap.category','ap.remarks','ap.adduser','ap.adddate','ap.upduser','ap.upddate','ap.source','ap.idno','ap.unit','ap.pvno','ap.paymode','pm.description as paymode_desc','ap.bankcode','bn.bankname as bankcode_desc','ap.postdate','ap.cheqdate','ap.requestby','ap.requestdate')
                        ->leftJoin('finance.bank as bn', function($join){
                            $join = $join->where('bn.compcode', '=', session('compcode'));
                            $join = $join->on('bn.bankcode', '=', 'ap.bankcode');
                        })->leftjoin('material.supplier as su', function($join){
                            $join = $join
                                ->where('su.compcode',session('compcode'))
                                ->on('su.SuppCode','ap.suppcode');
                        })->leftjoin('debtor.paymode as pm', function($join){
                            $join = $join
                                ->where('pm.compcode',session('compcode'))
                                ->on('pm.paymode','ap.paymode')
                                ->where('pm.source','AP');
                        })
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.source','AP')
                        ->where('ap.trantype',$type)
                        ->where('ap.auditno',$auditno)
                        ->first();


        $ap_dt = DB::table('finance.apalloc AS al')
                        ->select('al.suppcode','su.name as suppcode_desc','al.allocdate','al.reference','al.refamount','al.outamount','al.allocamount','al.balance')
                        ->leftjoin('material.supplier as su', function($join){
                            $join = $join
                                ->where('su.compcode',session('compcode'))
                                ->on('su.SuppCode','al.suppcode');
                        })
                        ->where('al.compcode','=', session('compcode'))
                        ->where('al.source','=', 'AP')
                        ->where('al.trantype','=', 'AL')
                        ->where('al.docsource','=', 'AP')
                        ->where('al.doctrantype','=', 'PV')
                        ->where('al.docauditno','=', $auditno)
                        ->where('al.recstatus','!=','DELETE')
                        ->where('al.recstatus','!=','CANCELLED')
                        ->get();

        return view('finance.AP.paymentVoucher.paymentVoucher_mobile',compact('ap_hd','ap_dt','scope','oper'));
    }

    public function table(Request $request){   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_alloc_table':
                return $this->get_alloc_table($request);
            case 'get_alloc_when_edit':
                return $this->get_alloc_when_edit($request);
            case 'link_pv':
                return $this->link_pv($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){
        $scope = $request->scope;
        $table = DB::table('finance.apacthdr AS ap')
                    ->select(
                        'ap.compcode AS apacthdr_compcode','ap.auditno AS apacthdr_auditno','ap.trantype AS apacthdr_trantype','ap.doctype AS apacthdr_doctype','ap.suppcode AS apacthdr_suppcode','su.name AS supplier_name','ap.actdate AS apacthdr_actdate','ap.document AS apacthdr_document','ap.cheqno AS apacthdr_cheqno','ap.deptcode AS apacthdr_deptcode','ap.amount AS apacthdr_amount','ap.outamount AS apacthdr_outamount','ap.recstatus AS apacthdr_recstatus','ap.payto AS apacthdr_payto','ap.recdate AS apacthdr_recdate','ap.category AS apacthdr_category','ap.remarks AS apacthdr_remarks','ap.adduser AS apacthdr_adduser','ap.adddate AS apacthdr_adddate','ap.upduser AS apacthdr_upduser','ap.upddate AS apacthdr_upddate','ap.source AS apacthdr_source','ap.idno AS apacthdr_idno','ap.unit AS apacthdr_unit','ap.pvno AS apacthdr_pvno','ap.paymode AS apacthdr_paymode','ap.bankcode AS apacthdr_bankcode','ap.postdate AS apacthdr_postdate','ap.cheqdate AS apacthdr_cheqdate','ap.bankaccno AS apacthdr_bankaccno','ap.requestby AS apacthdr_requestby','ap.requestdate AS apacthdr_requestdate','ap.request_remark AS apacthdr_request_remark','ap.supportby AS apacthdr_supportby','ap.supportdate AS apacthdr_supportdate','ap.support_remark AS apacthdr_support_remark','ap.verifiedby AS apacthdr_verifiedby','ap.verifieddate AS apacthdr_verifieddate','ap.verified_remark AS apacthdr_verified_remark','ap.approvedby AS apacthdr_approvedby','ap.approveddate AS apacthdr_approveddate','ap.approved_remark AS apacthdr_approved_remark','ap.cancelby AS apacthdr_cancelby','ap.canceldate AS apacthdr_canceldate','ap.cancelled_remark AS apacthdr_cancelled_remark'
                    )
                    ->leftJoin('material.supplier as su', function($join) use ($request){
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.source','=',$request->source);

        if(!empty($request->filterVal) && in_array('PD',$request->filterVal)){
            $table = $table->where('ap.trantype','PD');
        }else{
            $table = $table->whereIn('ap.trantype',['PD','PV']);
        }

        if(!in_array($scope, ['ALL','CANCEL','REOPEN'])){

            if(!empty($request->filterVal) && in_array('PD',$request->filterVal)){
                $table = $table->join('finance.queuepd as qpd', function($join) use ($request,$scope){
                    $join = $join
                        ->where('qpd.compcode',session('compcode'))
                        ->where('qpd.trantype','<>','DONE')
                        ->on('qpd.recno','ap.auditno')
                        ->on('qpd.recstatus','ap.recstatus')
                        ->where('qpd.trantype',$scope);
                });
            }else{
                $table = $table->join('finance.queuepv as qpv', function($join) use ($request,$scope){
                    $join = $join
                        ->where('qpv.compcode',session('compcode'))
                        ->where('qpv.trantype','<>','DONE')
                        ->on('qpv.recno','ap.auditno')
                        ->on('qpv.recstatus','ap.recstatus')
                        ->where('qpv.trantype',$scope);
                });
            }

            $table = $table->join('finance.permissiondtl as prdtl', function($join) use ($request,$scope){
                $join = $join
                    ->where('prdtl.compcode',session('compcode'))
                    ->where('prdtl.authorid',session('username'))
                    ->where('prdtl.cando','ACTIVE')
                    ->where('prdtl.recstatus',$scope)
                    ->where(function ($query) {
                        $query
                            ->on('ap.amount','>=','prdtl.minlimit')
                            ->on('ap.amount','<=', 'prdtl.maxlimit');
                    });

                if(!empty($request->filterVal) && in_array('PD',$request->filterVal)){
                    $join = $join->where('prdtl.trantype','PD');
                }else{
                    $join = $join->where('prdtl.trantype','PV');
                }
            });
        }

        if(!empty($request->filterCol)){
            if($request->filterCol[0] == 'ap.recstatus' && $request->filterVal[0] == 'All2'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.recstatus','=','SUPPORT');
                        $table->orWhere('ap.recstatus','=','PREPARED');
                    });
            }else{
                $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
            }
        }

        if(!empty($request->WhereInCol[0])){
            foreach ($request->WhereInCol as $key => $value) {
                // $sr = substr(strstr($value,'.'),1);
                $table = $table->whereIn($value,$request->WhereInVal[$key]);
            }
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

            // $apalloc = DB::table('finance.apalloc')
            //             ->select('allocdate')
            //             ->where('refsource','=',$value->apacthdr_source)
            //             ->where('reftrantype','=',$value->apacthdr_trantype)
            //             ->where('refauditno','=',$value->apacthdr_auditno)
            //             ->where('recstatus','!=','CANCELLED')
            //             ->orderBy('idno', 'desc');

            // if($apalloc->exists()){
            //     $value->apalloc_allocdate = $apalloc->first()->allocdate;
            // }else{
            //     $value->apalloc_allocdate = '';
            // }
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

        $apacthdr = DB::table('finance.apacthdr')
                    ->where('suppcode',$request->payto)
                    ->where('compcode',session('compcode'))
                    ->where('recstatus','=','POSTED')
                    // ->where('postdate','<=',$request->postdate)
                    ->where('outamount','>',0)
                    ->where('source', ['AP','DF','CF','TX'])
                    ->whereIn('trantype', ['IN','DN']);

        $apalloc = DB::table('finance.apalloc')
                    ->where('compcode',session('compcode'))
                    ->where('docsource','AP')
                    ->where('doctrantype','PV')
                    ->where('docauditno',$request->auditno)
                    ->where('recstatus','!=','CANCELLED')
                    ->where('recstatus','!=','DELETE');

        $return_array=[];
        $got_array=[];
        if($apalloc->exists()){
            foreach ($apacthdr->get() as $obj_apacthdr) {
                foreach ($apalloc->get() as $obj_apalloc) {
                    if(!in_array($obj_apacthdr->idno,$got_array)){
                        if(
                            $obj_apalloc->refsource == $obj_apacthdr->source
                            && $obj_apalloc->reftrantype == $obj_apacthdr->trantype
                            && $obj_apalloc->refauditno == $obj_apacthdr->auditno
                        ){

                            $obj_apacthdr->can_alloc=false;
                            $obj_apacthdr->outamount = $obj_apalloc->outamount;
                            $obj_apacthdr->refamount = $obj_apalloc->refamount;
                            $obj_apacthdr->allocamount = $obj_apalloc->allocamount;
                            $obj_apacthdr->source = $obj_apalloc->source;
                            $obj_apacthdr->trantype = $obj_apalloc->trantype;
                            $obj_apacthdr->auditno = $obj_apalloc->auditno;
                            $obj_apacthdr->lineno_ = $obj_apalloc->lineno_;
                             $obj_apacthdr->idno = $obj_apalloc->idno;

                            if(!in_array($obj_apacthdr, $return_array)){
                                array_push($return_array,$obj_apacthdr);
                            }
                            array_push($got_array,$obj_apacthdr->idno);
                        }else{
                            $obj_apacthdr->refamount = $obj_apacthdr->outamount;
                            $obj_apacthdr->can_alloc=true;
                            if(!in_array($obj_apacthdr, $return_array)){
                                array_push($return_array,$obj_apacthdr);
                            }
                        }
                    }
                }
            }
        }else{
            $return_array = $apacthdr->get();
        }

        $responce = new stdClass();
        $responce->rows = $return_array;

        return json_encode($responce);
    }

    public function form(Request $request){   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);break;
            case 'edit':
                return $this->edit($request);break;
            case 'del':
                return $this->del($request);break;
            case 'posted':
                return $this->posted($request);break;
            case 'support':
                return $this->support($request);break;
            case 'verify':
                return $this->verify($request);break;
            case 'approved':
                return $this->approved($request);break;
            case 'cancel':
                return $this->cancel($request);break;
            case 'reject':
                return $this->reject($request);break;
            case 'reopen':
                return $this->reopen($request);break;
            case 'del_alloc':
                return $this->del_alloc($request);break;
            default:
                return 'error happen..';
        }
    }

    public function suppgroup($suppcode){
        $query = DB::table('material.supplier')
                ->select('supplier.SuppGroup')
                ->where('SuppCode','=',$suppcode)
                ->where('compcode','=', session('compcode'))
                ->first();
        
        return $query->SuppGroup;
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            // $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            // $idno = $request->table_id;
        }

        DB::beginTransaction();
        try {
            
            $auditno = $this->defaultSysparam($request->apacthdr_source, $request->apacthdr_trantype);
            $suppgroup = $this->suppgroup($request->apacthdr_suppcode);
            
            if ($request->apacthdr_trantype == 'PV'){

                // $this->checkduplicate_docno('add', $request);

                $table = DB::table("finance.apacthdr");
            
                $array_insert = [
                    'source' => 'AP',
                    'auditno' => $auditno,
                    'trantype' => $request->apacthdr_trantype,
                    'actdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recdate' => $request->apacthdr_postdate,
                    'postdate' => $request->apacthdr_postdate,
                    // 'pvno' => $pvno,
                    'doctype' => $request->apacthdr_doctype,
                    'document' => strtoupper($request->apacthdr_document),
                    'paymode' => $request->apacthdr_paymode,
                    'bankcode' => $request->apacthdr_bankcode,
                    'cheqno' => $request->apacthdr_cheqno,
                    'cheqdate' => $request->apacthdr_cheqdate,
                    'remarks' => strtoupper($request->apacthdr_remarks),
                    'suppcode' => $request->apacthdr_suppcode,
                    'payto' => $request->apacthdr_payto,
                    'bankaccno' => $request->apacthdr_bankaccno,
                    'suppgroup' => $suppgroup,
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    'amount' => $request->apacthdr_amount,
                    'outamount' => $request->apacthdr_amount,
                ];

                if($request->apacthdr_paymode == 'TT'){
                    $last_tt = $this->defaultSysparam('AP','TT');
                    $array_insert['cheqno'] = $last_tt;
                }

                $idno_apacthdr = $table->insertGetId($array_insert);

                foreach ($request->data_detail as $key => $value){
                    $ALauditno = $this->defaultSysparam('AP','AL');

                    $apacthdr_IV = DB::table('finance.apacthdr')
                                ->where('idno','=',$value['idno'])
                                ->first();

                    $outamount = floatval($value['outamount']);
                    $balance = floatval($value['balance']);
                    $allocamount = floatval($value['outamount']) - floatval($value['balance']);
                    $newoutamount_IV = floatval($outamount - $allocamount);

                    if($allocamount == 0 || $value['can_alloc'] == 'false'){
                        continue;
                    }

                    $lineno_ = DB::table('finance.apalloc') 
                        ->where('compcode','=',session('compcode'))
                        ->where('docauditno','=',$auditno)
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')->max('lineno_');

                    if($lineno_ == null){
                        $lineno_ = 1;
                    }else{
                        $lineno_ = $lineno_+1;
                    }

                    DB::table('finance.apalloc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'source' => 'AP',
                            'trantype' => 'AL',
                            'auditno' => $ALauditno,
                            'lineno_' => $lineno_,
                            'docsource' => $request->apacthdr_source,
                            'doctrantype' => $request->apacthdr_trantype,
                            'docauditno' => $auditno,
                            'refsource' => $apacthdr_IV->source,
                            'reftrantype' => $apacthdr_IV->trantype,
                            'refauditno' => $apacthdr_IV->auditno,
                            'refamount' => $apacthdr_IV->amount,
                            'allocdate' => $request->apacthdr_actdate,//blank
                            'reference' => $value['reference'],
                            'remarks' => strtoupper($value['remarks']),
                            'allocamount' => $allocamount,
                            'outamount' => $outamount,
                            'balance' => $balance,
                            'paymode' => $request->apacthdr_paymode,
                            'cheqdate' => $request->apacthdr_cheqdate,
                            // 'recdate' => $request->apacthdr_recdate,
                            'bankcode' => $request->apacthdr_bankcode,
                            'suppcode' => $request->apacthdr_suppcode,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'OPEN'
                        ]);

                    $apacthdr_IV = DB::table('finance.apacthdr')
                                ->where('idno','=',$value['idno'])
                                ->update([
                                    'outamount' => $newoutamount_IV
                                ]);
                }

                //calculate total amount from detail
                $totalAmount = DB::table('finance.apalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('docsource','=','AP')
                    ->where('doctrantype','=','PV')
                    ->where('docauditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->where('recstatus','!=','CANCELLED')
                    ->sum('allocamount');
                
                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_apacthdr)
                    ->update([
                        'amount' => $totalAmount,
                        // 'outamount' => '0',
                        'recstatus' => 'OPEN'
                    ]);

                if($allocamount > $outamount){
                    throw new \Exception('Amount paid exceed outamount', 500);
                }

                $responce = new stdClass();
                $responce->auditno = $auditno;
                $responce->idno = $idno_apacthdr;
                $responce->totalAmount = $totalAmount;
            
            } else if ($request->apacthdr_trantype == 'PD'){

                // $this->checkduplicate_docno('add', $request);

                $table = DB::table("finance.apacthdr");
            
                $array_insert = [
                    'source' => 'AP',
                    'auditno' => $auditno,
                    'trantype' => $request->apacthdr_trantype,
                    'actdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recdate' => $request->apacthdr_postdate,
                    'postdate' => $request->apacthdr_postdate,
                    // 'pvno' => $pvno,
                    'doctype' => $request->apacthdr_doctype,
                    'document' => strtoupper($request->apacthdr_document),
                    'paymode' => $request->apacthdr_paymode,
                    'bankcode' => $request->apacthdr_bankcode,
                    'cheqno' => $request->apacthdr_cheqno,
                    'cheqdate' => $request->apacthdr_cheqdate,
                    'remarks' => strtoupper($request->apacthdr_remarks),
                    'suppcode' => $request->apacthdr_suppcode,
                    'payto' => $request->apacthdr_payto,
                    'amount' => $request->apacthdr_amount,
                    'outamount' => $request->apacthdr_amount,
                    'suppgroup' => $suppgroup,
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN'
                ];

                if($request->apacthdr_paymode == 'TT'){
                    $last_tt = $this->defaultSysparam('AP','TT');
                    $array_insert['cheqno'] = $last_tt;
                }

                $idno_apacthdr = $table->insertGetId($array_insert);

                $responce = new stdClass();
                $responce->auditno = $auditno;
                $responce->idno = $idno_apacthdr;
            }

            if($request->apacthdr_paymode == 'CHEQUE'){
                $chqtran =  DB::table('finance.chqtran')
                            ->where('compcode','=',session('compcode'))
                            ->where('bankcode','=',$request->apacthdr_bankcode)
                            ->where('cheqno','=',$request->apacthdr_cheqno)
                            ->where('recstatus','OPEN');

                if(!$chqtran->exists()){
                    throw new \Exception("Cheque Error, try again..");
                }

                DB::table('finance.chqtran')
                        ->where('compcode','=',session('compcode'))
                        ->where('bankcode','=',$request->apacthdr_bankcode)
                        ->where('cheqno','=',$request->apacthdr_cheqno)
                        ->where('recstatus','OPEN')
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'cheqdate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'amount' => $request->apacthdr_amount,
                            'remarks' => strtoupper($request->apacthdr_remarks),
                            'recstatus' => 'ISSUED',
                            'auditno' => $auditno,
                            'trantype' => $request->apacthdr_trantype,
                            'source' => 'AP',
                            'payto' => $request->apacthdr_payto,
                        ]);
            }

            ////update bankaccno at supplier
            DB::table('material.supplier')
                ->where('compcode','=',session('compcode'))
                ->where('SuppCode','=',$request->apacthdr_suppcode)
                ->update([
                    'AccNo' => $request->apacthdr_bankaccno,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();  

            echo json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $apacthdr_trantype = DB::table('finance.apacthdr')
            ->select('trantype')
            ->where('compcode','=',session('compcode'))
            ->where('auditno','=',$request->apacthdr_auditno)->first();
          
        if ($apacthdr_trantype->trantype == 'PV'){
            
            DB::beginTransaction();
            // $this->checkduplicate_docno('edit', $request);

            $table = DB::table("finance.apacthdr");

            $array_update = [
                'unit' => session('unit'),
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'pvno' => $request->apacthdr_pvno,
                'doctype' => $request->apacthdr_doctype,
                'recdate' => $request->apacthdr_postdate,
                'postdate' => $request->apacthdr_postdate,
                'cheqdate' => $request->apacthdr_cheqdate,
                'suppcode' => strtoupper($request->apacthdr_suppcode),
                'document' => strtoupper($request->apacthdr_document),
                'paymode' => strtoupper($request->apacthdr_paymode),
                'bankcode' => strtoupper($request->apacthdr_bankcode),
                'cheqno' => strtoupper($request->apacthdr_cheqno),
                'remarks' => strtoupper($request->apacthdr_remarks),
                'bankaccno' => $request->apacthdr_bankaccno,
            ];

            try {

                $idno = $request->idno;
                $table->where('idno','=',$idno)->update($array_update);
                $apacthdr_ = DB::table('finance.apacthdr')
                                ->where('idno','=',$idno)
                                ->first();

                foreach ($request->data_detail as $key => $value) {
                    $ALauditno = $this->defaultSysparam('AP','AL');

                    $apacthdr_IV = DB::table('finance.apacthdr')
                                ->where('idno','=',$value['idno'])
                                ->first();

                    $outamount = floatval($value['outamount']);
                    $allocamount = floatval($value['outamount']) - floatval($value['balance']);
                    $newoutamount_IV = floatval($outamount - $allocamount);

                    if($allocamount == 0 || $value['can_alloc'] == 'false'){
                        continue;
                    }

                    $lineno_ = DB::table('finance.apalloc') 
                        ->where('compcode','=',session('compcode'))
                        ->where('docauditno','=',$apacthdr_->auditno)
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')->max('lineno_');

                    if($lineno_ == null){
                        $lineno_ = 1;
                    }else{
                        $lineno_ = $lineno_+1;
                    }

                    DB::table('finance.apalloc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'source' => 'AP',
                            'trantype' => 'AL',
                            'auditno' => $ALauditno,
                            'lineno_' => $lineno_,
                            'docsource' => $apacthdr_->source,
                            'doctrantype' => $apacthdr_->trantype,
                            'docauditno' => $apacthdr_->auditno,
                            'refsource' => $apacthdr_IV->source,
                            'reftrantype' => $apacthdr_IV->trantype,
                            'refauditno' => $apacthdr_IV->auditno,
                            'refamount' => $apacthdr_IV->amount,
                            // 'allocdate' => $request->apacthdr_actdate,//blank
                            'reference' => $value['reference'],
                            'remarks' => strtoupper($value['remarks']),
                            'allocamount' => $allocamount,
                            'outamount' => $outamount,
                            'balance' => $value['balance'],
                            'paymode' => $request->apacthdr_paymode,
                            'cheqdate' => $request->apacthdr_cheqdate,
                            // 'recdate' => $request->apacthdr_recdate,
                            'bankcode' => $request->apacthdr_bankcode,
                            'suppcode' => $request->apacthdr_suppcode,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'OPEN'
                        ]);

                    $apacthdr_IV = DB::table('finance.apacthdr')
                                ->where('idno','=',$value['idno'])
                                ->update([
                                    'outamount' => $newoutamount_IV
                                ]);
                }

                //calculate total amount from detail
                $totalAmount = DB::table('finance.apalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('docsource','=','AP')
                    ->where('doctrantype','=','PV')
                    ->where('docauditno','=',$apacthdr_->auditno)
                    ->where('recstatus','!=','CANCELLED')
                    ->where('recstatus','!=','DELETE')
                    ->sum('allocamount');
                
                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno)
                    ->update([
                        'amount' => $totalAmount,
                        'recstatus' => 'OPEN'
                    ]);

                DB::commit();

                $responce = new stdClass();
                $responce->result = 'success';

                return json_encode($responce);
            } catch (\Exception $e) {
                DB::rollback();

                return response('Error'.$e, 500);
            }

        } else {

            DB::beginTransaction();

            // $this->checkduplicate_docno('edit', $request);
            $table = DB::table("finance.apacthdr");

            $array_update = [
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'pvno' => $request->apacthdr_pvno,
                // 'doctype' => $request->apacthdr_doctype,
                'actdate' => $request->apacthdr_actdate,
                'recdate' => $request->apacthdr_postdate,
                'postdate' => $request->apacthdr_postdate,
                'cheqdate' => $request->apacthdr_cheqdate,
                'amount' => $request->apacthdr_amount,
                'suppcode' => strtoupper($request->apacthdr_suppcode),
                'payto' => strtoupper($request->apacthdr_payto),
                'document' => strtoupper($request->apacthdr_document),
                'paymode' => strtoupper($request->apacthdr_paymode),
                'bankcode' => strtoupper($request->apacthdr_bankcode),
                'cheqno' => strtoupper($request->apacthdr_cheqno),
                'remarks' => strtoupper($request->apacthdr_remarks),
            ];

            try {
                //////////where//////////
                $table = $table->where('idno','=',$request->idno);
                $table->update($array_update);


                DB::commit();

                $responce = new stdClass();
                echo json_encode($responce);
            } catch (\Exception $e) {
                DB::rollback();

                return response($e->getMessage(), 500);
            }
        }
    }

    public function posted_lama(Request $request){
        DB::beginTransaction();
        try {
            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->first();

                $pvno = $this->defaultSysparam('HIS','PV');

                $yearperiod = defaultController::getyearperiod_($apacthdr->recdate);
                if($yearperiod->status == 'C'){
                    throw new \Exception('Auditno: '.$apacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
                }

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->update([
                        'pvno' => $pvno,
                        'recdate' => $apacthdr->postdate,
                        'recstatus' => 'POSTED',
                        'outamount' => 0.00,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'postuser' => session('username')
                    ]);

                $this->gltran($idno_obj['idno']);

                $apalloc = DB::table('finance.apalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('docsource','=', $apacthdr->source)
                    ->where('doctrantype','=', $apacthdr->trantype)
                    ->where('docauditno','=', $apacthdr->auditno)
                    ->update([
                        'allocdate' => $apacthdr->postdate,
                        'recstatus' => 'POSTED',
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
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
            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->first();

                if($apacthdr->recstatus != 'OPEN'){
                    continue;
                }

                if($apacthdr->trantype == 'PV'){
                    $queue = 'finance.queuepv';
                    $trantype = 'VERIFIED';
                }else{
                    $queue = 'finance.queuepd';
                    $trantype = 'VERIFIED';
                }

                DB::table($queue)
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $apacthdr->auditno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => 'ALL',
                        'recstatus' => 'PREPARED',
                        'trantype' => $trantype,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                $yearperiod = defaultController::getyearperiod_($apacthdr->recdate);
                if($yearperiod->status == 'C'){
                    throw new \Exception('Auditno: '.$apacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
                }

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->update([
                        'requestby' => session('username'),
                        'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'PREPARED'
                    ]);

                if($apacthdr->trantype == 'PV'){
                    DB::table('finance.apalloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('unit','=',session('unit'))
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')
                        ->where('docauditno','=',$apacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->where('recstatus','!=','CANCELLED')
                        ->update([
                            'recstatus' => 'PREPARED'
                        ]);
                
                    $this->sendemail('VERIFIED',$apacthdr->auditno);
                }

            }
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function support(Request $request){//skip kalu pv

         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->first();

                if($apacthdr->recstatus != 'PREPARED'){
                    continue;
                }

                if($apacthdr->trantype == 'PV'){
                    $queue = 'finance.queuepv';
                    $trantype = 'VERIFIED';

                    throw new \Exception("PV cant be supported, got to verified",500);
                }else{
                    $queue = 'finance.queuepd';
                    $trantype = 'VERIFIED';
                }

                $authorise = DB::table('finance.permissiondtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=',$apacthdr->trantype)
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','SUPPORT')
                    // ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$apacthdr->amount);

                if(!$authorise->exists()){
                    throw new \Exception("The user doesnt have authority",500);
                }

                $authorise_use = $authorise->first();

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->update([
                        'supportby' => session('username'),
                        'supportdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'SUPPORT'
                    ]);

                if($apacthdr->trantype == 'PV'){
                    DB::table('finance.apalloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('unit','=',session('unit'))
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')
                        ->where('docauditno','=',$apacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->where('recstatus','!=','CANCELLED')
                        ->update([
                            'recstatus' => 'SUPPORT'
                        ]);
                }

                DB::table($queue)
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$apacthdr->auditno)
                    ->update([
                        'AuthorisedID' => $authorise_use->authorid,
                        'recstatus' => 'SUPPORT',
                        'trantype' => 'VERIFIED',
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

    public function verify(Request $request){

         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->first();

                // if(!in_array($apacthdr->recstatus, ['PREPARED','SUPPORT']){
                //     continue;
                // }

                if($apacthdr->trantype == 'PV'){
                    $queue = 'finance.queuepv';
                    if($apacthdr->recstatus != 'PREPARED'){
                        continue;
                    }
                }else{
                    $queue = 'finance.queuepd';
                    if($apacthdr->recstatus != 'PREPARED'){
                        continue;
                    }
                }

                $authorise = DB::table('finance.permissiondtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=',$apacthdr->trantype)
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','VERIFIED')
                    // ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$apacthdr->amount);

                if(!$authorise->exists()){
                    throw new \Exception("The user doesnt have authority",500);
                }

                $authorise_use = $authorise->first();

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->update([
                        'verifiedby' => session('username'),
                        'verifieddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'VERIFIED'
                    ]);

                DB::table($queue)
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$apacthdr->auditno)
                    ->update([
                        'AuthorisedID' => $authorise_use->authorid,
                        'recstatus' => 'VERIFIED',
                        'trantype' => 'APPROVED',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                if($apacthdr->trantype == 'PV'){
                    DB::table('finance.apalloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('unit','=',session('unit'))
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')
                        ->where('docauditno','=',$apacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->where('recstatus','!=','CANCELLED')
                        ->update([
                            'recstatus' => 'VERIFIED'
                        ]);
                        
                    $this->sendemail('APPROVED',$apacthdr->auditno);
                }
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

            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->first();

                if(!in_array($apacthdr->recstatus, ['VERIFIED','RECOMMENDED1','RECOMMENDED2'])){
                    continue;
                }

                if($apacthdr->trantype == 'PV'){
                    $queue = 'finance.queuepv';
                    $outamount_ = 0.00;
                }else{
                    $queue = 'finance.queuepd';
                    $outamount_ = $apacthdr->amount;
                }

                $authorise = DB::table('finance.permissiondtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=',$apacthdr->trantype)
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','APPROVED')
                    // ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$apacthdr->amount);

                if(!$authorise->exists()){
                    throw new \Exception("The user doesnt have authority",500);
                }

                $authorise_use = $authorise->first();

                $yearperiod = defaultController::getyearperiod_($apacthdr->recdate);
                if($yearperiod->status == 'C'){
                    throw new \Exception('Auditno: '.$apacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
                }

                $pvno = $this->defaultSysparam('HIS','PV');

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->update([
                        'pvno' => $pvno,
                        'recdate' => $apacthdr->postdate,
                        'recstatus' => 'POSTED',
                        'outamount' => $outamount_,
                        'approvedby' => session('username'),
                        'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'APPROVED'
                    ]);

                $this->gltran($idno_obj['idno']);

                if($apacthdr->trantype == 'PV'){
                    DB::table('finance.apalloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('unit','=',session('unit'))
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')
                        ->where('docauditno','=',$apacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->where('recstatus','!=','CANCELLED')
                        ->update([
                            'recstatus' => 'POSTED',
                            'allocdate' => $apacthdr->postdate,
                        ]);

                }

                DB::table($queue)
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$apacthdr->auditno)
                    ->update([
                        'AuthorisedID' => $authorise_use->authorid,
                        'recstatus' => 'APPROVED',
                        'trantype' => 'done',
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

    public function cancel(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=', $idno_obj['idno'])
                    ->first();

                if(in_array($apacthdr->recstatus, ['OPEN','PREPARED','SUPPORT','VERIFIED','REJECTED'])){
                    $apalloc = DB::table('finance.apalloc')
                                ->where('compcode','=',session('compcode'))
                                // ->where('unit','=',session('unit'))
                                ->where('docsource','=',$apacthdr->source)
                                ->where('doctrantype','=',$apacthdr->trantype)
                                ->where('docauditno','=',$apacthdr->auditno)
                                ->get();

                    foreach($apalloc as $value){
                        $value = (array)$value;
                        
                        $refapacthdr = DB::table('finance.apacthdr')
                                        ->where('compcode','=',session('compcode'))
                                        // ->where('unit','=',session('unit'))
                                        ->where('source','=',$value['refsource'])
                                        ->where('trantype','=',$value['reftrantype'])
                                        ->where('auditno','=',$value['refauditno']);
                        $refapacthdr
                            ->update([
                                'outamount' => floatval($refapacthdr->first()->outamount) + floatval($value['allocamount'])
                            ]);
                            
                        DB::table('finance.apalloc')
                            ->where('idno','=',$value['idno'])
                            ->update([
                                'allocamount' => 0,
                                'recstatus' => 'CANCELLED',
                            ]);
                    }

                    DB::table('finance.apacthdr')
                        ->where('idno','=',$idno_obj['idno'])
                        ->update([
                            'recstatus' => 'CANCELLED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'amount' => 0,
                            'outamount' => 0,
                        ]);

                }else if($apacthdr->recstatus == 'APPROVED'){

                    $this->gltran_cancel($idno_obj['idno']);

                    $apalloc = DB::table('finance.apalloc')
                                ->where('compcode','=',session('compcode'))
                                // ->where('unit','=',session('unit'))
                                ->where('docsource','=',$apacthdr->source)
                                ->where('doctrantype','=',$apacthdr->trantype)
                                ->where('docauditno','=',$apacthdr->auditno)
                                ->get();
                    foreach($apalloc as $value){ //update reference document
                        $value = (array)$value;
                        
                        $refapacthdr = DB::table('finance.apacthdr')
                                        ->where('compcode','=',session('compcode'))
                                        // ->where('unit','=',session('unit'))
                                        ->where('source','=',$value['refsource'])
                                        ->where('trantype','=',$value['reftrantype'])
                                        ->where('auditno','=',$value['refauditno']);
                        $refapacthdr
                            ->update([
                                'outamount' => floatval($refapacthdr->first()->outamount) + floatval($value['allocamount'])
                            ]);
                    }

                    DB::table('finance.apacthdr')
                        ->where('idno','=', $idno_obj['idno'])
                        ->update([
                            'recstatus' => 'CANCELLED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'amount' => 0,
                            'outamount' => 0
                        ]);

                    DB::table('finance.apalloc')
                        ->where('compcode','=',session('compcode'))
                        // ->where('unit','=',session('unit'))
                        ->where('docsource','=',$apacthdr->source)
                        ->where('doctrantype','=',$apacthdr->trantype)
                        ->where('docauditno','=',$apacthdr->auditno)
                        ->update([
                            'allocamount' => 0,
                            'recstatus' => 'CANCELLED',
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                }
                   
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 500);
        }
    }

    public function reject(Request $request){
        DB::beginTransaction();

        try{
           
            foreach ($request->idno_array as $value){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$value)
                    ->first();

                if($apacthdr->trantype == 'PV'){
                    $queue = 'finance.queuepv';
                }else{
                    $queue = 'finance.queuepd';
                }

                if(!in_array($apacthdr->recstatus, ['PREPARED','SUPPORT','VERIFIED'])){
                    continue;
                }

                $apacthdr_update = [
                    'recstatus' => 'REJECTED',
                    'cancelby' => session('username'),
                    'canceldate' => Carbon::now("Asia/Kuala_Lumpur"),
                ];

                if(!empty($request->remarks)){
                    $apacthdr_update['cancelled_remark'] = $request->remarks;
                }

                DB::table('finance.apacthdr')
                    ->where('idno','=',$value)
                    ->update($apacthdr_update);

                if($apacthdr->trantype == 'PV'){
                    DB::table('finance.apalloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('unit','=',session('unit'))
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')
                        ->where('docauditno','=',$apacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->where('recstatus','!=','CANCELLED')
                        ->update([
                            'recstatus' => 'REJECTED'
                        ]);

                }

                // DB::table($queue)
                //     ->where('recno','=',$apacthdr->auditno)
                //     ->delete();

                DB::table($queue)
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$apacthdr->auditno)
                    ->update([
                        'AuthorisedID' => $apacthdr->adduser,
                        'recstatus' => 'REJECTED',
                        'trantype' => 'REOPEN',
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

    function sendemail($trantype,$recno){
        // $trantype = 'SUPPORT';
        // $recno = '64';
        $qpv = DB::table('finance.queuepv as qpv')
                    ->select('qpv.trantype','prdtl.authorid','ap.pvno','qpv.recno','ap.recdate','qpv.recstatus','ap.amount','ap.payto','ap.adduser','users.email')
                    ->join('finance.permissiondtl as prdtl', function($join){
                        $join = $join
                            ->where('prdtl.compcode',session('compcode'))
                            // ->where('adtl.authorid',session('username'))
                            ->where('prdtl.trantype','PV')
                            ->where('prdtl.cando','ACTIVE')
                            // ->on('adtl.prtype','qpo.prtype')
                            ->on('prdtl.recstatus','qpv.trantype');
                    })
                    ->join('finance.apacthdr as ap', function($join){
                        $join = $join
                            ->where('ap.compcode',session('compcode'))
                            ->where('ap.trantype','PV')
                            ->on('ap.auditno','qpv.recno')
                            ->on('ap.recstatus','qpv.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('ap.amount','>=','prdtl.minlimit')
                                    ->on('ap.amount','<=', 'prdtl.maxlimit');
                            });;
                    })
                    ->join('sysdb.users as users', function($join){
                        $join = $join
                            ->where('users.compcode',session('compcode'))
                            // ->where('users.email','HAZMAN.YUSOF@GMAIL.COM')
                            ->on('users.username','prdtl.authorid');
                    })
                    ->where('qpv.compcode',session('compcode'))
                    ->where('qpv.trantype',$trantype)
                    ->where('qpv.recno',$recno)
                    ->get();

        SendEmailPV::dispatch($qpv);
    }

    public function reopen(Request $request){

        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->first();

                if($apacthdr->trantype == 'PV'){
                    $queue = 'finance.queuepv';
                }else{
                    $queue = 'finance.queuepd';
                }

                if($apacthdr->recstatus != 'CANCELLED' || $apacthdr->recstatus != 'REJECTED'){
                    continue;
                }

                $array_update= [
                    'recstatus' => 'OPEN',
                    'requestby' => null,
                    'requestdate' => null,
                    'supportby' => null,
                    'supportdate' => null,
                    'support_remark' => null,
                    'verifiedby' => null,
                    'verifieddate' => null,
                    'verified_remark' => null,
                    'approvedby' => null,
                    'approveddate' => null,
                    'approved_remark' => null,
                ];

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->update($array_update);

                if($apacthdr->trantype == 'PV'){
                    DB::table('finance.apalloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('unit','=',session('unit'))
                        ->where('docsource','=','AP')
                        ->where('doctrantype','=','PV')
                        ->where('docauditno','=',$apacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->where('recstatus','!=','CANCELLED')
                        ->update([
                            'recstatus' => 'OPEN'
                        ]);

                }

                DB::table($queue)
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$apacthdr->auditno)
                    ->delete();

            }

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del_alloc(Request $request){

        DB::beginTransaction();

        try {

            $apalloc = DB::table('finance.apalloc')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->first();

            $apacthdr = DB::table('finance.apacthdr')
                        ->where('compcode','=',session('compcode'))
                        ->where('unit','=',session('unit'))
                        ->where('source','=',$apalloc->docsource)
                        ->where('trantype','=',$apalloc->doctrantype)
                        ->where('auditno','=',$apalloc->docauditno)
                        ->first();

            if($apacthdr->recstatus != 'OPEN'){
                throw new \Exception('Cant delete from PV that is not OPEN', 500);
            }

            $refapacthdr = DB::table('finance.apacthdr')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('unit','=',session('unit'))
                                    ->where('source','=',$apalloc->refsource)
                                    ->where('trantype','=',$apalloc->reftrantype)
                                    ->where('auditno','=',$apalloc->refauditno);
            $refapacthdr
                ->update([
                    'outamount' => floatval($refapacthdr->first()->outamount) + floatval($apalloc->allocamount)
                ]);

            //update status
            DB::table('finance.apalloc')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'recstatus' => 'DELETE',
                    ]);

            //calculate total amount from detail
            $totalAmount = DB::table('finance.apalloc')
                ->where('compcode','=',session('compcode'))
                ->where('unit','=',session('unit'))
                ->where('docsource','=',$apalloc->docsource)
                ->where('doctrantype','=',$apalloc->doctrantype)
                ->where('docauditno','=',$apalloc->docauditno)
                ->where('recstatus','!=','DELETE')
                ->where('recstatus','!=','CANCELLED')
                ->sum('allocamount');
            
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('unit','=',session('unit'))
                ->where('source','=',$apalloc->docsource)
                ->where('trantype','=',$apalloc->doctrantype)
                ->where('auditno','=',$apalloc->docauditno)
                ->update([
                    'amount' => $totalAmount,
                ]);

            // $apacthdr_outamount = floatVal($request->apacthdr_outamount);
            // $newoutamthdr = floatval($apacthdr_outamount + $allocamt);

            // //then update to header CN
            // DB::table('finance.apacthdr')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('source','=',$apalloc->docsource)
            //     ->where('trantype','=',$apalloc->doctrantype)
            //     ->where('auditno','=',$apalloc->docauditno)
            //     ->update([
            //         'outamount' => $newoutamthdr
            //     ]);
                
            DB::commit();

            $responce = new stdClass();
            $responce->newoutamthdr = $totalAmount;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function get_alloc_table(Request $request){
        $alloc_table = DB::table('finance.apalloc')
                            ->where('compcode','=', session('compcode'))
                            ->where('source','=', 'AP')
                            ->where('trantype','=', 'AL')
                            ->where('docsource','=', 'AP')
                            ->where('doctrantype','=', 'PV')
                            ->where('docauditno','=', $request->apacthdr_auditno)
                            ->where('recstatus','!=','DELETE')
                            ->where('recstatus','!=','CANCELLED');

        $paginate = $alloc_table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($alloc_table);
        
        return json_encode($responce);
    }

    public function gltran($idno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate);

        $credit_obj = $this->gltran_frombank($apacthdr_obj->bankcode);
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
                'description' => $apacthdr_obj->bankcode.'</br>'.$apacthdr_obj->cheqno,
                'postdate' => $apacthdr_obj->postdate,
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
        //cbtran

        //1st step add cbtran credit
        DB::table('finance.cbtran')
            ->insert([  'compcode' => $apacthdr_obj->compcode, 
                        'bankcode' => $apacthdr_obj->bankcode, 
                        'source' => $apacthdr_obj->source, 
                        'trantype' => $apacthdr_obj->trantype, 
                        'auditno' => $apacthdr_obj->auditno, 
                        'postdate' => $apacthdr_obj->actdate, 
                        'year' => $yearperiod->year, 
                        'period' => $yearperiod->period, 
                        'cheqno' => $apacthdr_obj->cheqno, 
                        'amount' => -$apacthdr_obj->amount, 
                        'remarks' => strtoupper($apacthdr_obj->remarks), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'reference' => 'Pay To :'. ' ' .$apacthdr_obj->payto  .' '. $apacthdr_obj->remarks, 
                        'recstatus' => 'ACTIVE' 
                    ]);

        //1st step, 2nd phase, update bank detaild
        if($this->isCBtranExist($apacthdr_obj->bankcode,$yearperiod->year,$yearperiod->period)){

            $totamt = $this->getCbtranTotamt($apacthdr_obj->bankcode,$yearperiod->year,$yearperiod->period);

            DB::table('finance.bankdtl')
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$yearperiod->year)
                ->where('bankcode','=',$apacthdr_obj->bankcode)
                ->update([
                    "actamount".$yearperiod->period => $totamt->amount
                ]);

        }else{

            DB::table('finance.bankdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'bankcode' => $apacthdr_obj->bankcode,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$apacthdr_obj->amount,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),

                    ]);
        }
    }

    public function gltran_cancel($idno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate);

        $credit_obj = $this->gltran_frombank($apacthdr_obj->bankcode);
        $debit_obj = $this->gltran_fromsupp($apacthdr_obj->suppcode,$apacthdr_obj->trantype);

        //1. buat gltran
        DB::table('finance.gltran')
            ->where('source','=',$apacthdr_obj->source)
            ->where('trantype','=',$apacthdr_obj->trantype)
            ->where('auditno','=',$apacthdr_obj->auditno)
            ->delete();

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
                    'actamount'.$yearperiod->period => $apacthdr_obj->amount - $gltranAmount,
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
                    'actamount'.$yearperiod->period => $gltranAmount + $apacthdr_obj->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function gltran_frombank($bankcode){

        $obj = DB::table("finance.bank")
                ->select('glaccno','glccode')
                ->where('compcode','=',session('compcode'))
                ->where('bankcode','=',$bankcode)
                ->first();

        return $obj;
    }

    public function gltran_fromsupp($suppcode,$trantype){

        if($trantype == 'PV'){
            $obj = DB::table("material.supplier")
                ->select('costcode','glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('suppcode','=',$suppcode)
                ->first();
        }else{
            $obj = DB::table("material.supplier")
                ->select('Advccode as costcode','AdvGlaccno as glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('suppcode','=',$suppcode)
                ->first();
        }

        return $obj;
    }

    public function checkduplicate_docno($oper,$request){

        if(!empty($request->apacthdr_document)){
            if($oper == 'edit'){
                $obj = DB::table("finance.apacthdr")
                    ->where('compcode','=',session('compcode'))
                    ->where('document','=',$request->apacthdr_document)
                    ->where('recstatus','!=','CANCELLED')
                    ->where('idno','!=',$request->idno);
            }else{
                $obj = DB::table("finance.apacthdr")
                    ->where('compcode','=',session('compcode'))
                    ->where('document','=',$request->apacthdr_document)
                    ->where('recstatus','!=','CANCELLED');
            }

            if($obj->exists()){
                throw new \Exception('duplicate_docno', 500);
            }
        }
    }

    public function showpdf(Request $request){
        $auditno = $request->auditno;
        $trantype = $request->trantype;
        if(!$auditno){
            abort(404);
        }

        $apacthdr = DB::table('finance.apacthdr as h')
            ->select('h.compcode', 'h.auditno', 'h.trantype', 'h.source','h.doctype', 'h.pvno', 'h.suppcode', 'm.Name as suppname', 'm.Addr1 as addr1', 'm.Addr2 as addr2', 'm.Addr3 as addr3', 'm.TelNo as telno', 'm.TINNo', 'm.CompRegNo', 'm.AccNo', 'h.actdate', 'h.document', 'h.deptcode', 'h.amount', 'h.outamount', 'h.recstatus', 'h.payto', 'h.category', 'h.remarks', 'h.paymode', 'h.bankcode', 'h.cheqno','h.bankaccno as h_bankaccno','b.bankname', 'b.bankaccount as bankaccno_desc', 'h.requestby', 'h.supportby','h.verifiedby', 'h.approvedby','u.name as requestby_name','u.designation as requestby_dsg','s.name as supportby_name','s.designation as supportby_dsg','e.name as verifiedby_name','e.designation as verifiedby_dsg','ur.name as approvedby_name','ur.designation as approvedby_dsg','gl_dr.description as gl_dr_desc','gl_cr.description as gl_cr_desc','gl_dr.glaccno as gl_dr_acc','gl_cr.glaccno as gl_cr_acc')
            ->leftJoin('material.supplier as m', function($join) use ($request){
                $join = $join->on('m.suppcode', '=', 'h.payto');
                $join = $join->where('m.compcode', '=', session('compcode'));
            })
            ->leftJoin('finance.glmasref as gl_dr', function($join) use ($request){
                $join = $join->on('gl_dr.glaccno', '=', 'm.GlAccNo');
                $join = $join->where('gl_dr.compcode', '=', session('compcode'));
            })
            ->leftJoin('finance.bank as b', function($join) use ($request){
                $join = $join->on('b.bankcode', '=', 'h.bankcode');
                $join = $join->where('b.compcode', '=', session('compcode'));
            })
            ->leftJoin('finance.glmasref as gl_cr', function($join) use ($request){
                $join = $join->on('gl_cr.glaccno', '=', 'b.glaccno');
                $join = $join->where('gl_cr.compcode', '=', session('compcode'));
            })
            ->leftJoin('sysdb.users as u', function ($join) use ($request){
                $join = $join->on('u.username', '=', 'h.requestby')
                            ->where('u.compcode','=',session('compcode'));
            })
            ->leftJoin('sysdb.users as s', function ($join) use ($request){
                $join = $join->on('s.username', '=', 'h.supportby')
                            ->where('s.compcode','=',session('compcode'));
            })
            ->leftJoin('sysdb.users as e', function ($join) use ($request){
                $join = $join->on('e.username', '=', 'h.verifiedby')
                            ->where('e.compcode','=',session('compcode'));
            })
            ->leftJoin('sysdb.users as ur', function ($join) use ($request){
                $join = $join->on('ur.username', '=', 'h.approvedby')
                            ->where('ur.compcode','=',session('compcode'));
            })
            ->where('h.compcode', '=', session('compcode'))
            ->where('h.trantype','=',$trantype)
            // ->whereIn('h.trantype',['PD','PV'])
            ->where('h.auditno','=',$auditno)
            ->first();

        if ($apacthdr->recstatus == "APPROVED" && $apacthdr->trantype == "PV"){
            $title = " PAYMENT VOUCHER";
        }else if ($apacthdr->recstatus == "APPROVED" && $apacthdr->trantype == "PD") {
            $title = " PAYMENT DEPOSIT";
        }else if ($apacthdr->recstatus != "APPROVED" && $apacthdr->trantype == "PV") {
            $title = " PAYMENT VOUCHER";
        }else if ($apacthdr->recstatus != "APPROVED" && $apacthdr->trantype == "PD") {
            $title = " PAYMENT DEPOSIT";
        }

        if($trantype == 'PV'){
            $apalloc = DB::table('finance.apalloc')
                        ->select('compcode','source','trantype', 'auditno', 'lineno_', 'docsource', 'doctrantype', 'docauditno', 'refsource', 'reftrantype', 'refauditno', 'refamount', 'allocdate', 'allocamount', 'recstatus', 'remarks', 'suppcode', 'reference', 'lastupdate' )
                        ->where('compcode','=', session('compcode'))
                        ->where('source','=', 'AP')
                        ->where('trantype','=', 'AL')
                        ->where('docsource','=', 'AP')
                        ->where('doctrantype','=', 'PV')
                        ->where('docauditno','=', $auditno)
                        ->where('recstatus','!=','CANCELLED')
                        ->where('recstatus','!=','DELETE')
                        ->get();

            foreach($apalloc as $obj_alloc){ //update reference document
                
                $refapacthdr = DB::table('finance.apacthdr')
                                ->where('compcode','=',session('compcode'))
                                // ->where('unit','=',session('unit'))
                                ->where('source','=',$obj_alloc->refsource)
                                ->where('trantype','=',$obj_alloc->reftrantype)
                                ->where('auditno','=',$obj_alloc->refauditno);
                
                if($refapacthdr->exists()){
                    $refapacthdr = $refapacthdr->first();
                    $obj_alloc->invdate = Carbon::parse($refapacthdr->postdate)->format('d/m/Y');
                }else{
                    $obj_alloc->invdate = '-';
                }
            }
        }else{
            $apalloc = [];
        }


        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
                    
        $totamount_expld = explode(".", (float)$apacthdr->amount);
        
        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";

        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }

        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";

        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }

        $CN_obj = $this->get_CN_from_PV($apacthdr);
        
        $attachment_files =$this->get_attachment_files($apalloc);

        return view('finance.AP.paymentVoucher.paymentVoucher_pdfmake',compact('apacthdr','apalloc','totamt_eng','company', 'title','CN_obj','attachment_files'));

        // if(empty($request->type)){

        // $pdf = PDF::loadView('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        // return $pdf->stream();      

        // return view('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        // } else {
        //     return view('finance.AP.paymentVoucher.paymentVoucher_pdfmake',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
 
        // }
    }

    public function get_CN_from_PV($apacthdr){
        $apalloc_PV = DB::table('finance.apalloc')
                    ->where('compcode',session('compcode'))
                    ->where('docsource',$apacthdr->source)
                    ->where('doctrantype',$apacthdr->trantype)
                    ->where('docauditno',$apacthdr->auditno);
        if(!$apalloc_PV->exists()){
            return 0 ;
        }

        $apalloc_PV = $apalloc_PV->first();
        $apalloc_CN = DB::table('finance.apalloc')
                    ->where('compcode',session('compcode'))
                    ->where('recstatus','POSTED')
                    ->where('refsource',$apalloc_PV->refsource)
                    ->where('reftrantype',$apalloc_PV->reftrantype)
                    ->where('refauditno',$apalloc_PV->refauditno)
                    ->where('docsource','AP')
                    ->where('doctrantype','CN');

        if(!$apalloc_CN->exists()){
            return 0 ;
        }

        $apalloc_CN = $apalloc_CN->first();
        $auditno = $apalloc_CN->docauditno;

        $apacthdr = DB::table('finance.apacthdr as h')
            ->select('h.compcode', 'h.auditno', 'h.trantype', 'h.source','h.doctype', 'h.suppcode', 'm.Name as suppname', 'm.Addr1 as addr1', 'm.Addr2 as addr2', 'm.Addr3 as addr3', 'm.TelNo as telno', 'm.TINNo', 'm.CompRegNo', 'm.AccNo', 'h.actdate', 'h.document', 'h.deptcode', 'h.amount', 'h.outamount', 'h.recstatus', 'h.payto', 'h.category', 'h.remarks', 'h.paymode', 'h.bankcode', 'h.cheqno','b.bankname', 'b.bankaccount as bankaccno')
            ->leftJoin('material.supplier as m', function($join){
                $join = $join->on('m.suppcode', '=', 'h.suppcode');
                $join = $join->where('m.compcode', '=', session('compcode'));
            })
            ->leftJoin('finance.bank as b', function($join){
                $join = $join->on('b.bankcode', '=', 'h.bankcode');
                $join = $join->where('b.compcode', '=', session('compcode'));
            })
            ->where('h.compcode', '=', session('compcode'))
            ->where('h.trantype','=', 'CN')
            ->where('h.auditno','=',$auditno)
            ->first();

        if ($apacthdr->recstatus == "OPEN") {
            $title = "DRAFT";
        } elseif ($apacthdr->recstatus == "POSTED"){
            $title = " CREDIT NOTE";
        }

        $apalloc = DB::table('finance.apalloc')
                    ->select('compcode','source','trantype', 'auditno', 'lineno_', 'docsource', 'doctrantype', 'docauditno', 'refsource', 'reftrantype', 'refauditno', 'refamount', 'allocdate', 'allocamount', 'recstatus', 'remarks', 'suppcode', 'reference', 'lastupdate' )
                    ->where('compcode','=', session('compcode'))
                    ->where('source','=', 'AP')
                    ->where('trantype','=', 'AL')
                    ->where('docsource','=', 'AP')
                    ->where('doctrantype','=', 'CN')
                    ->where('docauditno','=', $auditno)
                    ->where('recstatus','!=','CANCELLED')
                    ->where('recstatus','!=','DELETE')
                    ->get();

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
                    
        $totamount_expld = explode(".", (float)$apacthdr->amount);
   
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";

        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }

        $CN_obj = new stdClass();
        $CN_obj->apacthdr = $apacthdr;
        $CN_obj->apalloc = $apalloc;
        $CN_obj->totamt_eng = $totamt_eng;
        $CN_obj->company = $company;
        $CN_obj->title = $title;

        return $CN_obj;
    }

    public function link_pv(Request $request){

        switch ($request->type) {
            case 'do':
                $apdt = DB::table('finance.apactdtl as apdt')
                                ->where('apdt.compcode',session('compcode'))
                                ->where('apdt.auditno',$request->auditno)
                                ->where('apdt.recstatus','!=','DELETE')
                                ->where('apdt.recstatus','!=','CANCELLED')
                                ->where('apdt.source','AP');
                if(!$apdt->exists()){
                    abort(403,'No delivery Order');
                }

                $apdt = $apdt->first();
                $delordhd = DB::table('material.delordhd')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$apdt->dorecno);

                if(!$delordhd->exists()){
                    abort(403,'No delivery Order');
                }

                $delordhd = $delordhd->first();
                $recno = $delordhd->recno;
                return redirect('/deliveryOrder/showpdf?recno='.$recno);

                break;
            case 'po':
                $apdt = DB::table('finance.apactdtl as apdt')
                                ->where('apdt.compcode',session('compcode'))
                                ->where('apdt.auditno',$request->auditno)
                                ->where('apdt.recstatus','!=','DELETE')
                                ->where('apdt.recstatus','!=','CANCELLED')
                                ->where('apdt.source','AP');

                if(!$apdt->exists()){
                    abort(403,'No Delivery Order');
                }

                $apdt = $apdt->first();
                $delordhd = DB::table('material.delordhd')
                            // ->select('prdept','srcdocno')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$apdt->dorecno)
                            ->whereNotNull('srcdocno');

                if(!$delordhd->exists()){
                    abort(403,'No Delivery Order');
                }
                $delordhd = $delordhd->first();
                $purordhd = DB::table('material.purordhd')
                            ->where('compcode','=',session('compcode'))
                            ->where('prdept','=',$delordhd->prdept)
                            ->where('purordno','=',$delordhd->srcdocno);

                if(!$purordhd->exists()){
                    abort(403,'No Purchase Order');
                }
                $purordhd = $purordhd->first();
                $recno = $purordhd->recno;
                return redirect('/purchaseOrder/showpdf?recno='.$recno);

                break;

            case 'invoice':
                $aphr = DB::table('finance.apacthdr as aphr')
                                ->where('aphr.compcode',session('compcode'))
                                ->where('aphr.auditno',$request->auditno)
                                ->where('aphr.recstatus','!=','DELETE')
                                ->where('aphr.recstatus','!=','CANCELLED')
                                ->where('aphr.source','AP')
                                ->where('aphr.trantype','IN')
                                ->first();

                return redirect('/attachment_upload?page=invoiceap&idno='.$aphr->idno);
            
            default:
                // code...
                break;
        }
    }

    function get_attachment_files($apalloc){
        // $idno_array = [];
        $attachment_files = [];
        foreach ($apalloc as $obj) {
            $apacthdr = DB::table('finance.apacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','AP')
                            ->where('trantype','IN')
                            ->where('auditno',$obj->refauditno);

            if($apacthdr->exists()){
                $apacthdr = $apacthdr->first();

                $attachment_file = DB::table('finance.attachment')
                            ->where('compcode',session('compcode'))
                            ->where('page','invoiceap')
                            ->where('auditno',$apacthdr->idno)
                            ->first();

                if(!empty($attachment_file)){
                    array_push($attachment_files,$attachment_file);
                }
            }
        }

        // $attachment_files = DB::table('finance.attachment')
        //                 ->where('compcode',session('compcode'))
        //                 ->where('page','invoiceap')
        //                 ->whereIn('auditno',$idno_array)
        //                 ->get();
        // dd($attachment_files);

        return $attachment_files;
    }

}
