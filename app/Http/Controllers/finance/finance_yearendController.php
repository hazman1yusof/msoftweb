<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APAgeingExport;
use Maatwebsite\Excel\Facades\Excel;

class finance_yearendController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $currentyear = DB::table('sysdb.sysparam')
                            ->where('compcode',session('compcode'))
                            ->where('source','GL')
                            ->where('trantype','CURRENT_YEAR')
                            ->first();

        $period = DB::table('sysdb.period')
                            ->where('compcode',session('compcode'))
                            ->where('year',$currentyear->pvalue1 + 1);

        if($period->exists()){
            $curryear = $currentyear->pvalue1;
            $newyear = $currentyear->pvalue1 + 1;
            $lastyear = $currentyear->pvalue1 - 1;

            $period = $period->first();
        }else{
            $curryear = $currentyear->pvalue1;
            $newyear = 'No Period';
            $lastyear = $currentyear->pvalue1 - 1;

            $period = $period->first();
        }

        return view('finance.GL.finance_yearend.finance_yearend',compact('curryear','newyear','lastyear'));
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'process_newyear':
                return $this->process_newyear($request);
            case 'process_lastyear':
                return $this->process_lastyear($request);
            default:
                return 'error happen..';
        }
    }

    public function process_newyear(Request $request){
        $curryear = $request->curryear;
        $newyear = $request->curryear + 1;

        DB::beginTransaction();

        try {
            $this->check_newyear($curryear);

            $retain_earning = DB::table('sysdb.sysparam')
                                    ->where('compcode',session('compcode'))
                                    ->where('source','GL')
                                    ->where('trantype','RETAIN_EARNING')
                                    ->first();

            $glmasdtl = DB::table('finance.glmasdtl as gmd')
                            ->select('gmd.compcode','gmd.costcode','gmd.glaccount','gmd.year','gmd.openbalance','gmd.actamount1','gmd.actamount2','gmd.actamount3','gmd.actamount4','gmd.actamount5','gmd.actamount6','gmd.actamount7','gmd.actamount8','gmd.actamount9','gmd.actamount10','gmd.actamount11','gmd.actamount12','gmd.bdgamount1','gmd.bdgamount2','gmd.bdgamount3','gmd.bdgamount4','gmd.bdgamount5','gmd.bdgamount6','gmd.bdgamount7','gmd.bdgamount8','gmd.bdgamount9','gmd.bdgamount10','gmd.bdgamount11','gmd.bdgamount12','gmd.foramount1','gmd.foramount2','gmd.foramount3','gmd.foramount4','gmd.foramount5','gmd.foramount6','gmd.foramount7','gmd.foramount8','gmd.foramount9','gmd.foramount10','gmd.foramount11','gmd.foramount12','gmd.adduser','gmd.adddate','gmd.upduser','gmd.upddate','gmd.deluser','gmd.deldate','gmd.recstatus','gmd.idno','gmr.acttype')
                            ->join('finance.glmasref as gmr', function($join){
                                $join = $join->on('gmr.glaccno', 'gmd.glaccount')
                                        ->where('gmr.compcode',session('compcode'));
                            })
                            ->where('gmd.compcode',session('compcode'))
                            ->where('gmd.year',$curryear)
                            ->get();

            // dd($glmasdtl);

            $pnlbalance = 0;
            foreach ($glmasdtl as $obj) {
                $obj_arr = (array)$obj;

                if(in_array(strtoupper($obj->acttype), ['E','R'])){
                    $popenbalance = 0;

                    for ($i = 1; $i <= 12; $i++) { 
                        $pnlbalance = $pnlbalance + $obj_arr['actamount'.$i];
                    }

                }else if(in_array(strtoupper($obj->acttype), ['A','L','C'])){
                    $popenbalance = $obj_arr['openbalance'];

                    for ($i = 1; $i <= 12; $i++) { 
                        $popenbalance = $popenbalance + $obj_arr['actamount'.$i];
                    }

                    if($obj->glaccount == $retain_earning->pvalue2 && $obj->costcode == $retain_earning->pvalue1){
                        $retain_earning_sum = $popenbalance;
                    }

                }else{
                    $popenbalance = 0;
                }

                $check_exists = DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$newyear)
                                    ->where('glaccount',$obj->glaccount)
                                    ->where('costcode',$obj->costcode)
                                    ->exists();

                // dd($obj);

                if($check_exists){
                    DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$newyear)
                                    ->where('glaccount',$obj->glaccount)
                                    ->where('costcode',$obj->costcode)
                                    ->update([
                                        'openbalance' => $popenbalance,
                                        'upduser' => session('username'),
                                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                    ]);                
                }else{
                    DB::table('finance.glmasdtl')
                                    ->insert([
                                        'compcode' => session('compcode'),
                                        'costcode' => $obj->costcode,
                                        'glaccount' => $obj->glaccount,
                                        'year' => $newyear,
                                        'openbalance' => $popenbalance,
                                        'adduser' => session('username'),
                                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                        'recstatus' => 'ACTIVE',
                                    ]);
                }
            }

            $check_exists = DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$newyear)
                                    ->where('glaccount',$retain_earning->pvalue2)
                                    ->where('costcode',$retain_earning->pvalue1)
                                    ->exists();

            if($check_exists){
                DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$newyear)
                                    ->where('glaccount',$retain_earning->pvalue2)
                                    ->where('costcode',$retain_earning->pvalue1)
                                    ->update([
                                        'openbalance' => $pnlbalance + $retain_earning_sum,
                                        'upduser' => session('username'),
                                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                    ]);
            }else{
                DB::table('finance.glmasdtl')
                                    ->insert([
                                        'compcode' => session('compcode'),
                                        'costcode' => $retain_earning->pvalue1,
                                        'glaccount' => $retain_earning->pvalue2,
                                        'year' => $newyear,
                                        'openbalance' => $pnlbalance + $retain_earning_sum,
                                        'adduser' => session('username'),
                                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                        'recstatus' => 'ACTIVE',
                                    ]);  
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function process_lastyear(Request $request){
        $curryear = $request->curryear;
        $lastyear = $request->curryear - 1;

        DB::beginTransaction();

        try {

            $glmasdtl = DB::table('finance.glmasdtl as gmd')
                            ->select('gmd.compcode','gmd.costcode','gmd.glaccount','gmd.year','gmd.openbalance','gmd.actamount1','gmd.actamount2','gmd.actamount3','gmd.actamount4','gmd.actamount5','gmd.actamount6','gmd.actamount7','gmd.actamount8','gmd.actamount9','gmd.actamount10','gmd.actamount11','gmd.actamount12','gmd.bdgamount1','gmd.bdgamount2','gmd.bdgamount3','gmd.bdgamount4','gmd.bdgamount5','gmd.bdgamount6','gmd.bdgamount7','gmd.bdgamount8','gmd.bdgamount9','gmd.bdgamount10','gmd.bdgamount11','gmd.bdgamount12','gmd.foramount1','gmd.foramount2','gmd.foramount3','gmd.foramount4','gmd.foramount5','gmd.foramount6','gmd.foramount7','gmd.foramount8','gmd.foramount9','gmd.foramount10','gmd.foramount11','gmd.foramount12','gmd.adduser','gmd.adddate','gmd.upduser','gmd.upddate','gmd.deluser','gmd.deldate','gmd.recstatus','gmd.idno','gmr.acttype')
                            ->join('finance.glmasref as gmr', function($join){
                                $join = $join->on('gmr.glaccno', 'gmd.glaccount')
                                        ->where('gmr.compcode',session('compcode'));
                            })
                            ->where('gmd.compcode',session('compcode'))
                            ->where('gmd.year',$lastyear)
                            ->get();

            $pnlbalance = 0;
            foreach ($glmasdtl as $obj) {
                $obj_arr = (array)$obj;

                if(in_array(strtoupper($obj->acttype), ['E','R'])){
                    $popenbalance = 0;

                    for ($i = 1; $i <= 12; $i++) { 
                        $pnlbalance = $pnlbalance + $obj_arr['actamount'.$i];
                    }

                }else if(in_array(strtoupper($obj->acttype), ['A','L','C'])){
                    $popenbalance = $obj_arr['openbalance'];

                    for ($i = 1; $i <= 12; $i++) { 
                        $popenbalance = $popenbalance + $obj_arr['actamount'.$i];
                    }
                }

                $check_exists = DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$curryear)
                                    ->where('glaccount',$obj->glaccount)
                                    ->where('costcode',$obj->costcode)
                                    ->exists();

                if($check_exists){
                    DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$curryear)
                                    ->where('glaccount',$obj->glaccount)
                                    ->where('costcode',$obj->costcode)
                                    ->update([
                                        'openbalance' => $popenbalance,
                                        'upduser' => session('username'),
                                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                    ]);                
                }else{
                    DB::table('finance.glmasdtl')
                                    ->insert([
                                        'compcode' => session('compcode'),
                                        'costcode' => $obj->costcode,
                                        'glaccount' => $obj->glaccount,
                                        'year' => $curryear,
                                        'openbalance' => $popenbalance,
                                        'adduser' => session('username'),
                                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                        'recstatus' => 'ACTIVE',
                                    ]);
                }
            }

            $retain_earning = DB::table('sysdb.sysparam')
                                    ->where('compcode',session('compcode'))
                                    ->where('source','GL')
                                    ->where('trantype','RETAIN_EARNING')
                                    ->first();

            $check_exists = DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$curryear)
                                    ->where('glaccount',$retain_earning->pvalue1)
                                    ->where('costcode',$retain_earning->pvalue2)
                                    ->exists();

            if($check_exists){
                DB::table('finance.glmasdtl')
                                    ->where('compcode',session('compcode'))
                                    ->where('year',$curryear)
                                    ->where('glaccount',$retain_earning->pvalue2)
                                    ->where('costcode',$retain_earning->pvalue1)
                                    ->update([
                                        'openbalance' => $pnlbalance,
                                        'upduser' => session('username'),
                                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                    ]);
            }else{
                DB::table('finance.glmasdtl')
                                    ->insert([
                                        'compcode' => session('compcode'),
                                        'costcode' => $retain_earning->pvalue1,
                                        'glaccount' => $retain_earning->pvalue2,
                                        'year' => $curryear,
                                        'openbalance' => $popenbalance,
                                        'adduser' => session('username'),
                                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                        'recstatus' => 'ACTIVE',
                                    ]);  
            }
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function check_newyear($curryear){
        $period = DB::table('sysdb.period')
                            ->where('compcode',session('compcode'))
                            ->where('year',$curryear + 1);

        if(!$period->exists()){
            throw new \Exception("Period for new year doesnt exists");
        }
    }
}