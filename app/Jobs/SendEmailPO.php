<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use Carbon\Carbon;
use App\Mail\sendmaildefault;
use DB;

class SendEmailPO implements ShouldQueue
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
            $subject = 'Purchase Order to be '.$value->trantype;
            $type = 'Purchase Order';
            $recno = $value->recno;
            $curr_stats = $value->recstatus;
            $reqdept = $value->prdept.' - '.$value->purordno;
            $prepredon = Carbon::parse($value->purdate)->format('d-m-Y').' by '.$value->adduser;

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

            if(!empty($email)){
                Mail::to($email)->send(new sendmaildefault($subject,$type,$recno,$curr_stats,$reqdept,$prepredon));
            }
        }
    }
}