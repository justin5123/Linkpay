<?php

namespace App\Mail;

use App\Models\Appariement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAppariementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appariement;

    public function __construct(Appariement $appariement)
    {
        $this->appariement = $appariement;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvel appariement - LinPay',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_appariement',
        );
    }
}