<?php

namespace App\Filament\Widgets;

use App\Models\TransactionCompensee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalTransactions = TransactionCompensee::count();
        $totalMontantA = TransactionCompensee::sum('montant_a');
        $totalMontantB = TransactionCompensee::sum('montant_b');
        $litiges = TransactionCompensee::where('statut', 'LITIGE')->count();

        return [
            Stat::make('Transactions totales', $totalTransactions)
                ->description('Toutes transactions')
                ->color('primary'),
            Stat::make('Montant total (A)', number_format($totalMontantA, 2) . ' XAF')
                ->color('success'),
            Stat::make('Montant total (B)', number_format($totalMontantB, 2) . ' EUR')
                ->color('success'),
            Stat::make('Litiges ouverts', $litiges)
                ->description('En conflit')
                ->color('danger'),
        ];
    }
}