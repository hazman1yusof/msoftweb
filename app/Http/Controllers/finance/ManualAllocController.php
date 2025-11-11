<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

    class ManualAllocController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.manualAlloc.manualAlloc');
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_alloc_table_hdr':
                return $this->get_alloc_table_hdr($request);
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
                        'ap.doctype AS apacthdr_doctype',
                        'ap.suppcode AS apacthdr_suppcode',
                        'su.name AS supplier_name', 
                        'ap.actdate AS apacthdr_actdate',
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
                        'ap.bankcode AS apacthdr_bankcode'
                        
                    )
                    ->leftJoin('material.supplier as su', 'su.SuppCode', '=', 'ap.suppcode')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.recstatus','=', 'APPROVED')
                    ->where('ap.source','=','AP')
                    ->whereIn('ap.trantype',['PD','CN']);

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

    public function get_alloc_table_hdr(Request $request){
        $table = DB::table('finance.apacthdr AS ap')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.source','=',$request->source)
                    ->where('ap.trantype','=',$request->trantype)
                    ->where('ap.outamount','>',0);

        if($request->trantype=='PD'){
            $table = $table->where('ap.recstatus','APPROVED');
        }else if($request->trantype=='CN'){
            $table = $table->where('ap.recstatus','POSTED');
        }

        if(!empty($request->suppcode)){
            $table = $table->where('ap.suppcode',$request->suppcode);
        }

        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);
        
        return json_encode($responce);
    }

    public function form(Request $request){   
        // DB::enableQueryLog();
        if($request->action == 'manualAllocdtl_save'){
            return $this->manualAllocdtl_save($request);
        }

        switch($request->oper){
            case 'add':
                return $this->add($request);break;
            case 'edit':
                return $this->edit($request);break;
            case 'del':
                return $this->del($request);break;
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
            $ALauditno = $this->defaultSysparam('AP','AL');
            
            if ($request->apacthdr_trantype == 'PV'){

                $table = DB::table("finance.apacthdr");
            
                $array_insert = [
                    'source' => 'AP',
                    'auditno' => $auditno,
                    'trantype' => $request->apacthdr_trantype,
                    'actdate' => $request->apacthdr_actdate,
                    // 'recdate' => $request->apacthdr_actdate,
                    'pvno' => $request->apacthdr_pvno,
                    'doctype' => $request->apacthdr_doctype,
                    'document' => strtoupper($request->apacthdr_document),
                    'paymode' => $request->apacthdr_paymode,
                    'bankcode' => $request->apacthdr_bankcode,
                    'cheqno' => $request->apacthdr_cheqno,
                    'cheqdate' => $request->apacthdr_cheqdate,
                    'remarks' => strtoupper($request->apacthdr_remarks),
                    'suppcode' => $request->apacthdr_suppcode,
                    'payto' => $request->apacthdr_payto,
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    'amount' => $request->apacthdr_amount,
                ];

                $idno_apacthdr = $table->insertGetId($array_insert);

                foreach ($request->data_detail as $key => $value){

                    $apacthdr_IV = DB::table('finance.apacthdr')
                                ->where('idno','=',$value['idno'])
                                ->first();

                    $outamount = floatval($value['outamount']);
                    $allocamount = floatval($value['outamount']) - floatval($value['balance']);
                    $newoutamount_IV = floatval($outamount - $allocamount);

                    DB::table('finance.apalloc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'source' => 'AP',
                            'trantype' => 'AL',
                            'auditno' => $ALauditno,
                            'lineno_' => $key+1,
                            'docsource' => $request->apacthdr_source,
                            'doctrantype' => $request->apacthdr_trantype,
                            'docauditno' => $auditno,
                            'refsource' => $apacthdr_IV->source,
                            'reftrantype' => $apacthdr_IV->trantype,
                            'refauditno' => $apacthdr_IV->auditno,
                            'refamount' => $apacthdr_IV->amount,
                            // 'allocdate' => $request->apacthdr_actdate,//blank
                            'reference' => $value['reference'],
                            'allocamount' => $allocamount,
                            'outamount' => $outamount,
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
                    ->where('source','=','AP')
                    ->where('trantype','=','AL')
                    ->where('auditno','=',$ALauditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('allocamount');
                
                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_apacthdr)
                    ->update([
                        'amount' => $totalAmount,
                        // 'outamount' => '0',
                        'recstatus' => 'OPEN'
                    ]);

                $responce = new stdClass();
                $responce->auditno = $auditno;
                $responce->idno = $idno_apacthdr;
                $responce->totalAmount = $totalAmount;

                echo json_encode($responce);
            
            } else if ($request->apacthdr_trantype == 'PD'){
                $table = DB::table("finance.apacthdr");
            
                $array_insert = [
                    'source' => 'AP',
                    'auditno' => $auditno,
                    'trantype' => $request->apacthdr_trantype,
                    'actdate' => $request->apacthdr_actdate,
                    // 'recdate' => $request->apacthdr_actdate,
                    'pvno' => $request->apacthdr_pvno,
                    'doctype' => $request->apacthdr_doctype,
                    'document' => strtoupper($request->apacthdr_document),
                    'paymode' => $request->apacthdr_paymode,
                    'bankcode' => $request->apacthdr_bankcode,
                    'cheqno' => $request->apacthdr_cheqno,
                    'cheqdate' => $request->apacthdr_cheqdate,
                    'remarks' => strtoupper($request->apacthdr_remarks),
                    'recdate' => $request->apacthdr_recdate,
                    'suppcode' => $request->apacthdr_suppcode,
                    'payto' => $request->apacthdr_payto,
                    'amount' => $request->apacthdr_amount,
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN'
                ];

                $responce = new stdClass();
                $responce->auditno = $auditno;
                $responce->idno = $idno_apacthdr;

                echo json_encode($responce);
            }

            DB::commit();  
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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
          
        if ($request->apacthdr_trantype == 'PV'){
            
            DB::beginTransaction();

            $table = DB::table("finance.apacthdr");

            $array_update = [
                'unit' => session('unit'),
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'pvno' => $request->apacthdr_pvno,
                'doctype' => $request->apacthdr_doctype,
                'recdate' => $request->apacthdr_recdate,
                'suppcode' => strtoupper($request->apacthdr_suppcode),
                'document' => strtoupper($request->apacthdr_document),
                'paymode' => strtoupper($request->apacthdr_paymode),
                'bankcode' => strtoupper($request->apacthdr_bankcode),
                'cheqno' => strtoupper($request->apacthdr_cheqno),
                'remarks' => strtoupper($request->apacthdr_remarks),
                
            ];

            try {

                // $idno = $table->insertGetId($array_insert);
                // foreach ($request->data_detail as $key => $value) {
                //     $idno = $value['idno'];

                    $apacthdr_IV = DB::table('finance.apacthdr')
                                    ->where('idno','=',$idno)
                                    ->first();

                    DB::table('finance.apalloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('auditno','=',$request->auditno)
                            ->where('lineno_','=',$request->lineno_)
                            ->update([
                                
                                // 'source' => 'AP',
                                // 'trantype' => 'PV',
                                // 'lineno_' => $key+1,
                                // 'docsource' => 'AP',
                                // 'doctrantype' => 'PV',
                                'docauditno' => $request->auditno,
                                'refsource' => $request->source,
                                'reftrantype' => $request->trantype,
                            // 'refauditno' => $apacthdr_IV->auditno,
                                'refamount' => $request->amount,
                            //  'allocdate' => $this->turn_date($value['allocdate']),
                                'reference' => $request->reference,
                                'allocamount' => floatval($request['outamount']) - floatval($request['balance']),
                                'outamount' => floatval($request['outamount']),
                                'paymode' => $request->apacthdr_paymode,
                                'bankcode' => $request->apacthdr_bankcode,
                                'suppcode' => $request->apacthdr_suppcode,
                                'lastuser' => session('username'),
                                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'recstatus' => 'OPEN'
                            ]);
                //}

                //calculate total amount from detail
                $totalAmount = DB::table('finance.apalloc')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('allocamount');
    
    
                //then update to header
                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$apacthdr_IV->auditno)
                    ->where('source','=', 'AP')
                    ->where('trantype','=', 'PV')
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => '0',
                        'recstatus' => 'OPEN'
                    
                    ]);
                
                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$auditno)
                    ->where('source','=', 'AP')
                    ->where('trantype','=', 'PV')
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => '0',
                        'recstatus' => 'OPEN'
                    
                    ]);
    
                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$auditno)
                    ->update([
                        'outamount' => $value['outamount'] - $value['allocamount']
                    ]);    

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response('Error'.$e, 500);
            }

        } else {

            DB::beginTransaction();

            $table = DB::table("finance.apacthdr");

            $array_update = [
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'pvno' => $request->apacthdr_pvno,
                // 'doctype' => $request->apacthdr_doctype,
                'actdate' => $request->apacthdr_actdate,
                'recdate' => $request->apacthdr_recdate,
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

                return response($e, 500);
            }
        }
    }

    public function posted(Request $request){
        DB::beginTransaction();
        try {


            foreach ($request->idno_array as $idno_obj){
                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->first();

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_obj['idno'])
                    ->update([
                        'recdate' => $idno_obj['date'],
                        'recstatus' => 'POSTED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                $this->gltran($idno_obj['idno']);

                $apalloc = DB::table('finance.apalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('docsource','=', $apacthdr->source)
                    ->where('doctrantype','=', $apacthdr->trantype)
                    ->where('docauditno','=', $apacthdr->auditno)
                    ->update([
                        'allocdate' => $idno_obj['date'],
                        'recstatus' => 'POSTED',
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function cancel(Request $request){

        DB::beginTransaction();

        try {

            $apacthdr = DB::table('finance.apacthdr')
                ->where('idno','=', $request->idno)
                ->first();

            if($apacthdr->recstatus == 'OPEN'){

                $apalloc = DB::table('finance.apalloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('unit','=',session('unit'))
                            ->where('docsource','=',$apacthdr->source)
                            ->where('doctrantype','=',$apacthdr->trantype)
                            ->where('docauditno','=',$apacthdr->auditno)
                            ->where('recstatus','!=','CANCELLED')
                            ->get();

                $sum_allocamount = 0;

                foreach($apalloc  as $value){
                    $value = (array)$value;
                    $sum_allocamount = $sum_allocamount + $value['allocamount'];

                    $refapacthdr = DB::table('finance.apacthdr')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('unit','=',session('unit'))
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
                    ->where('idno','=',$request->idno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'amount' => $apacthdr->amount - $sum_allocamount,
                    ]);

            }else if($apacthdr->recstatus == 'POSTED'){
                DB::table('finance.apacthdr')
                    ->where('idno','=', $request->idno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                $this->gltran_cancel($request->idno);

                $totalAmount = DB::table('finance.apalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('docsource','=',$apacthdr->source)
                    ->where('doctrantype','=',$apacthdr->trantype)
                    ->where('docauditno','=',$apacthdr->auditno)
                    ->where('recstatus','!=','CANCELLED')
                    ->sum('allocamount');
                
                DB::table('finance.apacthdr')
                    ->where('idno','=',$request->idno)
                    ->update([
                        'amount' => $apacthdr->amount - $totalAmount,
                        // 'outamount' => '0',
                        'recstatus' => 'CANCELLED'
                    ]);

                DB::table('finance.apalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
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
               
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 500);
        }           
    }

    public function manualAllocdtl_save(Request $request){
        DB::beginTransaction();

        try {
            if($request->allocamount <= 0){
                throw new \Exception('Allocamount need to be greater 0', 500);
            }

            $doc_ap = DB::table('finance.apacthdr')
                            ->where('compcode',session('compcode'))
                            // ->where('source','AP')
                            ->where('idno',$request->idno_doc)
                            ->first();

            $outamount_doc = $doc_ap->outamount;
            if($request->allocamount > $outamount_doc){
                throw new \Exception('Allocamount greater than document outamount', 500);
            }
            $newoutamount_doc = floatval($outamount_doc - $request->allocamount);

            DB::table('finance.apacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('idno','=',$request->idno_doc)
                        ->update([
                            'outamount' => $newoutamount_doc
                        ]);

            $ref_ap = DB::table('finance.apacthdr')
                            ->where('compcode',session('compcode'))
                            // ->where('source','AP')
                            ->where('idno',$request->idno)
                            ->first();

            $outamount_ref = $ref_ap->outamount;
            if($request->allocamount > $outamount_ref){
                throw new \Exception('Allocamount greater than document outamount', 500);
            }
            $newoutamount_ref = floatval($outamount_ref - $request->allocamount);

            DB::table('finance.apacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('idno','=',$request->idno)
                        ->update([
                            'outamount' => $newoutamount_ref
                        ]);

            $lineno_ = DB::table('finance.apalloc') 
                ->where('compcode','=',session('compcode'))
                ->where('docauditno','=',$doc_ap->auditno)
                ->where('docsource','=',$doc_ap->source)
                ->where('doctrantype','=',$doc_ap->trantype)->max('lineno_');

            if($lineno_ == null){
                $lineno_ = 1;
            }else{
                $lineno_ = $lineno_+1;
            }

            $ALauditno = $this->defaultSysparam('AP','AL');
            DB::table('finance.apalloc')
                ->insert([
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'source' => 'AP',
                    'trantype' => 'AL',
                    'auditno' => $ALauditno,
                    'lineno_' => $lineno_,
                    'docsource' => $doc_ap->source,
                    'doctrantype' => $doc_ap->trantype,
                    'docauditno' => $doc_ap->auditno,
                    'refsource' => $ref_ap->source,
                    'reftrantype' => $ref_ap->trantype,
                    'refauditno' => $ref_ap->auditno,
                    'refamount' => $ref_ap->amount,
                    'allocdate' => $this->turn_date($request->allocdate,'d/m/Y'),//blank
                    'reference' => $ref_ap->document,
                    'remarks' => 'manual allocation - '.$ref_ap->document,
                    'allocamount' => $request->allocamount,
                    'outamount' => $request->outamount,
                    'balance' => $request->balance,
                    'paymode' => $doc_ap->paymode,
                    'cheqdate' => $doc_ap->cheqdate,
                    // 'recdate' => $doc_ap->recdate,
                    'bankcode' => $doc_ap->bankcode,
                    'suppcode' => $doc_ap->suppcode,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'POSTED'
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function get_alloc_table(Request $request){
        $table = DB::table('finance.apacthdr AS ap')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.suppcode',$request->suppcode)
                    ->where('ap.source','=','AP')
                    ->where('ap.outamount','>',0)
                    ->where('ap.recstatus','=','POSTED')
                    ->whereIn('ap.trantype',['IN','DN']);

        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);
        
        return json_encode($responce);
    }

    public function gltran($idno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->recdate);

        $credit_obj = $this->gltran_frombank($apacthdr_obj->bankcode);
        $debit_obj = $this->gltran_fromsupp($apacthdr_obj->suppcode);

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
                'postdate' => $apacthdr_obj->recdate,
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
    }

    public function gltran_cancel($idno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('idno','=',$idno)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->recdate);

        $credit_obj = $this->gltran_frombank($apacthdr_obj->bankcode);
        $debit_obj = $this->gltran_fromsupp($apacthdr_obj->suppcode);

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

    public function gltran_fromsupp($suppcode){

        $obj = DB::table("material.supplier")
                ->select('costcode','glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('suppcode','=',$suppcode)
                ->first();

        return $obj;
    }

    public function showpdf(Request $request){
        $auditno = $request->auditno;
        if(!$auditno){
            abort(404);
        }

        $apacthdr = DB::table('finance.apacthdr as h', 'material.supplier as m', 'finance.bank as b')
            ->select('h.compcode', 'h.auditno', 'h.trantype', 'h.source','h.doctype', 'h.pvno', 'h.suppcode', 'm.Name as suppname', 'h.actdate', 'h.document', 'h.deptcode', 'h.amount', 'h.outamount', 'h.recstatus', 'h.payto', 'h.category', 'h.remarks', 'h.paymode', 'h.bankcode', 'h.cheqno','b.bankname', 'b.bankaccount as bankaccno')
            ->leftJoin('material.supplier as m', 'h.suppcode', '=', 'm.suppcode')
            ->leftJoin('finance.bank as b', 'h.bankcode', '=', 'b.bankcode')
            ->where('auditno','=',$auditno)
            ->first();

        if ($apacthdr->recstatus == "OPEN") {
            $title = "DRAFT";
        } elseif ($apacthdr->recstatus == "POSTED"){
            $title = " PAYMENT VOUCHER";
        }

        $apalloc = DB::table('finance.apalloc')
            ->select('compcode','source','trantype', 'auditno', 'lineno_', 'docsource', 'doctrantype', 'docauditno', 'refsource', 'reftrantype', 'refauditno', 'refamount', 'allocdate', 'allocamount', 'recstatus', 'remarks', 'suppcode', 'reference' )

            ->where('auditno','=',$auditno)
            ->get();


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

        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])." RINGGIT ";
        $totamt_eng = $totamt_eng_rm." ONLY";

        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }

        $pdf = PDF::loadView('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        return $pdf->stream();      

        
        return view('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
    }

}
