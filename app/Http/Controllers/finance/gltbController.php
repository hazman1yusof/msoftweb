<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Response;
use App\Jobs\gltb_jobs;

class  gltbController extends defaultController
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   
        return view('other.gtb.gtb');
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'gltb_del':
                return $this->gltb_del($request);
            case 'gltb_run':
                gltb_jobs::dispatch($request->action,$request->like);
                return 'done';
            case 'gltrandr':
                gltb_jobs::dispatch($request->action,$request->month);
                return 'done';
                // return $this->gltb_run_dr($request);
            case 'gltrancr':
                gltb_jobs::dispatch($request->action,$request->month);
                return 'done';
            case 'glmasdtl':
                $this->glmasdtl($request);
                return 'done';
                // return $this->gltb_run_cr($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request){   
        switch($request->action){
            case 'gltb_run':
                return $this->gltb_run($request);
            default:
                return 'error happen..';
        }
    }

    public function gltb_del(Request $request){
        DB::table('recondb.gltb')->truncate();
    }

    public function glmasdtl(Request $request){
        $month_ = explode('-', $request->month);

        $year = $month_[0];
        $period = intval($month_[1]);

        DB::table('finance.glmasdtl')
                ->where('compcode',session('compcode'))
                ->where('year',$year)
                ->update([
                    'actamount'.$period => 0
                ]);

        $gltb = DB::table('recondb.gltb')
                ->where('compcode',session('compcode'))
                ->where('year',$year)
                ->where('period',$period)
                ->get();

        foreach ($gltb as $gltb_obj) {
            $glmasdtl = DB::table('finance.glmasdtl')
                            ->where('compcode',session('compcode'))
                            ->where('year',$year)
                            ->where('costcode',$gltb_obj->costcode)
                            ->where('glaccount',$gltb_obj->glaccount);

            if($glmasdtl->exists()){
                DB::table('finance.glmasdtl')
                            ->where('compcode',session('compcode'))
                            ->where('year',$year)
                            ->where('costcode',$gltb_obj->costcode)
                            ->where('glaccount',$gltb_obj->glaccount)
                            ->update([
                                'actamount'.$period => $gltb_obj->amount
                            ]);
            }else{
                DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $gltb_obj->costcode,
                                'glaccount' => $gltb_obj->glaccount,
                                'year' => $year,
                                'recstatus' => 'ACTIVE',
                                'adduser' => 'SYSTEM',
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'actamount'.$period => $gltb_obj->amount,
                            ]);
            }
        }


        $glmasref = DB::table('finance.glmasref')
                        ->where('compcode',session('compcode'))
                        ->get();

        foreach ($glmasref as $glm_obj) {
            $gltran_dr = DB::table('finance.gltran')
                            ->where('compcode',session('compcode'))
                            ->where('dracc',$glm_obj->glaccno)
                            ->where('year','2025')
                            ->where('period','1')
                            ->get();

            foreach ($gltran_dr as $gltrandr_obj) {
                $gltb = DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltrandr_obj->drcostcode)
                            ->where('glaccount',$gltrandr_obj->dracc)
                            ->where('year',$gltrandr_obj->year)
                            ->where('period',$gltrandr_obj->period);

                if($gltb->exists()){
                    $gltb = $gltb->first();
                    $newamt = floatval($gltb->amount) + floatval($gltrandr_obj->amount);

                    DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltrandr_obj->drcostcode)
                            ->where('glaccount',$gltrandr_obj->dracc)
                            ->where('year',$gltrandr_obj->year)
                            ->where('period',$gltrandr_obj->period)
                            ->update([
                                'amount' => $newamt,
                            ]);
                }else{
                    DB::table('recondb.gltb')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $gltrandr_obj->drcostcode,
                            'glaccount' => $gltrandr_obj->dracc,
                            'year' => $gltrandr_obj->year,
                            'period' => $gltrandr_obj->period,
                            'amount' => $gltrandr_obj->amount,
                        ]);
                }
            }
        }
    }
}