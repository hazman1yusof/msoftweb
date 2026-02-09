<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class CreditNoteController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.creditNote.creditNote');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);break;
            case 'edit':
                return $this->edit($request);break;
            case 'del':
                return $this->del($request);break;
            case 'save_alloc':
                return $this->save_alloc($request);break;
            case 'del_alloc':
                return $this->del_alloc($request);break;
            case 'posted_single':
                return $this->posted_single($request);break;
            case 'posted':
                return $this->posted($request);break;
            case 'cancel':
                return $this->cancel($request);break;
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

        $table = DB::table('finance.apacthdr AS ap')
                    ->select(
                        'ap.compcode AS apacthdr_compcode',
                        'ap.auditno AS apacthdr_auditno',
                        'ap.trantype AS apacthdr_trantype',
                        'ap.unallocated AS apacthdr_unallocated',
                        'ap.doctype AS apacthdr_doctype',
                        'ap.suppcode AS apacthdr_suppcode',
                        'su.name AS supplier_name', 
                        'ap.actdate AS apacthdr_actdate',
                        'ap.postdate AS apacthdr_postdate',
                        'ap.postuser AS apacthdr_postuser',
                        'ap.document AS apacthdr_document',
                        'ap.cheqno AS apacthdr_cheqno',
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
                        'ap.pvno AS apacthdr_pvno',
                        'ap.paymode AS apacthdr_paymode',
                        'ap.bankcode AS apacthdr_bankcode',
                    )
                    ->leftJoin('material.supplier as su', 'su.SuppCode', '=', 'ap.suppcode')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.source','=',$request->source)
                    ->where('ap.trantype', '=','CN');

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->filterdate)){
            $table = $table->where('ap.actdate','>=',$request->filterdate[0]);
            $table = $table->where('ap.actdate','<=',$request->filterdate[1]);
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

            $apalloc = DB::table('finance.apalloc')
                        ->where('docsource','=',$value->apacthdr_source)
                        ->where('doctrantype','=',$value->apacthdr_trantype)
                        ->where('docauditno','=',$value->apacthdr_auditno);

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

    public function get_alloc_when_edit(Request $request){

        $apacthdr = DB::table('finance.apacthdr')
                    ->where('suppcode',$request->filterVal[0])
                    ->where('compcode',session('compcode'))
                    ->where('recstatus','=','POSTED')
                    ->where('postdate','<=',$request->postdate)
                    ->where('outamount','>',0)
                    ->where('source', ['AP','DF','CF','TX'])
                    ->whereIn('trantype', ['IN','DN']);

        $apalloc = DB::table('finance.apalloc')
                    ->where('compcode',session('compcode'))
                    ->where('docsource','AP')
                    ->where('doctrantype','CN')
                    ->where('docauditno',$request->auditno)
                    ->where('recstatus','!=','CANCELLED');

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
                            $obj_apacthdr->amount = $obj_apalloc->allocamount;
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

    public function get_alloc_table(Request $request){
        
        $table = DB::table('finance.apalloc as al')
                        ->select(
                            'al.refauditno as auditno',
                            'al.reftrantype as trantype',
                            'al.refsource as source',
                            'al.lineno_',
                            'al.idno',
                            'al.suppcode',
                            'al.allocdate as allocdate',
                            'al.reference',
                            'al.refamount',
                            'al.outamount',
                            'al.allocamount',
                            'al.balance',
                            'al.doctrantype',
                            'al.docsource',
                            'al.docauditno',
                            'al.recstatus',
                            'ap.actdate as actdate',
                            'ap.postdate as postdate',
                            'ap.recdate',
                            'ap.document',
                            'ap.recstatus',
                            'ap.amount',
                        )
                        ->join('finance.apacthdr as ap', function($join) use ($request){
                                    $join = $join->on('al.docsource', '=', 'ap.source')
                                        ->on('al.doctrantype', '=', 'ap.trantype')
                                        ->on('al.docauditno', '=', 'ap.auditno');
                        })
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.docsource','=',$request->source)
                        ->where('al.doctrantype','=',$request->trantype)
                        ->where('al.docauditno','=',$request->auditno)
                        ->where('al.recstatus','=','POSTED')
                        ->orderBy('al.idno','DESC');
        
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

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            //$idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            //$idno = $request->table_id;
        }

        DB::beginTransaction();
        try {

            $apacthdr = DB::table('finance.apacthdr')
                        ->where('idno','=',$request->idno)
                        ->first();

            // if($apacthdr->unallocated == '1') {
            //     $this->check_alloc_exists($request);
            //     $this->checkduplicate_docno('add', $request);

            //     $auditno = $this->defaultSysparam($request->apacthdr_source,'CN');
            //     $suppgroup = $this->suppgroup($request->apacthdr_suppcode);

            //     $table = DB::table("finance.apacthdr");

            //     $array_insert = [
            //         'source' => 'AP',
            //         'auditno' => $auditno,
            //         'trantype' => 'CN',
            //         'unallocated' => $request->apacthdr_unallocated,
            //         'actdate' => $request->apacthdr_actdate,
            //         'recdate' => $request->apacthdr_postdate,
            //         'postdate' => $request->apacthdr_postdate,
            //         'pvno' => $request->apacthdr_pvno,
            //         'doctype' => $request->apacthdr_doctype,
            //         'document' => strtoupper($request->apacthdr_document),
            //         'remarks' => strtoupper($request->apacthdr_remarks),
            //         'suppcode' => $request->apacthdr_suppcode,
            //         'payto' => $request->apacthdr_payto,
            //         'amount' => $request->apacthdr_amount,
            //         'outamount' => $request->apacthdr_amount,
            //         'suppgroup' => $suppgroup,
            //         'compcode' => session('compcode'),
            //         'unit' => session('unit'),
            //         'adduser' => session('username'),
            //         'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //         'entryuser' => session('username'),
            //         'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
            //         'recstatus' => 'OPEN'
            //     ];

            // } else if($apacthdr->unallocated == '0'){

                $this->checkduplicate_docno('add', $request);

                $auditno = $this->defaultSysparam($request->apacthdr_source,'CN');
                $suppgroup = $this->suppgroup($request->apacthdr_suppcode);

                $table = DB::table("finance.apacthdr");

                $array_insert = [
                    'source' => 'AP',
                    'auditno' => $auditno,
                    'trantype' => 'CN',
                    'unallocated' => $request->apacthdr_unallocated,
                    'actdate' => $request->apacthdr_actdate,
                    'recdate' => $request->apacthdr_postdate,
                    'postdate' => $request->apacthdr_postdate,
                    'pvno' => $request->apacthdr_pvno,
                    'doctype' => $request->apacthdr_doctype,
                    'document' => strtoupper($request->apacthdr_document),
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
                    'entryuser' => session('username'),
                    'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN'
                ];
            // }
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

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        DB::beginTransaction();

        try {
            $apacthdr = DB::table('finance.apacthdr')
                        ->where('idno','=',$request->idno)
                        ->first();

            if($apacthdr->unallocated == '1') {

                $this->check_alloc_exists($request);
                $this->checkduplicate_docno('edit', $request);
                $table = DB::table("finance.apacthdr");

                $array_update = [
                    'unit' => session('unit'),
                    'compcode' => session('compcode'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'postdate' => $request->apacthdr_postdate,
                    'actdate' => $request->apacthdr_actdate,
                    'recdate' => $request->apacthdr_postdate,
                    'unallocated' => $request->apacthdr_unallocated,
                    'suppcode' => $request->apacthdr_suppcode,
                    'payto' => $request->apacthdr_payto,
                    // 'amount' => $request->apacthdr_amount,
                    // 'outamount' => $request->apacthdr_amount,
                    'remarks' => $request->apacthdr_remarks,
                    'pvno' => $request->apacthdr_pvno,
                    'doctype' => $request->apacthdr_doctype,
                    'suppcode' => strtoupper($request->apacthdr_suppcode),
                    'deptcode' => strtoupper($request->apacthdr_deptcode),
                    'document' => strtoupper($request->apacthdr_document),
                    'paymode' => strtoupper($request->apacthdr_paymode),
                    'remarks' => strtoupper($request->apacthdr_remarks),
                    
                ];

            } else if($apacthdr->unallocated == '0'){
                
                $this->checkduplicate_docno('edit', $request);
                $table = DB::table("finance.apacthdr");

                $array_update = [
                    'unit' => session('unit'),
                    'compcode' => session('compcode'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'postdate' => $request->apacthdr_postdate,
                    'actdate' => $request->apacthdr_actdate,
                    'recdate' => $request->apacthdr_postdate,
                    'unallocated' => $request->apacthdr_unallocated,
                    'suppcode' => $request->apacthdr_suppcode,
                    'payto' => $request->apacthdr_payto,
                    // 'amount' => $request->apacthdr_amount,
                    // 'outamount' => $request->apacthdr_amount,
                    'remarks' => $request->apacthdr_remarks,
                    'pvno' => $request->apacthdr_pvno,
                    'doctype' => $request->apacthdr_doctype,
                    'suppcode' => strtoupper($request->apacthdr_suppcode),
                    'deptcode' => strtoupper($request->apacthdr_deptcode),
                    'document' => strtoupper($request->apacthdr_document),
                    'paymode' => strtoupper($request->apacthdr_paymode),
                    'remarks' => strtoupper($request->apacthdr_remarks),
                    
                ];
            }

            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            DB::commit();

            $responce = new stdClass();
            $responce->auditno = $request->apacthdr_auditno;
            $responce->idno = $request->idno;
            echo json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        } 
    }

    public function save_alloc(Request $request){
        DB::beginTransaction();
        try {

            //from CN
            $apacthdr = DB::table('finance.apacthdr')
                ->where('idno','=',$request->idno)
                ->first();

            foreach ($request->data_detail as $key => $value){
                $auditno_al = $this->defaultSysparam('AP','AL');

                //from DN
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
                            ->where('docauditno','=',$apacthdr->auditno)
                            ->where('docsource','=','AP')
                            ->where('doctrantype','=','CN')->max('lineno_');

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
                            'auditno' => $auditno_al,
                            'lineno_' => $lineno_,
                            'docsource' => $apacthdr->source,
                            'doctrantype' => $apacthdr->trantype,
                            'docauditno' => $apacthdr->auditno,
                            'refsource' => $apacthdr_IV->source,
                            'reftrantype' => $apacthdr_IV->trantype,
                            'refauditno' => $apacthdr_IV->auditno,
                            'refamount' => $apacthdr_IV->amount,
                            'allocdate' => $apacthdr->actdate,
                            'reference' => $value['reference'],
                            'allocamount' => $allocamount,
                            'outamount' => $outamount,
                            'balance' => $balance,
                            'paymode' => $apacthdr->paymode,
                            'suppcode' => $apacthdr->suppcode,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'POSTED'
                        ]);

                $apacthdr_IV = DB::table('finance.apacthdr')
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'outamount' => $newoutamount_IV
                    ]);
            }

            //calculate total allocamount
            $totalAlloc = DB::table('finance.apalloc')
                ->where('compcode','=',session('compcode'))
                ->where('docauditno','=',$apacthdr->auditno)
                ->where('docsource','=','AP')
                ->where('doctrantype','=','CN')
                ->where('recstatus','=','POSTED')
                ->sum('allocamount');
            
            //calculate total amount from detail
            $totalDtl = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$apacthdr->auditno)
                ->where('source','=','AP')
                ->where('trantype','=','CN')
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            $newoutamt = floatval($totalDtl - $totalAlloc);

            //then update to header
            DB::table('finance.apacthdr')
                ->where('idno','=',$request->idno)
                ->update([
                    'outamount' => $newoutamt
                ]);

            if($newoutamt < 0){
                throw new \Exception('Total allocation amount exceed total amount', 500);
            }
            
            DB::commit();

            $responce = new stdClass();
            $responce->result = 'success';

            return json_encode($responce);
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
                
            $allocamt = floatval($apalloc->allocamount);
            $balance = floatval($apalloc->balance);
            $curr_outamthdr = floatval($allocamt + $balance);

            //update alloc amount asal IN/DN
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$apalloc->refauditno)
                ->where('source','=',$apalloc->refsource)
                ->where('trantype','=',$apalloc->reftrantype)
                ->update([
                    'outamount' => $curr_outamthdr
                ]);
            
            //update status
            DB::table('finance.apalloc')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'recstatus' => 'CANCELLED',
                    ]);

            $apacthdr_outamount = floatVal($request->apacthdr_outamount);
            $newoutamthdr = floatval($apacthdr_outamount + $allocamt);

            //then update to header CN
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$apalloc->docsource)
                ->where('trantype','=',$apalloc->doctrantype)
                ->where('auditno','=',$apalloc->docauditno)
                ->update([
                    'outamount' => $newoutamthdr
                ]);
                
            DB::commit();

            $responce = new stdClass();
            $responce->newoutamthdr = $newoutamthdr;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function posted_single(Request $request){
        DB::beginTransaction();
        try {

            $apacthdr = DB::table('finance.apacthdr')
                ->where('idno','=',$request->idno)
                ->first();

            if($apacthdr->amount == 0){
                throw new \Exception('Credit Note auditno: '.$apacthdr->auditno.' amount cant be zero', 500);
            }

            $yearperiod = defaultController::getyearperiod_($apacthdr->postdate);
            if($yearperiod->status == 'C'){
                throw new \Exception('Credit Note auditno: '.$apacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
            }

            $this->gltran($request->idno);

            DB::table('finance.apacthdr')
                ->where('idno','=',$request->idno)
                ->update([
                    'recdate' => $apacthdr->postdate,
                    'recstatus' => 'POSTED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'postuser' => session('username')
                ]);

            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('unit','=',session('unit'))
                ->where('source','=', $apacthdr->source)
                ->where('trantype','=', $apacthdr->trantype)
                ->where('auditno','=', $apacthdr->auditno)
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

    public function posted(Request $request){
        DB::beginTransaction();
        try {


            foreach ($request->idno_array as $idno){

                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno)
                    ->first();

                if($apacthdr->recstatus != 'OPEN'){
                    continue;
                }
                
                if($apacthdr->amount == 0){
                    throw new \Exception('Credit Note auditno: '.$apacthdr->auditno.' amount cant be zero', 500);
                }

                $yearperiod = defaultController::getyearperiod_($apacthdr->postdate);
                if($yearperiod->status == 'C'){
                    throw new \Exception('Credit Note auditno: '.$apacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
                }

                $this->gltran($idno);

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno)
                    ->update([
                        'recdate' => $apacthdr->postdate,
                        'recstatus' => 'POSTED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'postuser' => session('username')
                    ]);

                DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('source','=', $apacthdr->source)
                    ->where('trantype','=', $apacthdr->trantype)
                    ->where('auditno','=', $apacthdr->auditno)
                    ->update([
                        'recstatus' => 'POSTED'
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
            $apacthdr = DB::table('finance.apacthdr')
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=',session('compcode'));


            if($apacthdr->recstatus = 'POSTED'){

                $this->gltran_cancel($request->idno);
                $apacthdr
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'CANCELLED' 
                    ]);

            }else{

                $apacthdr
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

    public function gltran($idno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        $apactdtl_obj = DB::table('finance.apactdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$apacthdr_obj->source)
                            ->where('trantype','=',$apacthdr_obj->trantype)
                            ->where('auditno','=',$apacthdr_obj->auditno);

        if($apactdtl_obj->exists()){
            $apactdtl_get = $apactdtl_obj->get();

            foreach ($apactdtl_get as $key => $value){
                $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate); //check postdate period 

                $category_obj = $this->gltran_fromcategory($value->category);
                $dept_obj = $this->gltran_fromdept($value->deptcode);
                $supp_obj = $this->gltran_fromsupp($apacthdr_obj->payto);

                //1. buat gltran
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => $apacthdr_obj->compcode,
                        'auditno' => $apacthdr_obj->auditno,
                        'lineno_' => $key+1,
                        'source' => $apacthdr_obj->source,
                        'trantype' => $apacthdr_obj->trantype,
                        'reference' => $value->document,
                        'description' => $apacthdr_obj->remarks,
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $supp_obj->costcode,
                        'dracc' => $supp_obj->glaccno,
                        'crcostcode' => $dept_obj->costcode,
                        'cracc' => $category_obj->expacct,
                        'amount' => $value->amount,
                        'postdate' => $apacthdr_obj->postdate,
                        'adduser' => $apacthdr_obj->adduser,
                        'adddate' => $apacthdr_obj->adddate,
                        'idno' => null
                    ]);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                $gltranAmount =  defaultController::isGltranExist_($supp_obj->costcode,$supp_obj->glaccno,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$supp_obj->costcode)
                        ->where('glaccount','=',$supp_obj->glaccno)
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
                            'costcode' => $supp_obj->costcode,
                            'glaccount' => $supp_obj->glaccno,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($dept_obj->costcode,$category_obj->expacct,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$dept_obj->costcode)
                        ->where('glaccount','=',$category_obj->expacct)
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
                            'glaccount' => $category_obj->expacct,
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
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        $apactdtl_obj = DB::table('finance.apactdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$apacthdr_obj->source)
                            ->where('trantype','=',$apacthdr_obj->trantype)
                            ->where('auditno','=',$apacthdr_obj->auditno);

        if($apactdtl_obj->exists()){
            $apactdtl_get = $apactdtl_obj->get();

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$apacthdr_obj->source)
                ->where('trantype','=',$apacthdr_obj->trantype)
                ->where('auditno','=',$apacthdr_obj->auditno)
                ->delete();

            foreach ($apactdtl_get as $key => $value){
                $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate);

                $category_obj = $this->gltran_fromcategory($value->category);
                $dept_obj = $this->gltran_fromdept($value->deptcode);
                $supp_obj = $this->gltran_fromsupp($apacthdr_obj->payto);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                $gltranAmount =  defaultController::isGltranExist_($supp_obj->costcode,$supp_obj->glaccno,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$supp_obj->costcode)
                        ->where('glaccount','=',$supp_obj->glaccno)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $gltranAmount - $value->amount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($dept_obj->costcode,$category_obj->expacct,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$dept_obj->costcode)
                        ->where('glaccount','=',$category_obj->expacct)
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

    public function gltran_fromdept($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        return $obj;
    }

    public function gltran_fromcategory($category){
        $obj = DB::table('material.category')
                ->select('expacct')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CR')
                ->where('catcode','=',$category)
                ->first();

        return $obj;
    }

    public function gltran_fromsupp($suppcode){

        $obj = DB::table("material.supplier")
                ->select('costcode','glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('suppcode','=',$suppcode)
                ->first();

        return $obj;
    }

    public function checkduplicate_docno($oper,$request){

        if($oper == 'edit'){
            $obj = DB::table("finance.apacthdr")
                ->where('compcode','=',session('compcode'))
                ->where('document','=',$request->apacthdr_document)
                ->where('trantype','=','CN')
                ->where('recstatus','!=','CANCELLED')
                ->where('idno','!=',$request->idno);
        }else{
            $obj = DB::table("finance.apacthdr")
                ->where('compcode','=',session('compcode'))
                ->where('document','=',$request->apacthdr_document)
                ->where('trantype','=','CN')
                ->where('recstatus','!=','CANCELLED');
        }

        if($obj->exists()){
            throw new \Exception('Duplicate DO No', 500);
        }
    }

    public function check_alloc_exists(Request $request){

        $apacthdr = DB::table('finance.apacthdr')
                ->where('suppcode',$request->apacthdr_suppcode)
                ->where('compcode',session('compcode'))
                ->where('recstatus','=','POSTED')
                ->where('postdate','<=',$request->apacthdr_postdate)
                ->where('outamount','>',0)
                ->where('source', ['AP','DF','CF','TX'])
                ->whereIn('trantype', ['IN','DN']);

        if(!$apacthdr->exists()){
            throw new \Exception('This supplier doesnt have any invoice until postdate: '.Carbon::parse($request->apacthdr_postdate)->format('d-m-Y'), 500);
        }
    }

    public function showpdf(Request $request){
        $auditno = $request->auditno;
        if(!$auditno){
            abort(404);
        }

        $apacthdr = DB::table('finance.apacthdr as h')
            ->select('h.compcode', 'h.auditno', 'h.trantype', 'h.source','h.doctype', 'h.suppcode', 'm.Name as suppname', 'm.Addr1 as addr1', 'm.Addr2 as addr2', 'm.Addr3 as addr3', 'm.TelNo as telno', 'm.TINNo', 'm.CompRegNo', 'm.AccNo', 'h.actdate', 'h.document', 'h.deptcode', 'h.amount', 'h.outamount', 'h.recstatus', 'h.payto', 'h.category', 'h.remarks', 'h.paymode', 'h.bankcode', 'h.cheqno','b.bankname', 'b.bankaccount as bankaccno')
            ->leftJoin('material.supplier as m', function($join) use ($request){
                $join = $join->on('m.suppcode', '=', 'h.suppcode');
                $join = $join->where('m.compcode', '=', session('compcode'));
            })
            ->leftJoin('finance.bank as b', function($join) use ($request){
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
                    ->select('compcode','source','trantype', 'auditno', 'lineno_', 'docsource', 'doctrantype', 'docauditno', 'refsource', 'reftrantype', 'refauditno', 'refamount', 'allocdate', 'allocamount', 'recstatus', 'remarks', 'suppcode', 'reference' )
                    ->where('compcode','=', session('compcode'))
                    ->where('source','=', 'AP')
                    ->where('trantype','=', 'AL')
                    ->where('docsource','=', 'AP')
                    ->where('doctrantype','=', 'CN')
                    ->where('docauditno','=', $auditno)
                    ->where('recstatus','!=','CANCELLED')
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

        return view('finance.AP.creditNote.creditNote_pdfmake',compact('apacthdr','apalloc','totamt_eng','company', 'title'));

    }
}
