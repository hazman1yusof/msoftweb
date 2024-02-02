<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class WebserviceController extends defaultController
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function page(Request $request){  
        switch($request->action){
            case 'repost_ar':
                return view('other.webservice.repost_ar');
            case 'repost_ar_upd':
                return view('other.webservice.repost_ar_upd');
            default:
                abort(404);
        }
    }

    public function table(Request $request){  
        switch($request->action){
            // case 'repost_ar':
            //     return $this->repost_ar_table($request);
            // case 'repost_ar_upd':
            //     return $this->repost_ar_table($request);
            default:
                abort(404);
        }
    }

    public function form(Request $request){  
        switch($request->action){
            case 'repost_ar':
                return $this->repost_ar_form($request);
            case 'repost_ar_upd':
                return $this->repost_ar_upd_form($request);
            default:
                abort(404);
        }
    }

    public function repost_ar_form(Request $request){
        switch (strtoupper($request->trantype)) {
            case 'DN':
                return $this->repost_ar_dn($request);
                break;
            case 'CN':
                return $this->repost_ar_cn($request);
                break;
            case 'RD':
                return $this->repost_ar_rc($request);
                break;
            case 'RC':
                return $this->repost_ar_rc($request);
                break;
            case 'IN':
                return $this->repost_ar_in($request);
                break;
            
            default:
                // code...
                break;
        }
    }

    public function repost_ar_upd_form(Request $request){
        switch (strtoupper($request->trantype)) {
            // case 'DN':
            //     return $this->repost_ar_dn($request);
            //     break;
            // case 'CN':
            //     return $this->repost_ar_cn($request);
            //     break;
            case 'RD':
                return $this->repost_ar_upd_rc($request);
                break;
            case 'RC':
                return $this->repost_ar_upd_rc($request);
                break;
            // case 'IN':
            //     return $this->repost_ar_in($request);
                break;
            
            default:
                // code...
                break;
        }
    }

    public function repost_ar_dn(Request $request){
        DB::beginTransaction();

        try {

            $dbacthdr = DB::table('debtor.dbacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('source',$request->source)
                        ->where('trantype',$request->trantype)
                        ->where('auditno',$request->auditno);

            if($dbacthdr->exists()){
                $dbacthdr_obj = $dbacthdr->first();
                $dbactdtl_get = DB::table('debtor.dbactdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$dbacthdr_obj->source)
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno)
                            ->get();

                foreach ($dbactdtl_get as $key => $value){
                    $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->entrydate);

                    $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
                    $dept_obj = $this->gltran_fromdept($value->deptcode);
                    $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

                    $gltran = DB::table('finance.gltran')
                                ->where('compcode','=',session('compcode'))
                                ->where('source','=',$dbacthdr_obj->source)
                                ->where('trantype','=',$dbacthdr_obj->trantype)
                                ->where('auditno','=',$dbacthdr_obj->auditno)
                                ->where('lineno_','=',$key+1);

                    if($gltran->exists()){
                        throw new \Exception("gltran already exists",500);
                    }

                    //1. buat gltran
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => $dbacthdr_obj->compcode,
                            'auditno' => $dbacthdr_obj->auditno,
                            'lineno_' => $key+1,
                            'source' => $dbacthdr_obj->source,
                            'trantype' => $dbacthdr_obj->trantype,
                            'reference' => $value->document,
                            'description' => $dbacthdr_obj->remark,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $debtormast_obj->actdebccode,
                            'dracc' => $debtormast_obj->actdebglacc,
                            'crcostcode' => $dept_obj->costcode,
                            'cracc' => $paymode_obj->glaccno,
                            'amount' => $value->amount,
                            'postdate' => $dbacthdr_obj->entrydate,
                            'adduser' => $dbacthdr_obj->adduser,
                            'adddate' => $dbacthdr_obj->adddate,
                            'idno' => null
                        ]);

                    //2. check glmastdtl utk debit, kalu ada update kalu xde create
                    $gltranAmount =  defaultController::isGltranExist_($debtormast_obj->actdebccode,$debtormast_obj->actdebglacc,$yearperiod->year,$yearperiod->period);

                    if($gltranAmount!==false){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$debtormast_obj->actdebccode)
                            ->where('glaccount','=',$debtormast_obj->actdebglacc)
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
                                'costcode' => $debtormast_obj->actdebccode,
                                'glaccount' => $debtormast_obj->actdebglacc,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                    //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                    $gltranAmount = defaultController::isGltranExist_($dept_obj->costcode,$paymode_obj->glaccno,$yearperiod->year,$yearperiod->period);

                    if($gltranAmount!==false){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$dept_obj->costcode)
                            ->where('glaccount','=',$paymode_obj->glaccno)
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
                                'glaccount' => $paymode_obj->glaccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => - $value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }
                }
                
            }else{
                throw new \Exception("Dbacthdr doesnt exists",500);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
    }

    public function repost_ar_cn(Request $request){
        DB::beginTransaction();

        try {
            $dbacthdr = DB::table('debtor.dbacthdr')
                                ->where('compcode',session('compcode'))
                                ->where('source',$request->source)
                                ->where('trantype',$request->trantype)
                                ->where('auditno',$request->auditno);

            if($dbacthdr->exists()){
                $dbacthdr_obj = $dbacthdr->first();
                $dbactdtl_get = DB::table('debtor.dbactdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$dbacthdr_obj->source)
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno)
                            ->get();

                foreach ($dbactdtl_get as $key => $value){
                    $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->entrydate);
                    $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
                    $dept_obj = $this->gltran_fromdept($value->deptcode);
                    $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

                    $gltran = DB::table('finance.gltran')
                                ->where('compcode','=',session('compcode'))
                                ->where('source','=',$dbacthdr_obj->source)
                                ->where('trantype','=',$dbacthdr_obj->trantype)
                                ->where('auditno','=',$dbacthdr_obj->auditno)
                                ->where('lineno_','=',$key+1);

                    if($gltran->exists()){
                        throw new \Exception("gltran already exists",500);
                    }

                    //1. buat gltran
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => $dbacthdr_obj->compcode,
                            'auditno' => $dbacthdr_obj->auditno,
                            'lineno_' => $key+1,
                            'source' => $dbacthdr_obj->source,
                            'trantype' => $dbacthdr_obj->trantype,
                            'reference' => $value->document,
                            'description' => $dbacthdr_obj->remark,
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $dept_obj->costcode,
                            'dracc' => $paymode_obj->glaccno,
                            'crcostcode' => $debtormast_obj->actdebccode,
                            'cracc' => $debtormast_obj->actdebglacc,
                            'amount' => $value->amount,
                            'postdate' => $dbacthdr_obj->entrydate,
                            'adduser' => $dbacthdr_obj->adduser,
                            'adddate' => $dbacthdr_obj->adddate,
                            'idno' => null
                        ]);

                    //2. check glmastdtl utk debit, kalu ada update kalu xde create
                    $gltranAmount =  defaultController::isGltranExist_($dept_obj->costcode,$paymode_obj->glaccno,$yearperiod->year,$yearperiod->period);

                    if($gltranAmount!==false){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$dept_obj->costcode)
                            ->where('glaccount','=',$paymode_obj->glaccno)
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
                                'glaccount' => $paymode_obj->glaccno,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                    //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
                    $gltranAmount = defaultController::isGltranExist_($debtormast_obj->actdebccode,$debtormast_obj->actdebglacc,$yearperiod->year,$yearperiod->period);

                    if($gltranAmount!==false){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$debtormast_obj->actdebccode)
                            ->where('glaccount','=',$debtormast_obj->actdebglacc)
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
                                'costcode' => $debtormast_obj->actdebccode,
                                'glaccount' => $debtormast_obj->actdebglacc,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => - $value->amount,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }

                }
            }else{
                throw new \Exception("Dbacthdr doesnt exists",500);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
    }

    public function repost_ar_rc(Request $request){
        DB::beginTransaction();

        try {
            $dbacthdr = DB::table('debtor.dbacthdr')
                                ->where('compcode',session('compcode'))
                                ->where('source',$request->source)
                                ->where('trantype',$request->trantype)
                                ->where('auditno',$request->auditno);

            if($dbacthdr->exists()){
                $dbacthdr_obj = $dbacthdr->first();
                $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->entrydate);
                $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
                $dept_obj = $this->gltran_fromdept($dbacthdr_obj->deptcode);
                $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

                $gltran = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$dbacthdr_obj->source)
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno)
                            ->where('lineno_','=',1);

                if($gltran->exists()){
                    throw new \Exception("gltran already exists",500);
                }

                if(strtoupper($request->trantype) == 'RD'){
                    $drcostcode = $dept_obj->costcode;
                    $dracc => $paymode_obj->glaccno;
                    $crcostcode = $debtormast_obj->depccode;
                    $cracc = $debtormast_obj->depglacc;
                }else{
                    $drcostcode = $dept_obj->costcode;
                    $dracc => $paymode_obj->glaccno;
                    $crcostcode = $debtormast_obj->actdebccode;
                    $cracc = $debtormast_obj->actdebglacc;
                }

                //1. buat gltran
                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => $dbacthdr_obj->compcode,
                        'auditno' => $dbacthdr_obj->auditno,
                        'lineno_' => 1,
                        'source' => $dbacthdr_obj->source,
                        'trantype' => $dbacthdr_obj->trantype,
                        'reference' => $dbacthdr_obj->recptno,
                        'description' => $dbacthdr_obj->remark,
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $drcostcode,
                        'dracc' => $dracc,
                        'crcostcode' => $crcostcode,
                        'cracc' => $cracc,
                        'amount' => $dbacthdr_obj->amount,
                        'postdate' => $dbacthdr_obj->entrydate,
                        'adduser' => $dbacthdr_obj->adduser,
                        'adddate' => $dbacthdr_obj->adddate,
                        'idno' => null
                    ]);

                $this->init_glmastdtl(
                    $drcostcode,//drcostcode
                    $dracc,//dracc
                    $crcostcode,//crcostcode
                    $cracc,//cracc
                    $yearperiod->year,
                    $yearperiod->period,
                    $dbacthdr_obj->amount
                );

            }else{
                throw new \Exception("Dbacthdr doesnt exists",500);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
    }

    public function repost_ar_in(Request $request){
        DB::beginTransaction();

        try {
            $dbacthdr = DB::table('debtor.dbacthdr')
                                ->where('compcode',session('compcode'))
                                ->where('source',$request->source)
                                ->where('trantype',$request->trantype)
                                ->where('auditno',$request->auditno);

            if($dbacthdr->exists()){
                $dbacthdr_obj = $dbacthdr->first();
                $billsum = DB::table("debtor.billsum")
                            ->where('compcode',session('compcode'))
                            ->where('source','=',$dbacthdr_obj->source)
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('billno','=',$dbacthdr_obj->auditno)
                            ->get();

                foreach ($billsum as $billsum_obj){

                    $gltran = DB::table('finance.gltran')
                                ->where('compcode','=',session('compcode'))
                                ->where('source','=','OE')
                                ->where('trantype','=','IN')
                                ->where('auditno','=',$billsum_obj->auditno)
                                ->where('lineno_','=',1);

                    if(!$gltran->exists()){
                        $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->posteddate);
                        $chgmast = DB::table('hisdb.chgmast')
                                    ->where('compcode',session('compcode'))
                                    ->where('chgcode',$billsum_obj->chggroup)
                                    ->first();

                        $chgtype = DB::table('hisdb.chgtype')
                                    ->where('compcode',session('compcode'))
                                    ->where('chgtype',$chgmast->chgtype)
                                    ->first();

                        $dept = DB::table('sysdb.department')
                                    ->where('compcode',session('compcode'))
                                    ->where('deptcode',$dbacthdr_obj->deptcode)
                                    ->first();

                        $sysparam = DB::table('sysdb.sysparam')
                                    ->where('compcode',session('compcode'))
                                    ->where('source','AR')
                                    ->where('trantype','AD')
                                    ->first();

                        DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'auditno' => $billsum_obj->auditno,
                                'lineno_' => 1,
                                'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                                'trantype' => 'IN',
                                'reference' => $dbacthdr_obj->invno,
                                'description' => $billsum_obj->chggroup, 
                                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $sysparam->pvalue1,
                                'dracc' => $sysparam->pvalue2,
                                'crcostcode' => $dept->costcode,
                                'cracc' => $chgtype->opacccode,
                                'amount' => $billsum_obj->amount 
                            ]);

                        $this->init_glmastdtl(
                                $sysparam->pvalue1,//drcostcode
                                $sysparam->pvalue2,//dracc
                                $dept->costcode,//crcostcode
                                $chgtype->opacccode,//cracc
                                $yearperiod,
                                $billsum_obj->amount
                        );
                    }

                }

                $gltran = DB::table('finance.gltran')
                                ->where('compcode','=',session('compcode'))
                                ->where('source','=','PB')
                                ->where('trantype','=','IN')
                                ->where('auditno','=',$dbacthdr_obj->invno)
                                ->where('lineno_','=',1);

                if(!$gltran->exists()){
                    $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->posteddate);
                    $debtormast = DB::table("debtor.debtormast")
                                ->where('compcode',session('compcode'))
                                ->where('debtorcode',$dbacthdr_obj->payercode)
                                ->first();

                    $sysparam = DB::table('sysdb.sysparam')
                                ->where('compcode',session('compcode'))
                                ->where('source','AR')
                                ->where('trantype','AD')
                                ->first();

                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => session('compcode'),
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'auditno' => $dbacthdr_obj->invno,
                            'lineno_' => 1,
                            'source' => 'PB', //kalau stock 'IV', lain dari stock 'DO'
                            'trantype' => 'IN',
                            'reference' => $dbacthdr_obj->invno,
                            'description' => $dbacthdr_obj->remark, 
                            'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $debtormast->actdebccode,
                            'dracc' => $debtormast->actdebglacc,
                            'crcostcode' => $sysparam->pvalue1,
                            'cracc' => $sysparam->pvalue2,
                            'amount' => $dbacthdr_obj->amount
                        ]);

                    $this->init_glmastdtl(
                                $debtormast->actdebccode,//drcostcode
                                $debtormast->actdebglacc,//dracc
                                $sysparam->pvalue1,//crcostcode
                                $sysparam->pvalue2,//cracc
                                $yearperiod,
                                $dbacthdr_obj->amount
                            );
                }


            }else{
                throw new \Exception("Dbacthdr doesnt exists",500);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
    }

    public function repost_ar_upd_rc(Request $request,$type){
        DB::beginTransaction();

        try {
            $dbacthdr = DB::table('debtor.dbacthdr')
                                ->where('compcode',session('compcode'))
                                ->where('source',$request->source)
                                ->where('trantype',$request->trantype)
                                ->where('auditno',$request->auditno);

            if($dbacthdr->exists()){
                $dbacthdr_obj = $dbacthdr_obj->first();
                $gltran = DB::table('finance.gltran')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$dbacthdr_obj->source)
                            ->where('trantype','=',$dbacthdr_obj->trantype)
                            ->where('auditno','=',$dbacthdr_obj->auditno)
                            ->where('lineno_','=',1);

                if(!$gltran->exists()){
                    throw new \Exception("gltran not exists",500);
                }

                //update glmasdtl
                $gltran_first = $gltran->first();

                $gltranAmount =  defaultController::isGltranExist_($gltran_first->crcostcode,$gltran_first->cracc,$gltran_first->year,$gltran_first->period);
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$gltran_first->crcostcode)
                    ->where('glaccount','=',$gltran_first->cracc)
                    ->where('year','=',$gltran_first->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$gltran_first->period => $gltranAmount + $dbacthdr_obj->amount,
                        'recstatus' => 'ACTIVE'
                    ]);

                $gltranAmount =  defaultController::isGltranExist_($gltran_first->drcostcode,$gltran_first->dracc,$gltran_first->year,$gltran_first->period);
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$gltran_first->drcostcode)
                    ->where('glaccount','=',$gltran_first->dracc)
                    ->where('year','=',$gltran_first->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$gltran_first->period => $gltranAmount - $dbacthdr_obj->amount,
                        'recstatus' => 'ACTIVE'
                    ]);


                //update gltran
                $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
                $dept_obj = $this->gltran_fromdept($dbacthdr_obj->deptcode);
                $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

                if(strtoupper($trantype) == 'RD'){
                    $crcostcode = $debtormast_obj->depccode;
                    $cracc = $debtormast_obj->depglacc;
                    $drcostcode => $dept_obj->costcode,
                    $dracc => $paymode_obj->glaccno,
                }else{
                    $crcostcode = $debtormast_obj->actdebccode;
                    $cracc = $debtormast_obj->actdebglacc;
                    $drcostcode => $dept_obj->costcode,
                    $dracc => $paymode_obj->glaccno,
                }

                DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$dbacthdr_obj->source)
                    ->where('trantype','=',$dbacthdr_obj->trantype)
                    ->where('auditno','=',$dbacthdr_obj->auditno)
                    ->where('lineno_','=',1)
                    ->update([
                        'drcostcode' => $drcostcode,
                        'dracc' => $dracc,
                        'crcostcode' => $crcostcode,
                        'cracc' => $cracc,
                        'amount' => $dbacthdr_obj->amount,
                    ]);

                $this->init_glmastdtl(
                        $drcostcode,//drcostcode
                        $dracc,//dracc
                        $crcostcode,//crcostcode
                        $cracc,//cracc
                        $gltran_first->year,
                        $gltran_first->period,
                        $dbacthdr_obj->amount
                );

            }else{
                throw new \Exception("Dbacthdr doesnt exists",500);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response($e, 500);
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

    public function gltran_frompaymode($paymode){
        $obj = DB::table('debtor.paymode')
                ->select('glaccno')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AR')
                ->where('paymode','=',$paymode)
                ->first();

        return $obj;
    }

    public function gltran_fromdebtormast($payercode){
        $obj = DB::table('debtor.debtormast')
                ->select('actdebglacc','actdebccode')
                ->where('compcode','=',session('compcode'))
                ->where('debtorcode','=',$payercode)
                ->first();

        return $obj;
    }

    public function init_glmastdtl($dbcc,$dbacc,$crcc,$cracc,$yearperiod,$amount){
        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($dbcc,$dbacc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$dbcc)
                ->where('glaccount','=',$dbacc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => floatval($amount) + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $dbcc,
                    'glaccount' => $dbacc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcc,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcc)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - floatval($amount),
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcc,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

}