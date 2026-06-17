<?php

namespace App\Events;

use App\Models\TransactionCompensee;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class PaiementConfirme
{
    use Dispatchable;

    public $transaction;
    public $confirmePar;

    public function __construct(TransactionCompensee $transaction, User $confirmePar)
    {
        $this->transaction = $transaction;
        $this->confirmePar = $confirmePar;
    }
}