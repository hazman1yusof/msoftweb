<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use Carbon\Carbon;
use DB;

class gltb_jobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $like;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$like)
    {
        //
        $this->data = $data;
        $this->like = $like;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        if($data == 'gltb_run_dr'){
            $this->gltb_run_dr();
        }else{
            $this->gltb_run_cr();
        }
        
    }

    public function gltb_run_dr(){
        $glmasref = DB::table('finance.glmasref')
                        ->where('compcode',session('compcode'))
                        ->where('glaccno','like',$this->like.'%')
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
                    $gltb_f = $gltb->first();
                    $newamt = floatval($gltb_f->amount) + floatval($gltrandr_obj->amount);

                    $gltb
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

    public function gltb_run_cr(){
        $glmasref = DB::table('finance.glmasref')
                        ->where('compcode',session('compcode'))
                        ->where('glaccno',$this->like.'%')
                        ->get();

        foreach ($glmasref as $glm_obj) {

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
