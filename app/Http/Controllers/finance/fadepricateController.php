<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;

class fadepricateController extends defaultController
{   

    var $table;
    var $duplicateCode;
    var $auditno;

    public function __construct()
    {
        $this->middleware('auth');
        $this->auditno;
    }

    public function show(Request $request)
    {   
        $facontrol = DB::table('finance.facontrol')->first();
        return view('finance.FA.fadepricate.fadepricate',compact('facontrol'));
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            case 'depreciation':
                return $this->depreciation($request);
            default:
                return 'error happen..';
        }
    }

    public function depreciation(Request $request){
        DB::beginTransaction();

        try {

            $year = $request->year;
            $period = $request->period;

            //1. check facontrol
            $facontrol_obj = DB::table('finance.facontrol')
                            ->where('compcode','=',session('compcode'));

            if($facontrol_obj->exists()){
                //2.baca semua faregister


                $yearperiod = defaultController::getyearperiod_($year.'-'.$period.'-01');
                $dateto = $yearperiod->dateto;

                $faregister_obj = DB::table('finance.faregister')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('recstatus','=','ACTIVE')
                                    ->whereNotNull('startdepdate')
                                    ->where('startdepdate','<=',$dateto);
                                    
                if($faregister_obj->exists()){

                    $faregisters = $faregister_obj->get();

                    foreach ($faregisters as $faregister) {

                        $facode_obj = DB::table('finance.facode')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('assetcode','=',$faregister->assetcode);

                        if($facode_obj->exists()){
                            //3. amik facode utk tahu rate
                            $facode = $facode_obj->first();

                            // $cost = $faregister->currentcost;
                            $cost = $faregister->purprice;
                            $residualvalue = $faregister->residualvalue;
                            // $lstyear = $faregister->lstytddep;
                            // $ytd = $faregister->cuytddep;

                            $accum_dep = DB::table('finance.fatran')
                                        ->where('compcode','=',session('compcode'))
                                        ->where('assetcode','=',$faregister->assetcode)
                                        ->where('assetno','=',$faregister->assetno)
                                        ->where('trantype','=','DEP')
                                        ->sum('amount');

                            $fatrans = DB::table('finance.fatran')
                                        ->where('compcode','=',session('compcode'))
                                        ->where('assetcode','=',$faregister->assetcode)
                                        ->where('assetno','=',$faregister->assetno)
                                        ->where('trantype','=','DIS')
                                        ->get();


                            $accum_costdisp = 0;
                            foreach ($fatrans as $fatran) {
                                $accum_costdisp = $accum_costdisp + $fatran->amount;
                            }

                            //4. cost - dis * (rate / 100 / 12)
                            $cost = $cost - $accum_costdisp;
                            $depr = $cost * $facode->rate / 100 /12;

                            $accum_after = $accum_dep + $depr;
                            if($cost - $accum_after < $residualvalue){
                                $cost_minus_resvalue = $cost - $residualvalue;
                                $last_depr = $cost_minus_resvalue - $accum_dep;

                                $depr = $last_depr;

                                DB::table('finance.faregister')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('recstatus','=','ACTIVE')
                                    ->where('assetcode','=',$faregister->assetcode)
                                    ->where('assetno','=',$faregister->assetno)
                                    ->update([
                                        'recstatus' => 'DEACTIVE',
                                        'trantype' => 'FUL'
                                    ]);
                            }

                            //6. buat fatran
                            $this->fainface(
                                $request,
                                $faregister,
                                $depr,
                                $dateto
                            );

                            //6. buat fatran
                            $this->gltran(
                                $request,
                                $faregister,
                                $depr,
                                $dateto
                            );
                        }

                    }

                    //5. upd balik facontrol
                    $facontrol = $facontrol_obj->first();
                    $updated_period = $facontrol->period + 1;

                    if($updated_period == 13){

                        $facontrol_obj->update([
                            'period' => 1,
                            'year' => $facontrol->year + 1,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                        
                    }else{

                        $facontrol_obj->update([
                            'period' => $updated_period,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                    }
                }

            }

            DB::commit();

            return back()->with('success', 'success');

        } catch (\Exception $e) {

            DB::rollback();
            
            return back()->with('error', $e->getMessage());
        }
    }

    public function fainface(Request $request,$faregister,$amount,$process_date){

        $sysparam_obj = DB::table('sysdb.sysparam')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','FA')
                            ->where('trantype','=','DEP');

        if($sysparam_obj->exists()){
            $sysparam = $sysparam_obj->first();

            //plus 1 sysparam
            $auditno = $sysparam->pvalue1 + 1;
            $this->auditno = $auditno;

            $sysparam_obj->update([
                'pvalue1' => $auditno,
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);  

            //create fatran
            DB::table('finance.fatran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'auditno' => $auditno,
                        'assetcode' => $faregister->assetcode,
                        'assettype' =>  $faregister->assettype,
                        'assetno' => $faregister->assetno,
                        'trantype' => "DEP",
                        'amount' => $amount,
                        'deptcode' => $faregister->deptcode,
                        'reference' => "POSTING FROM ASSET DEPRECIATION",  
                        'trandate' => $process_date,//process date
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
        }
    }

    public function gltran(Request $request,$faregister,$amount,$process_date){

        $yearperiod = defaultController::getyearperiod_($process_date);//<--process date

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$faregister->deptcode);

        if(!$row_dept->exists()){
            throw new \Exception("Department not Exists : ".$faregister->deptcode);
        }else{
            $row_dept = $row_dept->first();
        }

        $facode = DB::table('finance.facode')
            ->where('compcode','=', session('compcode'))
            ->where('assetcode','=', $faregister->assetcode);

        if(!$facode->exists()){
            throw new \Exception("facode not Exists : ".$faregister->assetcode);
        }else{
            $facode = $facode->first();
        }

        $drcostcode = $row_dept->costcode;
        $dracc = $facode->gldep;
        $crcostcode = $row_dept->costcode;
        $cracc = $facode->glprovdep;

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'auditno' => $this->auditno,
                'lineno_' => 1,
                'source' => 'FA', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => "DEP",
                'reference' => "POSTING FROM ASSET DEPRECIATION",
                'description' => $faregister->assetno, 
                'postdate' => $process_date,
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $amount,
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  defaultController::isGltranExist_($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcostcode)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }
}
