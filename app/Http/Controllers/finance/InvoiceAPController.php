<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\invoiceListingsExport;
use Maatwebsite\Excel\Facades\Excel;

    class InvoiceAPController extends defaultController
    {   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   

        if($request->source == 'invoiceListings'){

            switch ($request->type) {
                case 'IN':
                    $ttype = 'IN';
                    $desc = 'Invoice';
                    break;
                case 'DN':
                    $ttype = 'DN';
                    $desc = 'Debit Note';
                    break;
                case 'CN':
                    $ttype = 'CN';
                    $desc = 'Credit Note';
                    break;
                case 'PV':
                    $ttype = 'PV';
                    $desc = 'Payment';
                    break;
                
                default:
                    $type = 'IN';
                    $desc = 'Invoice';
                    break;
            }

            return view('finance.AP.invoiceAP.invoiceListings',compact('ttype','desc'));
        }

        $purdept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('purdept',1)
                        ->get();

        return view('finance.AP.invoiceAP.invoiceAP',compact('purdept'));
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'document':
                return $this->document($request);
            case 'maintable':
                return $this->maintable($request);
            case 'get_pv_detail':
                return $this->get_pv_detail($request);
            case 'invoiceListings':
                return $this->process_pyserver($request);
                // return $this->invoiceListings($request);
            case 'check_running_process':
                return $this->check_running_process($request);
            case 'download_excel':
                return $this->download_excel($request);
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
                        'ap.deptcode AS apacthdr_deptcode',
                        'ap.amount AS apacthdr_amount',
                        'ap.outamount AS apacthdr_outamount',
                        'ap.recstatus AS apacthdr_recstatus',
                        'ap.payto AS apacthdr_payto',
                        'ap.recdate AS apacthdr_recdate',
                        'ap.postdate AS apacthdr_postdate',
                        'ap.category AS apacthdr_category',
                        'ap.remarks AS apacthdr_remarks',
                        'ap.adduser AS apacthdr_adduser',
                        'ap.adddate AS apacthdr_adddate',
                        'ap.upduser AS apacthdr_upduser',
                        'ap.upddate AS apacthdr_upddate',
                        'ap.source AS apacthdr_source',
                        'ap.idno AS apacthdr_idno',
                        'ap.unit AS apacthdr_unit'
                    )
                    ->leftJoin('material.supplier as su', 'su.SuppCode', '=', 'ap.suppcode')
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.source','=',$request->source)
                    ->where('ap.trantype','=',$request->trantype);

        // if(strtoupper(session('unit')) != 'MRS'){
        //          $table = $table->where('ap.unit','=',session('unit'));
        // }

        if($request->deptcode!='ALL'){
            $table = $table->where('ap.deptcode','=',$request->deptcode);
        }

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

    public function document(Request $request){

        // DB::insert(
        //     DB::raw("
        //         CREATE TEMPORARY TABLE table_temp_a 
        //             AS (
        //                 SELECT *
        //                 FROM material.delordhd 
        //                 WHERE compcode = '".session('compcode')."'
        //                 AND suppcode = '$request->suppcode'
        //                 AND recstatus = 'POSTED'
        //                 AND invoiceno IS NULL
        //             );
        //     ")
        // );

        // //grt negative, buat temp table sebab nak negative kan ni
        // DB::update("update table_temp_a set totamount = 0-totamount where trantype = 'GRT'");

        // $table = DB::select("
        //             SELECT table_temp_a.delordno as delordno,
        //                 SUM(table_temp_a.totamount) as amount,
        //                 max(delordhd.docno) as docno,
        //                 max(delordhd.deliverydate) as deliverydate,
        //                 max(delordhd.srcdocno) as srcdocno,
        //                 max(delordhd.taxclaimable) as taxclaimable,
        //                 max(delordhd.TaxAmt) as TaxAmt,
        //                 max(delordhd.recno) as recno,
        //                 max(delordhd.suppcode) as suppcode
        //             FROM table_temp_a
        //             LEFT JOIN material.delordhd on delordhd.delordno = table_temp_a.delordno 
        //             AND delordhd.trantype = 'GRN' 
        //             AND delordhd.suppcode = '$request->suppcode' 
        //             AND delordhd.recstatus = 'POSTED'
        //             GROUP BY table_temp_a.delordno
        //         ");

        // $chunk = collect($table)->forPage($request->page,$request->rows);

        // $responce = new stdClass();
        // $responce->page = $paginate->currentPage();
        // $responce->total = $paginate->lastPage();
        // $responce->records = $paginate->total();
        // $responce->rows = $chunk;
        // $responce->sql = $table->toSql();
        // $responce->sql_bind = $table->getBindings();

        $table = DB::table('material.delordhd')
                    ->select('delordno','srcdocno','docno','deliverydate','subamount as amount','taxclaimable','TaxAmt','recno','suppcode', 'prdept')
                    ->where('compcode','=',session('compcode'))
                    ->where('suppcode','=',$request->suppcode)
                    ->where('trantype','=','GRN')
                  //  ->where('prdept','=',$request->prdept)
                    ->where('recstatus','=','POSTED')
                    ->whereDate('trandate','<=',$request->postdate)
                    ->whereNull('invoiceno');

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'docno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('docno','like',$request->searchVal[0]);
                    });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                    });
            }
        }


        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {//ini baru

            $accum_grt_amt = DB::table('material.delordhd')
                            ->where('compcode',session('compcode'))
                            ->where('suppcode','=',$request->suppcode)
                            ->where('srcdocno','=',$value->docno)
                            ->where('trantype','=','GRT')
                            ->where('recstatus','=','POSTED')
                            ->whereNull('invoiceno')
                            ->whereDate('trandate','<=',$request->postdate)
                            ->sum('subamount');
            $amount_ = $value->amount - $accum_grt_amt;

            $value->amount = $amount_;
            
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

    public function get_pv_detail(Request $request){

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
                    ->where('al.recstatus', '=', "POSTED")
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

            // $auditno = $this->recno($request->apacthdr_source, $request->apacthdr_trantype);
            // $suppgroup = $this->suppgroup($request->apacthdr_suppcode);

            // if($request->apacthdr_doctype == 'Supplier'){
                // $auditno = $this->recno($request->apacthdr_source, $request->apacthdr_trantype);
                $suppgroup = $this->suppgroup($request->apacthdr_suppcode);
                $auditno = 0;
                $compcode = 'DD';
            // }

            $document = DB::table("finance.apacthdr")
                            ->where('document',$request->apacthdr_document)
                            ->where('compcode',session('compcode'))
                            ->where('suppcode',$request->apacthdr_suppcode)
                            ->where('recstatus','!=','CANCELLED');

            if($document->exists()){
                throw new \Exception('document No already exist: '.$request->apacthdr_document, 500);
            }

            $table = DB::table("finance.apacthdr");
            
            $array_insert = [
                'source' => $request->apacthdr_source,
                'auditno' => $auditno,
                'trantype' => $request->apacthdr_trantype,
                'doctype' => $request->apacthdr_doctype,
                'recdate' => $request->apacthdr_postdate,
                'postdate' => $request->apacthdr_postdate,
                'suppgroup' => $suppgroup,
                'document' => strtoupper($request->apacthdr_document),
                'category' => strtoupper($request->apacthdr_category),
                'remarks' => strtoupper($request->apacthdr_remarks),
                'compcode' => $compcode,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'outamount' => $request->apacthdr_amount,
                'unit' => session('unit'),
            ];

            foreach ($field as $key => $value){
                if($key == 'remarks' || $key == 'document'|| $value == 'outamt' || $value == 'outamount'){
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

        $table = DB::table("finance.apacthdr");

        $array_update = [
            'unit' => session('unit'),
            'compcode' => session('compcode'),
            // 'doctype' => $request->apacthdr_doctype,
            'postdate' => $request->apacthdr_postdate,
            'actdate' => $request->apacthdr_actdate,
            'recdate' => $request->apacthdr_postdate,
            'document' => strtoupper($request->apacthdr_document),
            'category' => strtoupper($request->apacthdr_category),
            'remarks' => strtoupper($request->apacthdr_remarks),
            'outamount' => $request->apacthdr_amount,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
        ];

        foreach ($field as $key => $value) {
            if($value == 'remarks' || $value == 'document' || $value == 'outamt' || $value == 'outamount' || $value == 'doctype'){
                continue;
            }
            $array_update[$value] = $request[$request->field[$key]];
        }

        try {
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            $responce = new stdClass();
            $responce->amount = $request->apacthdr_amount;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function posted(Request $request){
        DB::beginTransaction();
        try {

            foreach ($request->idno_array as $auditno){

                $apacthdr = DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->where('auditno','=',$auditno)
                    ->first();

                $apactdtl = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$apacthdr->source)
                    ->where('trantype','=',$apacthdr->trantype)
                    ->where('auditno','=', $auditno);

                $yearperiod = defaultController::getyearperiod_($apacthdr->postdate);
                    if($yearperiod->status == 'C'){
                        throw new \Exception('Auditno: '.$apacthdr->auditno.' Period already close, year: '.$yearperiod->year.' month: '.$yearperiod->period.' status: '.$yearperiod->status, 500);
                    }
                $this->check_outamt($apacthdr,$apactdtl);


                if($apacthdr->doctype == 'Supplier'){ 
                    $this->gltran($auditno);

                    foreach ($apactdtl->get() as $value) {
                        DB::table('material.delordhd')
                            ->where('compcode','=',session('compcode'))
                            ->where('recstatus','=','POSTED')
                            ->where('delordno','=',$value->document)
                            ->update(['invoiceno'=>$apacthdr->document]);
                    }
                }else{
                    $this->gltran_others($auditno);
                }

                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','IN')
                    ->where('auditno','=',$auditno)
                    ->update([
                        'recstatus' => 'POSTED',
                        'recdate' => $apacthdr->postdate,
                        'postuser' => session('username'),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
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

            foreach ($request->idno_array as $auditno){
                $apacthdr = DB::table('finance.apacthdr')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','IN')
                        ->where('auditno','=',$auditno)
                        ->first();

                if($apacthdr->recstatus = 'POSTED'){
                    $delordhd = DB::table('material.delordhd')
                            ->where('compcode','=',session('compcode'))
                            ->where('recstatus','=','POSTED')
                            ->where('invoiceno','=',$apacthdr->document)
                            ->update([
                                'invoiceno' => null
                            ]);

                    $apactdtl = DB::table('finance.apactdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','IN')
                        ->where('auditno','=', $auditno)
                        ->update([
                            'recstatus' => 'CANCELLED'
                        ]);

                    if(strtoupper($apacthdr->doctype) == 'SUPPLIER'){ 
                        $this->gltran_cancel($auditno);
                    }else{
                        $this->gltran_cancel_others($auditno);
                    }

                    DB::table('finance.apacthdr')
                        ->where('auditno','=',$auditno)
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','IN')
                        ->update([
                            'recstatus' => 'CANCELLED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                }else if($apacthdr->recstatus = 'OPEN'){

                    $delordhd = DB::table('material.delordhd')
                            ->where('compcode','=',session('compcode'))
                            ->where('recstatus','=','POSTED')
                            ->where('invoiceno','=',$apacthdr->document)
                            ->update([
                                'invoiceno' => null
                            ]);

                    $apactdtl = DB::table('finance.apactdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','IN')
                        ->where('auditno','=', $auditno)
                        ->update([
                            'recstatus' => 'CANCELLED'
                        ]);

                    DB::table('finance.apacthdr')
                        ->where('auditno','=',$auditno)
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','IN')
                        ->update([
                            'recstatus' => 'CANCELLED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                }

            }
               
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 500);
        }
    }

    public function gltran($auditno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','AP')
                            ->where('trantype','=','IN')
                            ->where('auditno','=',$auditno)
                            ->first();

        $supp_obj = DB::table('material.supplier')
                            ->where('compcode','=',session('compcode'))
                            ->where('suppcode','=',$apacthdr_obj->suppcode)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate);

        $debit_obj = $this->gltran_fromdept($apacthdr_obj->deptcode,$apacthdr_obj->category);
        $credit_obj = $this->gltran_fromsupp($apacthdr_obj->suppcode);

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
                'postdate' => $apacthdr_obj->postdate,
                'description' => $supp_obj->SuppCode.'</br>'.$supp_obj->Name, //suppliercode + suppliername
                'postdate' => $apacthdr_obj->recdate,
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $debit_obj->drcostcode,
                'dracc' => $debit_obj->draccno,
                'crcostcode' => $credit_obj->costcode,
                'cracc' => $credit_obj->glaccno,
                'amount' => $apacthdr_obj->amount,
                'idno' => null
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  defaultController::isGltranExist_($debit_obj->drcostcode,$debit_obj->draccno,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$debit_obj->drcostcode)
                ->where('glaccount','=',$debit_obj->draccno)
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
                    'costcode' => $debit_obj->drcostcode,
                    'glaccount' => $debit_obj->draccno,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $apacthdr_obj->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($credit_obj->costcode,$credit_obj->glaccno,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$credit_obj->costcode)
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
                    'costcode' => $credit_obj->costcode,
                    'glaccount' => $credit_obj->glaccno,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$apacthdr_obj->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function gltran_others($auditno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','AP')
                            ->where('trantype','=','IN')
                            ->where('auditno','=',$auditno)
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
                $dept_obj = $this->gltran_fromdept_others($value->deptcode);
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
    }

    public function gltran_cancel($auditno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','AP')
                            ->where('trantype','=','IN')
                            ->where('auditno','=',$auditno)
                            ->first();

        $supp_obj = DB::table('material.supplier')
                            ->where('compcode','=',session('compcode'))
                            ->where('suppcode','=',$apacthdr_obj->suppcode)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->postdate);

        $debit_obj = $this->gltran_fromdept($apacthdr_obj->deptcode,$apacthdr_obj->category);
        $credit_obj = $this->gltran_fromsupp($apacthdr_obj->suppcode);

        //1. delete gltran
        DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$apacthdr_obj->source)
                ->where('trantype','=',$apacthdr_obj->trantype)
                ->where('auditno','=',$apacthdr_obj->auditno)
                ->delete();

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  defaultController::isGltranExist_($debit_obj->drcostcode,$debit_obj->draccno,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$debit_obj->drcostcode)
                ->where('glaccount','=',$debit_obj->draccno)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $apacthdr_obj->amount - $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($credit_obj->costcode,$credit_obj->glaccno,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$credit_obj->costcode)
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

    public function gltran_cancel_others($auditno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','AP')
                            ->where('trantype','=','IN')
                            ->where('auditno','=',$auditno)
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
                $dept_obj = $this->gltran_fromdept_others($value->deptcode);
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

    public function gltran_fromdept($deptcode,$catcode){

        // $ccode_obj = DB::table("sysdb.department")
        //             ->where('compcode','=',session('compcode'))
        //             ->where('deptcode','=',$deptcode)
        //             ->first();

        $ccode_obj = DB::table("sysdb.sysparam")
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','ACC')
                    ->first();

        // $draccno_obj = DB::table("material.category")
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('catcode','=',$catcode)
        //                 ->where('source','=','CR')
        //                 ->first();
        
        $responce = new stdClass();
        $responce->drcostcode = $ccode_obj->pvalue1;
        $responce->draccno = $ccode_obj->pvalue2;
        return $responce;
    }

    public function gltran_fromdept_others($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
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

    public function check_outamt($apacthdr,$apactdtl){

        if($apactdtl->exists()){
            $apactdtl_outamt = $apactdtl->sum('amount');

            if($apacthdr->amount != $apactdtl_outamt){
                throw new \Exception("TOTAL DETAIL AMOUNT NOT EQUAL TO INVOICE AMOUNT");
            }

        }
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

    public function process_pyserver(Request $request){

        $username = session('username');
        $compcode = session('compcode');
        $suppcode_from = $request->supp_from;
        $suppcode_to = $request->supp_to;
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $ttype = $request->ttype;
        $pyserver = \config('get_config.DB_HOST_PYSERVER');

        $job_id = $this->start_job_queue($suppcode_from,$suppcode_to,$fromdate,$todate,$ttype);

        $client = new \GuzzleHttp\Client();

        $url = 'http://localhost:5000/api/invoiceListings?suppcode_from='.$suppcode_from.'&suppcode_to='.$suppcode_to.'&fromdate='.$fromdate.'&todate='.$todate.'&ttype='.$ttype.'&username='.$username.'&compcode='.$compcode.'&job_id='.$job_id.'&host='.$pyserver;

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);

        $responce = new stdClass();
        $responce->job_id = $job_id;
        return json_encode($responce);
    }

    public function invoiceListings(Request $request){
        return Excel::download(new invoiceListingsExport($request->supp_from,$request->supp_to,$request->datefr,$request->dateto), 'Invoice Listings.xlsx');
    }

    public function start_job_queue($suppcode_from,$suppcode_to,$fromdate,$todate,$ttype){
        $idno = DB::table('sysdb.job_queue')
                ->insertGetId([
                    'compcode' => session('compcode'),
                    'page' => 'invoiceListings',
                    'filename' => 'invoice Listings '.$suppcode_from.'.xlsx',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'status' => 'PENDING',
                    'remarks' => 'invoice Listings for Supplier from '.$suppcode_from.' to '.$suppcode_to.' from '.$fromdate.' to '.$todate,
                    'type' => $suppcode_from,
                    'date' => $fromdate,
                    'date_to' => $todate,
                    'debtorcode_from' => $suppcode_from,
                    'debtorcode_to' => $suppcode_to,
                    'debtortype' => $ttype
                ]);

        return $idno;
    }

    public function check_running_process(Request $request){

        $responce = new stdClass();
        $job_id = $request->job_id;

        $last_job = DB::table('sysdb.job_queue')
                        ->where('idno', $job_id)
                        ->where('compcode', session('compcode'))
                        ->where('page', 'invoiceListings')
                        ->orderBy('idno', 'desc');

        if(!$last_job->exists()){
            $responce->jobdone = 'false';
            $responce->status = 'notfound';
            return json_encode($responce);
        }

        $last_job = $last_job->first();
        $responce->status = $last_job->status;
        $responce->datefr = $last_job->adddate;
        $responce->dateto = $last_job->finishdate;
        $responce->type = $last_job->type;

        if($last_job->status != 'DONE'){
            $responce->jobdone = 'false';
        }else{
            $responce->jobdone = 'true';
        }
        return json_encode($responce);
    }

    public function download_excel(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno', $request->job_id)
                        ->where('compcode', session('compcode'))
                        ->where('page', 'invoiceListings')
                        ->where('status', 'DONE')
                        ->orderBy('idno', 'desc')
                        ->first();
                        // dd($job_queue);

        return Excel::download(new invoiceListingsExport(
            $job_queue->idno,
            $job_queue->debtorcode_from,
            $job_queue->debtorcode_to,
            $job_queue->date,
            $job_queue->date_to,
            $job_queue->debtortype
        ), $job_queue->filename);  
    }

}
