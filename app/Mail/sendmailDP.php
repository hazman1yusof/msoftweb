<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendmailDP extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$type,$recno,$curr_stats,$reqdept,$prepredon)
    {
        $this->subject = $subject;
        $this->type = $type;
        $this->recno = $recno;
        $this->curr_stats = $curr_stats;
        $this->reqdept = $reqdept;
        $this->prepredon = $prepredon;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->markdown('email.pvmail')
                        ->subject($this->subject)
                        ->with([
                            'subject' => $this->subject,
                            'type' => $this->type,
                            'recno' => $this->recno,
                            'curr_stats' => $this->curr_stats,
                            'reqdept' => $this->reqdept,
                            'prepredon' => $this->prepredon,
                        ]);
    }
}
