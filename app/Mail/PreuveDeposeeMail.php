<?php

namespace App\Mail;

use App\Models\TransactionCompensee;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreuveDeposeeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $payeur;

    public function __construct(TransactionCompensee $transaction, User $payeur)
    {
        $this->transaction = $transaction;
        $this->payeur = $payeur;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Preuve de paiement déposée - LinPay',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.preuve_deposee',
        );
    }
}