<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class enrollment_notification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $class;


    public function __construct($user, $class = null)
    {
        $this->user = $user;
        $this->class = $class;
    }

    public function build()
    {
        return $this->subject('Enrollment Confirmed – Welcome to Your Course at LEIAAI')
            ->view('emails.enrollment_notif')
            ->with([
                'user' => $this->user,
                'class' => $this->class,
            ]);
    }
}
