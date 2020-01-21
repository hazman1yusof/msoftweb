<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;


class DirectPaymentController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.CM.directPayment.directPayment');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try{

            $auditno = $this->defaultSysparam('CM','DP');
            $pvno = $this->defaultSysparam('HIS','PV');
            $amount = 0;

            $idno = DB::table('finance.apacthdr')
                    ->insertGetId([
                        'auditno' => $auditno,
                        'bankcode' => $request->bankcode,
                        'payto' => $request->payto,
                        'actdate' => $request->actdate,
                        'amount' => $amount,
                        'paymode' => $request->paymode,
                        'cheqno' => $request->cheqno,
                        'remarks' => $request->remarks,
                        'TaxClaimable' => $request->TaxClaimable,
                        'pvno' => $pvno,
                        'cheqdate' => $request->cheqdate,
                        'source' => $request->source,
                        'trantype' => $request->trantype,
                        'compcode' => session('compcode'),
                        'unit' => session('unit'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'OPEN'
                    ]);


            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->pvno = $pvno;
            $responce->idno = $idno;
            $responce->amount = 0;

            echo json_encode($responce);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

     public function posted(Request $request){

        DB::beginTransaction();

        try {

            $apacthdr = DB::table('finance.apacthdr')
                            ->where('idno','=',$request->idno);

            $apacthdr_get = $apacthdr->first();
            $yearperiod = $this->getyearperiod($apacthdr_get->actdate);

            //1st step add cbtran credit
            DB::table('finance.cbtran')
                ->insert([  'compcode' => $apacthdr_get->compcode, 
                            'bankcode' => $apacthdr_get->bankcode, 
                            'source' => $apacthdr_get->source, 
                            'trantype' => $apacthdr_get->trantype, 
                            'auditno' => $apacthdr_get->auditno, 
                            'postdate' => $apacthdr_get->actdate, 
                            'year' => $yearperiod->year, 
                            'period' => $yearperiod->period, 
                            'cheqno' => $apacthdr_get->cheqno, 
                            'amount' => -$apacthdr_get->amount, 
                            'remarks' => $apacthdr_get->remarks, 
                            'upduser' => session('username'), 
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                            'reference' => 'Pay To :'. ' ' .$apacthdr_get->payto  .' '. $apacthdr_get->remarks, 
                            'stat' => 'A' 
                        ]);

            //1st step, 2nd phase, update bank detail
            if($this->isCBtranExist($apacthdr_get->bankcode,$yearperiod->year,$yearperiod->period)){

                $totamt = $this->getCbtranTotamt($apacthdr_get->bankcode,$yearperiod->year,$yearperiod->period);

                DB::table('finance.bankdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('year','=',$yearperiod->year)
                    ->where('bankcode','=',$apacthdr_get->bankcode)
                    ->update([
                        "actamount".$yearperiod->period => $this->totamt-$apacthdr_get->amount
                    ]);

            }else{

                DB::table('finance.bankdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'bankcode' => $apacthdr_get->bankcode,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => -$apacthdr_get->amount,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),

                        ]);
            }

            //2nd step step add gltran   
            $queryDP_obj = DB::table('finance.apacthdr')
                    ->select ('apactdtl.compcode', 'apactdtl.source', 'apactdtl.trantype', 'apactdtl.auditno', 'apactdtl.lineno_', 'apactdtl.document', 'apacthdr.remarks', 'apactdtl.deptcode', 'apactdtl.category', 'apacthdr.bankcode', 'apactdtl.amount', 'apacthdr.actdate', 'apactdtl.AmtB4GST')
                    ->join('finance.apactdtl', function($join) use ($request){
                        $join = $join->on('apactdtl.auditno', '=', 'apacthdr.auditno');
                        $join = $join->on('apactdtl.compcode', '=', 'apacthdr.compcode');
                        $join = $join->on('apactdtl.source', '=', 'apacthdr.source');
                        $join = $join->on('apactdtl.trantype', '=', 'apacthdr.trantype');
                    })
                    ->where('apactdtl.compcode', '=', session('compcode'))
                    ->where('apactdtl.source', '=', $request->source)
                    ->where('apactdtl.trantype', '=', $request->trantype)
                    ->where('apactdtl.auditno', '=', $request->auditno)
                    ->first();

                $queryDP = $queryDP_obj->queryDP; 
           

            //2nd step add cbtran + 
            DB::table('finance.cbtran')
                ->insert([  'compcode' => $apacthdr_get->compcode , 
                            'bankcode' => $apacthdr_get->payto , 
                            'source' => $apacthdr_get->source , 
                            'trantype' => $apacthdr_get->trantype , 
                            'auditno' => $apacthdr_get->auditno , 
                            'postdate' => $apacthdr_get->actdate , 
                            'year' => $yearperiod->year , 
                            'period' => $yearperiod->period , 
                            'cheqno' => $apacthdr_get->cheqno , 
                            'amount' => $apacthdr_get->amount , 
                            'remarks' => $apacthdr_get->remarks , 
                            'upduser' => session('username') , 
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur") , 
                            'reference' => 'Transfer from :'. ' ' .$apacthdr_get->bankcode  . ' ' . 'to'. ' '. $apacthdr_get->payto , 
                            'stat' => 'A' 
                        ]);

            //2nd step, 2nd phase, update bank detail
            if($this->isCBtranExist($apacthdr_get->payto,$yearperiod->year,$yearperiod->period)){

                DB::table('finance.bankdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('year','=',$yearperiod->year)
                    ->where('bankcode','=',$apacthdr_get->payto)
                    ->update([
                        "actamount".$yearperiod->period => $this->cbtranAmount+$apacthdr_get->amount
                    ]);

            }else{

                DB::table('finance.bankdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'bankcode' => $apacthdr_get->payto,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $apacthdr_get->amount,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")

                    ]);
            }

            //3rd step add gltran
            $creditbank = $this->getGLcode($apacthdr_get->bankcode);
            $debitbank = $this->getGLcode($apacthdr_get->payto);

            //4th step add glmasdtl untuk bankcode

            //creditbank glmastdtl
            if($this->isGltranExist($creditbank->glccode,$creditbank->glaccno,$yearperiod->year,$yearperiod->period)){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$creditbank->glccode)
                    ->where('glaccount','=',$creditbank->glaccno)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $this->gltranAmount - $apacthdr_get->amount,
                        'recstatus' => 'A'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $creditbank->glccode,
                        'glaccount' => $creditbank->glaccno,
                        'year' => $yearperiod->year,
                        "actamount".$yearperiod->period => -$apacthdr_get->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'A'
                    ]);
            }

            //debitbank glmastdtl
            if($this->isGltranExist($debitbank->glccode,$debitbank->glaccno,$yearperiod->year,$yearperiod->period)){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$debitbank->glccode)
                    ->where('glaccount','=',$debitbank->glaccno)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $this->gltranAmount + $apacthdr_get->amount,
                        'recstatus' => 'A'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $debitbank->glccode,
                        'glaccount' => $debitbank->glaccno,
                        'year' => $yearperiod->year,
                        "actamount".$yearperiod->period => $apacthdr_get->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'A'
                    ]);
            }

            //5th step change status to posted
            $apacthdr->update(['recstatus' => 'POSTED']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }


    }

    public function isCBtranExist($bankcode,$year,$period){

        $cbtran = DB::table('finance.bankdtl')
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('bankcode','=',$bankcode);

        if($cbtran->exists()){
            $cbtran_get = $cbtran->first();
            $this->cbtranAmount = $cbtran_get["actamount".$period];
        }

        return $cbtran->exists();
    }

    public function getGLcode($bankcode){
        $bank = DB::table('finance.bank')
                    ->where('compcode','=',session('compcode'))
                    ->where('bankcode','=',$bankcode)
                    ->first();

        $responce = new stdClass();
        $responce->glccode = $bank->glccode;
        $responce->glaccno = $bank->glaccno;
        return $responce;

    }   

    public function isPaytypeCheque($paymode){
        $paytype = DB::table('debtor.paymode')
                ->where('compcode', '=', session('compcode'))
                ->where('paymode', '=', $paymode)
                ->where('source', '=', "CM")
                ->where('paytype', '=', "Cheque")

        if($paytype->exists()){
            $paytype_get = $paytype->first();
            $this->cbtranAmount = $cbtran_get["actamount".$period];
        }        
    }

    public function getCbtranTotamt($bankcode, $year, $period){
        $cbtranamt = DB::table('finance.cbtran')
                    ->where('compcode', '=', session('compcode'))
                    ->where('bankcode', '=', $bankcode)
                    ->where('year', '=', $year)
                    ->where('period', '=', $period)
                    ->first();

        $responce = new stdClass();
        $responce->amount = $cbtranamt->amount;
        return $responce;
    }

    public function getTax ($source, $trantype){
        $tax = DB::table('sysdb.sysparam')
                ->where('compcode', '=', session('compcode'))
                ->where('source', '=', $source)
                ->where('trantype', '=', $trantype)
                ->first();

        $responce = new stdClass();
        $responce->pvalue1 = $tax->pvalue1;
        $responce->pvalue2 = $tax->pvalue2;
        return $responce;         
    }

    public function edit(Request $request){

        $table = DB::table("finance.apacthdr");

        $array_update = [
            'bankcode' => $request->bankcode,
            'payto' => $request->payto,
            'actdate' => $request->actdate,
            'paymode' => $request->paymode,
            'cheqno' => $request->cheqno,
            'remarks' => $request->remarks,
            'TaxClaimable' => $request->TaxClaimable,
            'cheqdate' => $request->cheqdate,
            'unit' => session('unit'),
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
        ];

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

    public function del(Request $request){
        DB::beginTransaction();

        try{

            DB::table('finance.apacthdr')
                    ->where('idno','=',$request->idno)
                    ->update([
                        'recstatus' => 'D' ,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }
}
