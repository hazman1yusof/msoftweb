<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

    class InvoiceAPController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.invoiceAP.invoiceAP');
    }

    public function table(Request $request){

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

        $auditno = $this->recno($request->apacthdr_source, $request->apacthdr_trantype);
        $suppgroup = $this->suppgroup($request->apacthdr_suppcode);

        DB::beginTransaction();

        $table = DB::table("finance.apacthdr");
        
        $array_insert = [
            'source' => $request->apacthdr_source,
            'auditno' => $auditno,
            'trantype' => $request->apacthdr_trantype,
            'doctype' => $request->apacthdr_doctype,
            'recdate' => $request->apacthdr_recdate,
            'suppgroup' => $suppgroup,
            'document' => strtoupper($request->apacthdr_document),
            'category' => strtoupper($request->apacthdr_category),
            'remarks' => strtoupper($request->apacthdr_remarks),
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN',
            'outamount' => $request->apacthdr_amount,
        ];

        foreach ($field as $key => $value){
            if($key == 'remarks' || $key == 'document' || $value == 'outamount'){
                continue;
            }
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {
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
            'doctype' => $request->apacthdr_doctype,
            'recdate' => $request->apacthdr_recdate,
            'document' => strtoupper($request->apacthdr_document),
            'category' => strtoupper($request->apacthdr_category),
            'remarks' => strtoupper($request->apacthdr_remarks),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        foreach ($field as $key => $value) {
            if($value == 'remarks' || $value == 'document'){
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

            return response('Error'.$e, 500);
        }
    }

    public function posted(Request $request){
        DB::beginTransaction();
        try {


            foreach ($request->idno_array as $auditno){

                $apacthdr = DB::table('finance.apacthdr')
                    ->where('auditno','=',$auditno)
                    ->first();

                $apactdtl = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=', $auditno);

                $this->gltran($auditno);

                if($apactdtl->exists()){ 
                    foreach ($apactdtl->get() as $value) {
                        DB::table('material.delordhd')
                            ->where('compcode','=',session('compcode'))
                            ->where('recstatus','=','POSTED')
                            ->where('delordno','=',$value->document)
                            ->update(['invoiceno'=>$apacthdr->document]);
                    }
                }

                DB::table('finance.apacthdr')
                    ->where('auditno','=',$auditno)
                    ->update([
                        'recstatus' => 'POSTED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function cancel(Request $request){
        $apacthdr = DB::table('finance.apacthdr')
                        ->where('auditno','=',$request->auditno)
                        ->where('compcode','=',session('compcode'));

        $apacthdr
            ->update([
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                'recstatus' => 'CANCELLED' 
            ]);

        DB::table('finance.apactdtl')
            ->where('source','=',$apacthdr->first()->source)
            ->where('trantype','=',$apacthdr->first()->trantype)
            ->where('auditno','=',$apacthdr->first()->auditno)
            ->where('compcode','=',session('compcode'))
            ->delete();
           
    }

    public function gltran($auditno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('auditno','=',$auditno)
                            ->first();

        $supp_obj = DB::table('material.supplier')
                            ->where('compcode','=',session('compcode'))
                            ->where('suppcode','=',$apacthdr_obj->suppcode)
                            ->first();

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($apacthdr_obj->actdate);

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

    public function gltran_fromdept($deptcode,$catcode){

        $ccode_obj = DB::table("sysdb.department")
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$deptcode)
                    ->first();

        $draccno_obj = DB::table("material.category")
                        ->where('compcode','=',session('compcode'))
                        ->where('catcode','=',$catcode)
                        ->where('source','=','CR')
                        ->first();
        
        $responce = new stdClass();
        $responce->drcostcode = $ccode_obj->costcode;
        $responce->draccno = $draccno_obj->expacct;
        return $responce;
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
