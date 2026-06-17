<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $montant;
    public $devise;
    public $autrePartie;
    public $type; // 'paye' ou 'recu'
    public $reference;

    public function __construct($user, $montant, $devise, $autrePartie, $type, $reference)
    {
        $this->user = $user;
        $this->montant = $montant;
        $this->devise = $devise;
        $this->autrePartie = $autrePartie;
        $this->type = $type;
        $this->reference = $reference;
    }

    public function build()
    {
        return $this->subject($this->type == 'paye' ? "Paiement effectué - LinPay" : "Fonds reçus - LinPay")
                    ->view('emails.transaction_notification');
    }
}