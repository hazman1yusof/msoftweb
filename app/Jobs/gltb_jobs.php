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
    protected $year;
    protected $period;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$month)
    {
        //
        $this->data = $data;
        $month_ = explode('-', $month);

        $this->year = $month_[0];
        $this->period = intval($month_[1]);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        if($data == 'gltrandr'){
            $this->gltrandr();
        }else if($data == 'gltrancr'){
            $this->gltrancr();
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
                            ->limit(2)
                            ->get();

            foreach ($gltran_dr as $gltrandr_obj) {
                $gltb = DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltrandr_obj->drcostcode)
                            ->where('glaccount',$gltrandr_obj->dracc)
                            ->where('year',$gltrandr_obj->year)
                            ->where('period',$gltrandr_obj->period);

                if(!$gltb->exists()){
                    DB::table('recondb.gltb')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $gltrandr_obj->drcostcode,
                            'glaccount' => $gltrandr_obj->dracc,
                            'year' => $gltrandr_obj->year,
                            'period' => $gltrandr_obj->period,
                            'amount' => $gltrandr_obj->amount,
                        ]);
                }else{
                    $gltb_f = $gltb->first();
                    $newamt = floatval($gltb_f->amount) + floatval($gltrandr_obj->amount);

                    $gltb
                            ->update([
                                'amount' => $newamt,
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
                            ->limit(2)
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

    public function gltran(){
        $this->gltrandr();
        $this->gltrancr();
    }

    public function gltrandr(){
        DB::table('recondb.gltb')
            ->where('compcode',session('compcode'))
            ->where('year',$this->year)
            ->where('period',$this->period)
            ->update(
                ['amount' => 0]
            );

        $gltran = DB::table('finance.gltran')
                ->where('compcode',session('compcode'))
                ->where('year',$this->year)
                ->where('period',$this->period)
                ->get();

        foreach ($gltran as $gltran_obj) {
                $gltbdr = DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltran_obj->drcostcode)
                            ->where('glaccount',$gltran_obj->dracc)
                            ->where('year',$gltran_obj->year)
                            ->where('period',$gltran_obj->period);

                if($gltbdr->exists()){
                    $gltbdr = $gltbdr->first();
                    $newamt = floatval($gltbdr->amount) + floatval($gltran_obj->amount);

                    DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltran_obj->drcostcode)
                            ->where('glaccount',$gltran_obj->dracc)
                            ->where('year',$gltran_obj->year)
                            ->where('period',$gltran_obj->period)
                            ->update([
                                'amount' => $newamt,
                            ]);
                }else{
                    DB::table('recondb.gltb')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $gltran_obj->drcostcode,
                            'glaccount' => $gltran_obj->dracc,
                            'year' => $gltran_obj->year,
                            'period' => $gltran_obj->period,
                            'amount' => $gltran_obj->amount,
                        ]);
                }

                // $gltbcr = DB::table('recondb.gltb')
                //             ->where('compcode',session('compcode'))
                //             ->where('costcode',$gltran_obj->crcostcode)
                //             ->where('glaccount',$gltran_obj->cracc)
                //             ->where('year',$gltran_obj->year)
                //             ->where('period',$gltran_obj->period);

                // if($gltbcr->exists()){
                //     $gltbcr = $gltbcr->first();
                //     $newamt = floatval($gltbcr->amount) - floatval($gltran_obj->amount);

                //     DB::table('recondb.gltb')
                //             ->where('compcode',session('compcode'))
                //             ->where('costcode',$gltran_obj->crcostcode)
                //             ->where('glaccount',$gltran_obj->cracc)
                //             ->where('year',$gltran_obj->year)
                //             ->where('period',$gltran_obj->period)
                //             ->update([
                //                 'amount' => $newamt,
                //             ]);
                // }else{
                //     DB::table('recondb.gltb')
                //         ->insert([
                //             'compcode' => session('compcode'),
                //             'costcode' => $gltran_obj->crcostcode,
                //             'glaccount' => $gltran_obj->cracc,
                //             'year' => $gltran_obj->year,
                //             'period' => $gltran_obj->period,
                //             'amount' => -$gltran_obj->amount,
                //         ]);
                // }
            }
    }

    public function gltrancr(){
        $gltran = DB::table('finance.gltran')
                ->where('compcode',session('compcode'))
                ->where('year','2025')
                ->where('period','1')
                // ->where('source','OE')
                // ->limit(2)
                ->get();

        foreach ($gltran as $gltran_obj) {
                // $gltbdr = DB::table('recondb.gltb')
                //             ->where('compcode',session('compcode'))
                //             ->where('costcode',$gltran_obj->drcostcode)
                //             ->where('glaccount',$gltran_obj->dracc)
                //             ->where('year',$gltran_obj->year)
                //             ->where('period',$gltran_obj->period);

                // if($gltbdr->exists()){
                //     $gltbdr = $gltbdr->first();
                //     $newamt = floatval($gltbdr->amount) + floatval($gltran_obj->amount);

                //     DB::table('recondb.gltb')
                //             ->where('compcode',session('compcode'))
                //             ->where('costcode',$gltran_obj->drcostcode)
                //             ->where('glaccount',$gltran_obj->dracc)
                //             ->where('year',$gltran_obj->year)
                //             ->where('period',$gltran_obj->period)
                //             ->update([
                //                 'amount' => $newamt,
                //             ]);
                // }else{
                //     DB::table('recondb.gltb')
                //         ->insert([
                //             'compcode' => session('compcode'),
                //             'costcode' => $gltran_obj->drcostcode,
                //             'glaccount' => $gltran_obj->dracc,
                //             'year' => $gltran_obj->year,
                //             'period' => $gltran_obj->period,
                //             'amount' => $gltran_obj->amount,
                //         ]);
                // }

                $gltbcr = DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltran_obj->crcostcode)
                            ->where('glaccount',$gltran_obj->cracc)
                            ->where('year',$gltran_obj->year)
                            ->where('period',$gltran_obj->period);

                if($gltbcr->exists()){
                    $gltbcr = $gltbcr->first();
                    $newamt = floatval($gltbcr->amount) - floatval($gltran_obj->amount);

                    DB::table('recondb.gltb')
                            ->where('compcode',session('compcode'))
                            ->where('costcode',$gltran_obj->crcostcode)
                            ->where('glaccount',$gltran_obj->cracc)
                            ->where('year',$gltran_obj->year)
                            ->where('period',$gltran_obj->period)
                            ->update([
                                'amount' => $newamt,
                            ]);
                }else{
                    DB::table('recondb.gltb')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $gltran_obj->crcostcode,
                            'glaccount' => $gltran_obj->cracc,
                            'year' => $gltran_obj->year,
                            'period' => $gltran_obj->period,
                            'amount' => -$gltran_obj->amount,
                        ]);
                }
            }
    }
}
