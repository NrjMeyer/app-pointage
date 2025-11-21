<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SessionAutoClosedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sessions;

    public function __construct($sessions)
    {
        $this->sessions = $sessions;
    }

    public function build()
    {
        return $this->subject('Clôture automatique des sessions')
            ->view('emails.session_auto_closed');
    }
}
