<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendEmailPR implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($status)
    {
        //
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data = ['status' => $this->status];

        Mail::send('email.mail', $data, function($message) {
            $message->from('me@gmail.com', 'Christian Nwmaba');
            $message->to('hazman.yusof@gmail.com');
            // $message->from(‘SENDER_EMAIL_ADDRESS’,’Test Mail’);
            // $message->to($to_email, $to_name)->subject(Laravel Test Mail’);
        });
    }
}
