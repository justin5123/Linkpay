<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycStepSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $step;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, int $step)
    {
        $this->user = $user;
        $this->step = $step;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Nouvelle soumission KYC - Étape {$this->step}")
                    ->view('emails.kyc-step-submitted');
    }
}