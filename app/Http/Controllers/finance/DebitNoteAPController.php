<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

    class DebitNoteAPController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.debitNoteAP.debitNoteAP');
    }

    public function table(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_alloc_table':
                return $this->get_alloc_table($request);
            default:
                return 'error happen..';
        }

        DB::insert(
            DB::raw("
                CREATE TEMPORARY TABLE table_temp_a 
                    AS (
                        SELECT *
                        FROM material.delordhd 
                        WHERE compcode = '".session('compcode')."'
                        AND suppcode = '$request->suppcode'
                        AND recstatus = 'POSTED'
                        AND invoiceno IS NULL
                    );
            ")
        );

        //grt negative, buat temp table sebab nak negative kan ni
        DB::update("update table_temp_a set totamount = 0-totamount where trantype = 'GRT'");

        $table = DB::select("
                    SELECT table_temp_a.delordno as delordno,
                        SUM(table_temp_a.totamount) as amount,
                        max(delordhd.docno) as docno,
                        max(delordhd.deliverydate) as deliverydate,
                        max(delordhd.srcdocno) as srcdocno,
                        max(delordhd.taxclaimable) as taxclaimable,
                        max(delordhd.TaxAmt) as TaxAmt,
                        max(delordhd.recno) as recno,
                        max(delordhd.suppcode) as suppcode
                    FROM table_temp_a
                    LEFT JOIN material.delordhd on delordhd.delordno = table_temp_a.delordno 
                    AND delordhd.trantype = 'GRN' 
                    AND delordhd.suppcode = '$request->suppcode' 
                    AND delordhd.recstatus = 'POSTED'
                    GROUP BY table_temp_a.delordno
                ");

        $chunk = collect($table)->forPage($request->page,$request->rows);

        $responce = new stdClass();
        // $responce->page = $paginate->currentPage();
        // $responce->total = $paginate->lastPage();
        // $responce->records = $paginate->total();
        $responce->rows = $chunk;
        // $responce->sql = $table->toSql();
        // $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
        
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
                        'ap.postdate AS apacthdr_postdate',
                        'ap.document AS apacthdr_document',
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
                        'ap.postuser AS apacthdr_postuser'
                    )
                    ->leftJoin('material.supplier as su', 'su.SuppCode', '=', 'ap.suppcode')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.source','=', $request->source)
                    ->where('ap.trantype','=', $request->trantype);

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
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$value->apacthdr_source)
                        ->where('trantype','=',$value->apacthdr_trantype)
                        ->where('auditno','=',$value->apacthdr_auditno);

            if($apactdtl->exists()){
                $value->apactdtl_outamt = $apactdtl->sum('amount');
            }else{
                $value->apactdtl_outamt = $value->apacthdr_outamount;
            }

            $apalloc = DB::table('finance.apalloc')
                        ->select('allocdate')
                        ->where('compcode','=',session('compcode'))
                        ->where('refsource','=',$value->apacthdr_source)
                        ->where('reftrantype','=',$value->apacthdr_trantype)
                        ->where('refauditno','=',$value->apacthdr_auditno)
                        ->where('recstatus','!=','CANCELLED')
                        ->orderBy('idno', 'desc');

            if($apalloc->exists()){
                $value->apalloc_allocdate = $apalloc->first()->allocdate;
            }else{
                $value->apalloc_allocdate = '';
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

    public function get_alloc_table(Request $request){
        
        $table = DB::table('finance.apalloc as al')
                        ->select(
                            'al.docauditno as auditno',
                            'al.doctrantype as trantype',
                            'al.docsource as source',
                            'al.lineno_',
                            'al.idno',
                            'al.suppcode',
                            'al.allocdate as allocdate',
                            'al.reference',
                            'al.refamount',
                            'al.outamount',
                            'al.allocamount',
                            'al.balance',
                            'al.reftrantype',
                            'al.refsource',
                            'al.refauditno',
                            'al.recstatus',
                            'ap.actdate as actdate',
                            'ap.postdate as postdate',
                            'ap.recdate',
                            'ap.document',
                            'ap.recstatus',
                            'ap.amount',
                        )
                        ->join('finance.apacthdr as ap', function($join) use ($request){
                                    $join = $join->on('al.refsource', '=', 'ap.source')
                                        ->on('al.reftrantype', '=', 'ap.trantype')
                                        ->on('al.refauditno', '=', 'ap.auditno');
                        })
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.refsource','=',$request->source)
                        ->where('al.reftrantype','=',$request->trantype)
                        ->where('al.refauditno','=',$request->auditno)
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
            case 'cancel':
                return $this->cancel($request);
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
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }
        try {

            DB::beginTransaction();

            $auditno = $this->recno($request->apacthdr_source, $request->apacthdr_trantype);
            $suppgroup = $this->suppgroup($request->apacthdr_suppcode);
            $compcode = session('compcode');
      
            $table = DB::table("finance.apacthdr");
            
            $array_insert = [
                'source' => $request->apacthdr_source,
                'auditno' => $auditno,
                'trantype' => $request->apacthdr_trantype,
                'doctype' => $request->apacthdr_doctype,
                'actdate' => $request->apacthdr_actdate,
                'recdate' => $request->apacthdr_postdate,
                'postdate' => $request->apacthdr_postdate,
                'suppgroup' => $suppgroup,
                'document' => strtoupper($request->apacthdr_document),
                'suppcode' => strtoupper($request->apacthdr_suppcode),
                'payto' => strtoupper($request->apacthdr_payto),
                'remarks' => strtoupper($request->apacthdr_remarks),
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'entryuser' => session('username'),
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'amount' => $request->apacthdr_amount,
                'outamount' => $request->apacthdr_amount,
                'unit' => session('unit'),

            ];

            foreach ($field as $key => $value){
                if($key == 'remarks' || $key == 'document' || $value == 'outamount'){
                    continue;
                }
                $array_insert[$value] = $request[$request->field[$key]];
            }

            $idno = $table->insertGetId($array_insert);

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->idno = $idno;
            $responce->suppgroup = $suppgroup;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

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

        DB::beginTransaction();

        $table = DB::table("finance.apacthdr");

        $array_update = [
            'unit' => session('unit'),
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'postdate' => $request->apacthdr_postdate,
            'actdate' => $request->apacthdr_actdate,
            'recdate' => $request->apacthdr_postdate,
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

        foreach ($field as $key => $value) {
            if($value == 'remarks' || $value == 'document' || $value == 'amount' || $value == 'outamount'){
                continue;
            }
            $array_update[$value] = $request[$request->field[$key]];
        }

        try {
            //////////where//////////
            $table = $table->where('idno','=',$request->apacthdr_idno);
            $table->update($array_update);

            DB::commit();

            $responce = new stdClass();
            $responce->auditno = $request->apacthdr_auditno;
            $responce->idno = $request->apacthdr_idno;
            echo json_encode($responce);
            
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function posted(Request $request){
        DB::beginTransaction();
        try {

            foreach ($request->idno_array as $idno){

                $apacthdr = DB::table('finance.apacthdr')
                    ->where('idno','=',$idno)
                    ->first();

                if($apacthdr->amount == 0){
                    throw new \Exception('Debit Note auditno: '.$apacthdr->auditno.' amount cant be zero', 500);
                }

                $yearperiod = defaultController::getyearperiod_($apacthdr->postdate);
                if($yearperiod->status == 'C'){
                    throw new \Exception('Debit Note auditno: '.$apacthdr->auditno.' Period already close, Year: '.$yearperiod->year.' Month: '.$yearperiod->period, 500);
                }
                    
                $this->gltran($idno);

                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno)
                    ->update([
                        'recstatus' => 'POSTED',
                        'recdate' => $apacthdr->postdate,
                        'postuser' => session('username'),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
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
                            ->where('compcode','=',session('compcode'))
                            ->first();

            if($apacthdr->recstatus == 'POSTED'){

                $this->gltran_cancel($request->idno);

                DB::table('finance.apacthdr')
                    ->where('idno','=',$request->idno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'CANCELLED' 
                    ]);

            }else{

                DB::table('finance.apacthdr')
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
                $gltranAmount =  defaultController::isGltranExist_($dept_obj->costcode,$category_obj->expacct,$yearperiod->year,$yearperiod->period);

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
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($supp_obj->costcode,$supp_obj->glaccno,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$supp_obj->costcode)
                        ->where('glaccount','=',$supp_obj->glaccno)
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
                $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate);

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
                        'drcostcode' => $dept_obj->costcode,
                        'dracc' => $category_obj->expacct,
                        'crcostcode' => $supp_obj->costcode,
                        'cracc' => $supp_obj->glaccno,
                        'amount' => $value->amount,
                        'postdate' => $apacthdr_obj->postdate,
                        'adduser' => $apacthdr_obj->adduser,
                        'adddate' => $apacthdr_obj->adddate,
                        'idno' => null
                    ]);

                //2. check glmastdtl utk debit, kalu ada update kalu xde create
                $gltranAmount =  defaultController::isGltranExist_($dept_obj->costcode,$category_obj->expacct,$yearperiod->year,$yearperiod->period);

                if($gltranAmount!==false){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$dept_obj->costcode)
                        ->where('glaccount','=',$category_obj->expacct)
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
                            'glaccount' => $category_obj->expacct,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                $gltranAmount = defaultController::isGltranExist_($supp_obj->costcode,$supp_obj->glaccno,$yearperiod->year,$yearperiod->period);

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
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $supp_obj->costcode,
                            'glaccount' => $supp_obj->glaccno,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => - $value->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }
            }

        }


        // $supp_obj = DB::table('material.supplier')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('suppcode','=',$apacthdr_obj->suppcode)
        //                     ->first();

        // //amik yearperiod dari delordhd
        // $yearperiod = defaultController::getyearperiod_($apacthdr_obj->actdate);

        // $debit_obj = $this->gltran_fromdept($apacthdr_obj->deptcode,$apacthdr_obj->category);
        // $credit_obj = $this->gltran_fromsupp($apacthdr_obj->suppcode);

        // //1. buat gltran
        // DB::table('finance.gltran')
        //     ->insert([
        //         'compcode' => $apacthdr_obj->compcode,
        //         'adduser' => $apacthdr_obj->adduser,
        //         'adddate' => $apacthdr_obj->adddate,
        //         'auditno' => $apacthdr_obj->auditno,
        //         'lineno_' => 1,
        //         'source' => $apacthdr_obj->source,
        //         'trantype' => $apacthdr_obj->trantype,
        //         'reference' => $apacthdr_obj->document,
        //         'description' => $supp_obj->SuppCode.'</br>'.$supp_obj->Name, //suppliercode + suppliername
        //         'postdate' => $apacthdr_obj->recdate,
        //         'year' => $yearperiod->year,
        //         'period' => $yearperiod->period,
        //         'drcostcode' => $debit_obj->drcostcode,
        //         'dracc' => $debit_obj->draccno,
        //         'crcostcode' => $credit_obj->costcode,
        //         'cracc' => $credit_obj->glaccno,
        //         'amount' => $apacthdr_obj->amount,
        //         'idno' => null
        //     ]);

        // //2. check glmastdtl utk debit, kalu ada update kalu xde create
        // $gltranAmount =  defaultController::isGltranExist_($debit_obj->drcostcode,$debit_obj->draccno,$yearperiod->year,$yearperiod->period);

        // if($gltranAmount!==false){
        //     DB::table('finance.glmasdtl')
        //         ->where('compcode','=',session('compcode'))
        //         ->where('costcode','=',$debit_obj->drcostcode)
        //         ->where('glaccount','=',$debit_obj->draccno)
        //         ->where('year','=',$yearperiod->year)
        //         ->update([
        //             'upduser' => session('username'),
        //             'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
        //             'actamount'.$yearperiod->period => $apacthdr_obj->amount + $gltranAmount,
        //             'recstatus' => 'ACTIVE'
        //         ]);
        // }else{
        //     DB::table('finance.glmasdtl')
        //         ->insert([
        //             'compcode' => session('compcode'),
        //             'costcode' => $debit_obj->drcostcode,
        //             'glaccount' => $debit_obj->draccno,
        //             'year' => $yearperiod->year,
        //             'actamount'.$yearperiod->period => $apacthdr_obj->amount,
        //             'adduser' => session('username'),
        //             'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
        //             'recstatus' => 'ACTIVE'
        //         ]);
        // }

        // //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        // $gltranAmount = defaultController::isGltranExist_($credit_obj->costcode,$credit_obj->glaccno,$yearperiod->year,$yearperiod->period);

        // if($gltranAmount!==false){
        //     DB::table('finance.glmasdtl')
        //         ->where('compcode','=',session('compcode'))
        //         ->where('costcode','=',$credit_obj->costcode)
        //         ->where('glaccount','=',$credit_obj->glaccno)
        //         ->where('year','=',$yearperiod->year)
        //         ->update([
        //             'upduser' => session('username'),
        //             'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
        //             'actamount'.$yearperiod->period => $gltranAmount - $apacthdr_obj->amount,
        //             'recstatus' => 'ACTIVE'
        //         ]);
        // }else{
        //     DB::table('finance.glmasdtl')
        //         ->insert([
        //             'compcode' => session('compcode'),
        //             'costcode' => $credit_obj->costcode,
        //             'glaccount' => $credit_obj->glaccno,
        //             'year' => $yearperiod->year,
        //             'actamount'.$yearperiod->period => -$apacthdr_obj->amount,
        //             'adduser' => session('username'),
        //             'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
        //             'recstatus' => 'ACTIVE'
        //         ]);
        // }
    }

    public function gltran_fromdept($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        return $obj;
    }

    public function gltran_fromcategory($catcode){
        $obj = DB::table('material.category')
                ->select('expacct')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CR')
                ->where('catcode','=',$catcode)
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

}
