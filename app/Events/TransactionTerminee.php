<?php

namespace App\Events;

use App\Models\TransactionCompensee;
use Illuminate\Foundation\Events\Dispatchable;

class TransactionTerminee
{
    use Dispatchable;

    public $transaction;

    public function __construct(TransactionCompensee $transaction)
    {
        $this->transaction = $transaction;
    }
}