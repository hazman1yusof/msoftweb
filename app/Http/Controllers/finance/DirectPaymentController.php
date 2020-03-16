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
            case 'posted':
                return $this->posted($request);
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
                        'remarks' => strtoupper($request->remarks),
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
                            'remarks' => strtoupper($apacthdr_get->remarks), 
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
                    ->where('apactdtl.source', '=', $apacthdr_get->source)
                    ->where('apactdtl.trantype', '=', $apacthdr_get->trantype)
                    ->where('apactdtl.auditno', '=', $apacthdr_get->auditno)
                    ->get();

            if($apacthdr_get->TaxClaimable == "Claimable" ){
                $gst = $this->getTax('TX', 'BS');
            }else{
                $gst = $this->getTax('TX', 'PNL');
            }

            foreach ($queryDP_obj as $key => $apactdtl) {
                $creditbank = $this->getGLcode($apacthdr_get->bankcode);
                $debitbank = $this->getDept($apactdtl->deptcode);
                $debitbank2 = $this->getCat($apactdtl->category);
                
                DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'auditno' => $apactdtl->auditno,
                                'lineno_' => $apactdtl->lineno_,
                                'source' => $apactdtl->source,
                                'trantype' => $apactdtl->trantype,
                                'reference' => $apacthdr_get->cheqno,
                                'description' => strtoupper($apacthdr_get->remarks),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $debitbank->costcode,
                                'crcostcode' => $creditbank->glccode,
                                'dracc' => $debitbank2->expacct,
                                'cracc' => $creditbank->glaccno,
                                'amount' => $apactdtl->AmtB4GST,
                                'postdate' => $apacthdr_get->actdate,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            ]);

                if($this->isGltranExist($debitbank->costcode,$debitbank2->expacct,$yearperiod->year,$yearperiod->period)){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$debitbank->costcode)
                        ->where('glaccount','=',$debitbank2->expacct)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $this->gltranAmount + $apactdtl->AmtB4GST,
                            'recstatus' => 'A'
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $debitbank->costcode,
                            'glaccount' => $debitbank2->expacct,
                            'year' => $yearperiod->year,
                            "actamount".$yearperiod->period => -$apactdtl->AmtB4GST,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'A'
                        ]);
                }
            
                /////////////for gst/////////////////////////////////
                $amountgst = floatval($apactdtl->amount) - floatval($apactdtl->AmtB4GST);

                if($amountgst > 0.00){
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => session('compcode'),
                            'auditno' => $apactdtl->auditno,
                            'lineno_' => $apactdtl->lineno_,
                            'source' => $apactdtl->source,
                            'trantype' => $apactdtl->trantype,
                            'reference' => $apacthdr_get->cheqno,
                            'description' => strtoupper($apacthdr_get->remarks),
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $gst->pvalue1,
                            'crcostcode' => $creditbank->glccode,
                            'dracc' => $gst->pvalue2,
                            'cracc' => $creditbank->glaccno,
                            'amount' => $amountgst,
                           // 'idno' => $idno,
                            'postdate' => $apacthdr_get->actdate,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        ]);
                    
                    if($this->isGltranExist($gst->pvalue1,$gst->pvalue2,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$gst->pvalue1)
                            ->where('glaccount','=',$gst->pvalue2)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount + $amountgst,
                                'recstatus' => 'A'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $gst->pvalue1,
                                'glaccount' => $gst->pvalue2,
                                'year' => $yearperiod->year,
                                "actamount".$yearperiod->period => -$amountgst,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'A'
                            ]);
                    }

                    //3th step add glmasdtl untuk bankcode

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
                                'actamount'.$yearperiod->period => $this->gltranAmount - $amountgst,
                                'recstatus' => 'A'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $creditbank->glccode,
                                'glaccount' => $creditbank->glaccno,
                                'year' => $yearperiod->year,
                                "actamount".$yearperiod->period => -$amountgst,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'A'
                            ]);
                    }
                }
            }
            
            //step update stat at cheqtran
            if($this->isPaytypeCheque($apacthdr_get->paymode == "Cheque")){
                DB::table('finance.chqtran')
                    ->where('compcode','=',session('compcode'))
                    ->where('bankcode','=',$apacthdr_get->bankcode)
                    ->where('cheqno','=',$apacthdr_get->cheqno)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'cheqdate' => $apacthdr_get->cheqdate,
                        'amount' => $apacthdr_get->amount,
                        'remarks' => strtoupper($apacthdr_get->remarks),
                        'stat' => 'I',
                        'auditno' => $apacthdr_get->auditno,
                        'trantype' => $apacthdr_get->trantype,
                        'source' => $apacthdr_get->source,
                       
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
            $cbtran_get = (array)$cbtran->first();
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

    public function getDept($deptcode){
        $dept = DB::table('sysdb.department')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        $responce = new stdClass();
        $responce->costcode = $dept->costcode;
        return $responce;

    }

    public function getCat($catcode){
        $dept = DB::table('material.category')
                ->where('compcode','=',session('compcode'))
                ->where('catcode','=',$catcode)
                ->where('source','=',"CR")
                ->first();

        $responce = new stdClass();
        $responce->expacct = $dept->expacct;
        return $responce;

    }


    public function isPaytypeCheque($paymode){
        $paytype = DB::table('debtor.paymode')
                ->where('compcode', '=', session('compcode'))
                ->where('paymode', '=', $paymode)
                ->where('source', '=', "CM")
                ->where('paytype', '=', "Cheque")
                ->first();
        
        $responce = new stdClass();
        $responce->paytype = $paytype->paytype;
        return $responce;
    }

    public function getCbtranTotamt($bankcode, $year, $period){
        $cbtranamt = DB::table('finance.cbtran')
                    ->where('compcode', '=', session('compcode'))
                    ->where('bankcode', '=', $bankcode)
                    ->where('year', '=', $year)
                    ->where('period', '=', $period)
                    ->sum('amount');
                   

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
            'remarks' => strtoupper($request->remarks),
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
