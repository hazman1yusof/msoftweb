<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use Carbon\Carbon;
use App\Mail\sendmailPV;
use DB;

class SendEmailPV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        foreach ($data as $key => $value) {
            $email = $value->email;
            $subject = 'Payment Voucher to be '.$value->trantype;
            $type = 'Payment Voucher';
            $recno = $value->recno;
            $curr_stats = $value->recstatus;
            $reqdept = $value->payto;
            $prepredon = Carbon::parse($value->recdate)->format('d-m-Y').' by '.$value->adduser;

            DB::table('sysdb.sendemail')
                    ->insert([
                        'email' => $email,
                        'authorid' => $value->authorid,
                        'subject' => $subject,
                        'type' => $type, 
                        'recno' => $recno,
                        'curr_stats' => $curr_stats,
                        'reqdept' => $reqdept,
                        'prepredon' => $prepredon,
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            $NOT_USE_MAIL = \config('get_config.NOT_USE_MAIL');

            if(!empty($email) && $NOT_USE_MAIL == null){
                Mail::to($email)->send(new sendmailPV($subject,$type,$recno,$curr_stats,$reqdept,$prepredon));
            }
        }
    }
}
