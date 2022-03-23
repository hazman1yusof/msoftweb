<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

    class PaymentVoucherController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.paymentVoucher.paymentVoucher');
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
            // $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            // $idno = $request->table_id;
        }

        DB::beginTransaction();
        try {
            
            $auditno = $this->defaultSysparam($request->apacthdr_source, $request->apacthdr_trantype);
            
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
                    'recstatus' => 'OPEN'
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
                            'trantype' => 'PV',
                            'auditno' => $auditno,
                            'lineno_' => $key+1,
                            'docsource' => 'AP',
                            'doctrantype' => 'PV',
                            'docauditno' => $auditno,
                            'refsource' => $apacthdr_IV->source,
                            'reftrantype' => $apacthdr_IV->trantype,
                            'refauditno' => $apacthdr_IV->auditno,
                            'refamount' => $apacthdr_IV->amount,
                            'allocdate' => $request->apacthdr_actdate,//blank
                            'reference' => $value['reference'],
                            'allocamount' => $allocamount,
                            'outamount' => $outamount,
                            'paymode' => $request->apacthdr_paymode,
                            'cheqdate' => $request->apacthdr_cheqdate,
                            'recdate' => $request->apacthdr_recdate,
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
                    ->where('trantype','=','PV')
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('allocamount');
                
                DB::table('finance.apacthdr')
                    ->where('idno','=',$idno_apacthdr)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => '0',
                        'recstatus' => 'OPEN'
                    ]);

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->idno = $idno_apacthdr;
            $responce->totalAmount = $totalAmount;

            echo json_encode($responce);
            
            } else {
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

                $idno_apacthdr = $table->insertGetId($array_insert);


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

            //dd($apacthdr_trantype);
          
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

            foreach ($field as $key => $value) {
                if(is_string($request[$request->field[$key]])){
                    $array_update[$value] = strtoupper($request[$request->field[$key]]);
                }else{
                    $array_update[$value] = $request[$request->field[$key]];
                }
            }

            try {
                //////////where//////////
                $table = $table->where('idno','=',$request->apacthdr_idno);
                $table->update($array_update);

                $responce = new stdClass();
                echo json_encode($responce);

                DB::commit();
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
                    ->where('source','=', $apacthdr->source)
                    ->where('trantype','=', $apacthdr->trantype)
                    ->where('auditno','=', $apacthdr->auditno)
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
        // $apacthdr = DB::table('finance.apacthdr')
        //                 ->where('auditno','=',$request->auditno)
        //                 ->where('compcode','=',session('compcode'));

        // $apacthdr
        //     ->update([
        //         'upduser' => session('username'),
        //         'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
        //         'recstatus' => 'CANCELLED' 
        //     ]);

        // DB::table('finance.apactdtl')
        //     ->where('source','=',$apacthdr->first()->source)
        //     ->where('trantype','=',$apacthdr->first()->trantype)
        //     ->where('auditno','=',$apacthdr->first()->auditno)
        //     ->where('compcode','=',session('compcode'))
        //     ->delete();


        DB::beginTransaction();

        try {

            $apacthdr = DB::table('finance.apacthdr')
                        ->where('idno','=',$request->idno);

            $apacthdr_first = $apacthdr->first();

            if($apacthdr_first->recstatus != 'POSTED'){
                throw new \Exception("CANNOT DELETE UNPOSTED PAYMENT VOUCHER");
            }

            $apalloc = DB::table('finance.apalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('unit','=',session('unit'))
                    ->where('source','=', $apacthdr->source)
                    ->where('trantype','=', $apacthdr->trantype)
                    ->where('auditno','=', $apacthdr->auditno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            $supp_obj = DB::table('material.supplier')
                                ->where('compcode','=',session('compcode'))
                                ->where('suppcode','=',$apacthdr_first->suppcode)
                                ->first();

            //amik yearperiod dari delordhd
            $yearperiod = defaultController::getyearperiod_($apacthdr_first->actdate);

            $debit_obj = $this->gltran_fromdept($apacthdr_first->deptcode,$apacthdr_first->category);
            $credit_obj = $this->gltran_fromsupp($apacthdr_first->suppcode);

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
                        'actamount'.$yearperiod->period => $gltranAmount - $apacthdr_first->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{

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
                        'actamount'.$yearperiod->period => $gltranAmount + $apacthdr_first->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                
            }

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('source','=', $apacthdr_first->source)
                ->where('trantype','=', $apacthdr_first->trantype)
                ->where('auditno','=', $apacthdr_first->auditno)
                ->where('lineno_','=', 1)
                ->delete();
            
            DB::table('finance.apacthdr')
                ->where('idno','=',$request->idno)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'CANCELLED' 
                ]);
               
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 500);
        }
           
    }

    public function gltran($auditno){
        $apacthdr_obj = DB::table('finance.apacthdr')
                            ->where('idno','=',$auditno)
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
