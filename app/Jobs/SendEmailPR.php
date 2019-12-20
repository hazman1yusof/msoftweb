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

        $data_ = ['data' => $this->data];
        $data = $this->data;

        Mail::send('email.mail', $data_, function($message) use ($data) {
            $message->from('me@gmail.com', 'Christian Nwmaba');
            $message->to($data->email_to);
            // $message->from(‘SENDER_EMAIL_ADDRESS’,’Test Mail’);
            // $message->to($to_email, $to_name)->subject(Laravel Test Mail’);
        });
    }
}
