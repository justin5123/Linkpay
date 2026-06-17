<?php

namespace App\Console\Commands;

use App\Models\TransactionCompensee;
use Illuminate\Console\Command;

class CleanupTransactions extends Command
{
    protected $signature = 'transaction:cleanup';
    protected $description = 'Annule les transactions non finalisées après 7 jours';

    public function handle()
    {
        $expired = TransactionCompensee::whereIn('statut', ['EN_ATTENTE', 'PAYER_A', 'PAYER_B'])
            ->where('date_debut', '<', now()->subDays(7))
            ->get();

        foreach ($expired as $transaction) {
            $transaction->update(['statut' => 'ANNULEE']);
            $this->info("Transaction {$transaction->reference} annulée.");
        }

        $this->info($expired->count() . ' transaction(s) annulée(s).');
    }
}