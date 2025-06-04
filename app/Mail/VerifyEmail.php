<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $verification_link;

    public function __construct($user, $verification_link)
    {
        $this->user = $user;
        $this->verification_link = $verification_link;
    }

    public function build()
    {
        return $this->subject('Email Verification - LEIAAI')
            ->view('emails.verify')
            ->with([
                'user' => $this->user,
                'verification_link' => $this->verification_link
            ]);
    }
}
