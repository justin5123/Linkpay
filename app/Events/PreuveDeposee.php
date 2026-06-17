<?php

namespace App\Events;

use App\Models\TransactionCompensee;
use App\Models\User;
use App\Models\Paiement;
use Illuminate\Foundation\Events\Dispatchable;

class PreuveDeposee
{
    use Dispatchable;

    public $transaction;
    public $payeur;
    public $paiement;

    public function __construct(TransactionCompensee $transaction, User $payeur, Paiement $paiement)
    {
        $this->transaction = $transaction;
        $this->payeur = $payeur;
        $this->paiement = $paiement;
    }
}