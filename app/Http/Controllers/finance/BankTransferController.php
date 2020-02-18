<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class BankTransferController extends defaultController
{   
    var $cbtranAmount;
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.CM.bankTransfer.bankTransfer');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            case 'posted':
                return $this->posted($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $auditno = $this->recno('CM','FT');
        $pvno = $this->recno('HIS','PV');

        DB::beginTransaction();

        $table = DB::table("finance.apacthdr");
        
        $array_insert = [
            'source' => 'CM',
            'auditno' => $auditno,
            'trantype' => 'FT',
            'pvno' => $pvno,
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value){
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $idno = $table->insertGetId($array_insert);

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->idno = $idno;
            $responce->pvno = $pvno;
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

            $apacthdr = DB::table('finance.apacthdr')
                            ->where('idno','=',$request->idno);

            $apacthdr_get = $apacthdr->first();
            $yearperiod = $this->getyearperiod($apacthdr_get->actdate);

            //1st step add cbtran credit
            DB::table('finance.cbtran')
                ->insert([  'compcode' => $apacthdr_get->compcode , 
                            'bankcode' => $apacthdr_get->bankcode , 
                            'source' => $apacthdr_get->source , 
                            'trantype' => $apacthdr_get->trantype , 
                            'auditno' => $apacthdr_get->auditno , 
                            'postdate' => $apacthdr_get->actdate , 
                            'year' => $yearperiod->year , 
                            'period' => $yearperiod->period , 
                            'cheqno' => $apacthdr_get->cheqno , 
                            'amount' => -$apacthdr_get->amount , 
                            'remarks' => strtoupper($apacthdr_get->remarks) , 
                            'upduser' => session('username') , 
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur") , 
                            'reference' => 'Transfer from :'. ' ' .$apacthdr_get->bankcode  . ' ' . 'to'. ' '. $apacthdr_get->payto , 
                            'stat' => 'A' 
                        ]);

            //1st step, 2nd phase, update bank detail
            if($this->isCBtranExist($apacthdr_get->bankcode,$yearperiod->year,$yearperiod->period)){

                DB::table('finance.bankdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('year','=',$yearperiod->year)
                    ->where('bankcode','=',$apacthdr_get->bankcode)
                    ->update([
                        "actamount".$yearperiod->period => $this->cbtranAmount-$apacthdr_get->amount
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
                            'remarks' => strtoupper($apacthdr_get->remarks) , 
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


}
