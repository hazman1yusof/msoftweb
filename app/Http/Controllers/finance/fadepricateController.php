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

    public function __construct()
    {
        $this->middleware('auth');
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
        DB::enableQueryLog();

        try {

            //1. check facontrol
            $facontrol_obj = DB::table('finance.facontrol')->where('compcode','=',session('compcode'));

            if($facontrol_obj->exists()){
                //2.baca semua faregister
                $faregister_obj = DB::table('finance.faregister')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('recstatus','=','A')
                                    ->where('startdepdate','!=','')
                                    ->where('startdepdate','<=',Carbon::now("Asia/Kuala_Lumpur"));
                                    
                if($faregister_obj->exists()){

                    $faregisters = $faregister_obj->get();

                    foreach ($faregisters as $faregister) {

                        $facode_obj = DB::table('finance.facode')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('assetcode','=',$faregister->assetcode);

                        if($facode_obj->exists()){
                            //3. amik facode utk tahu rate
                            $facode = $facode_obj->first();

                            $cost = $faregister->currentcost;
                            $lstyear = $faregister->lstytddep;
                            $ytd = $faregister->cuytddep;

                            $accum_costdisp = 0;
                            $accum_dep = 0;

                            $fatrans = DB::table('finance.fatran')
                                        ->where('compcode','=',session('compcode'))
                                        ->where('assetcode','=',$faregister->assetcode)
                                        ->where('assetno','=',$faregister->assetno)
                                        ->where('trantype','=','DIS')
                                        ->get();

                            foreach ($fatrans as $fatran) {
                                $accum_costdisp = $accum_costdisp + $fatran->amount;
                            }

                            //4. cost - dis * (rate / 100 / 12)
                            $cost = $cost - $accum_costdisp;
                            $accum_dep = $cost * $facode->rate / 100 /12;

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

                    //6. buat fatran
                    $this->fainface(
                                    $request,
                                    $faregister->deptcode,
                                    $accum_dep,
                                    $faregister->assetno,
                                    $faregister->assetcode
                                );

                }

            }

            $queries = DB::getQueryLog();
            dump($queries);

            DB::commit();

            // return back();

        } catch (\Exception $e) {

            DB::rollback();
            
            return response('Error'.$e, 500);
        }

    }

    public function fainface(Request $request,$deptcode,$amount,$assetno,$assetcode){

        $sysparam_obj = DB::table('sysdb.sysparam')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','FA')
                            ->where('trantype','=','DEP');

        if($sysparam_obj->exists()){
            $sysparam = $sysparam_obj->first();
            //create fatran
            DB::table('finance.fatran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'assetcode' => $assetcode,
                        'assetno' => $assetno,
                        'trantype' => "DEP",
                        'amount' => $amount,
                        'deptcode' => $deptcode,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'reference' => "POSTING FROM ASSET DEPRECIATION",

                    ]);

            //plus 1 sysparam
            $sysparam_obj->update([
                'pvalue1' => $sysparam->pvalue1 + 1,
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);      
        }
    }
}
