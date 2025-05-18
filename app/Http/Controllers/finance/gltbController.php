<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APAgeingDtlExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Response;

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
            case 'gltb_run_dr':
                return $this->gltb_run_dr($request);
            case 'gltb_run_cr':
                return $this->gltb_run_cr($request);
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

    public function gltb_run_dr(Request $request){
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

            // $gltran_cr = DB::table('finance.gltran')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('cracc',$glm_obj->glaccno)
            //                 ->where('year','2025')
            //                 ->where('period','1')
            //                 ->get();

            // foreach ($gltran_cr as $gltrandr_obj) {
            //     $gltb = DB::table('recondb.gltb')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('costcode',$gltrandr_obj->crcostcode)
            //                 ->where('glaccount',$gltrandr_obj->cracc)
            //                 ->where('year',$gltrandr_obj->year)
            //                 ->where('period',$gltrandr_obj->period);

            //     if($gltb->exists()){
            //         $gltb = $gltb->first();
            //         $newamt = floatval($gltb->amount) - floatval($gltrandr_obj->amount);

            //         DB::table('recondb.gltb')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('costcode',$gltrandr_obj->crcostcode)
            //                 ->where('glaccount',$gltrandr_obj->cracc)
            //                 ->where('year',$gltrandr_obj->year)
            //                 ->where('period',$gltrandr_obj->period)
            //                 ->update([
            //                     'amount' => $newamt,
            //                 ]);
            //     }else{
            //         DB::table('recondb.gltb')
            //             ->insert([
            //                 'compcode' => session('compcode'),
            //                 'costcode' => $gltrandr_obj->crcostcode,
            //                 'glaccount' => $gltrandr_obj->cracc,
            //                 'year' => $gltrandr_obj->year,
            //                 'period' => $gltrandr_obj->period,
            //                 'amount' => -$gltrandr_obj->amount,
            //             ]);
            //     }
            // }
        }
    }

    public function gltb_run_cr(Request $request){
        $glmasref = DB::table('finance.glmasref')
                        ->where('compcode',session('compcode'))
                        ->get();

        foreach ($glmasref as $glm_obj) {
            // $gltran_dr = DB::table('finance.gltran')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('dracc',$glm_obj->glaccno)
            //                 ->where('year','2025')
            //                 ->where('period','1')
            //                 ->get();

            // foreach ($gltran_dr as $gltrandr_obj) {
            //     $gltb = DB::table('recondb.gltb')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('costcode',$gltrandr_obj->drcostcode)
            //                 ->where('glaccount',$gltrandr_obj->dracc)
            //                 ->where('year',$gltrandr_obj->year)
            //                 ->where('period',$gltrandr_obj->period);

            //     if($gltb->exists()){
            //         $gltb = $gltb->first();
            //         $newamt = floatval($gltb->amount) + floatval($gltrandr_obj->amount);

            //         DB::table('recondb.gltb')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('costcode',$gltrandr_obj->drcostcode)
            //                 ->where('glaccount',$gltrandr_obj->dracc)
            //                 ->where('year',$gltrandr_obj->year)
            //                 ->where('period',$gltrandr_obj->period)
            //                 ->update([
            //                     'amount' => $newamt,
            //                 ]);
            //     }else{
            //         DB::table('recondb.gltb')
            //             ->insert([
            //                 'compcode' => session('compcode'),
            //                 'costcode' => $gltrandr_obj->drcostcode,
            //                 'glaccount' => $gltrandr_obj->dracc,
            //                 'year' => $gltrandr_obj->year,
            //                 'period' => $gltrandr_obj->period,
            //                 'amount' => $gltrandr_obj->amount,
            //             ]);
            //     }
            // }

            $gltran_cr = DB::table('finance.gltran')
                            ->where('compcode',session('compcode'))
                            ->where('cracc',$glm_obj->glaccno)
                            ->where('year','2025')
                            ->where('period','1')
                            ->get();

            foreach ($gltran_cr as $gltrandr_obj) {
                $gltb = DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltrandr_obj->crcostcode)
                            ->where('glaccount',$gltrandr_obj->cracc)
                            ->where('year',$gltrandr_obj->year)
                            ->where('period',$gltrandr_obj->period);

                if($gltb->exists()){
                    $gltb = $gltb->first();
                    $newamt = floatval($gltb->amount) - floatval($gltrandr_obj->amount);

                    DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltrandr_obj->crcostcode)
                            ->where('glaccount',$gltrandr_obj->cracc)
                            ->where('year',$gltrandr_obj->year)
                            ->where('period',$gltrandr_obj->period)
                            ->update([
                                'amount' => $newamt,
                            ]);
                }else{
                    DB::table('recondb.gltb')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $gltrandr_obj->crcostcode,
                            'glaccount' => $gltrandr_obj->cracc,
                            'year' => $gltrandr_obj->year,
                            'period' => $gltrandr_obj->period,
                            'amount' => -$gltrandr_obj->amount,
                        ]);
                }
            }
        }
    }
}