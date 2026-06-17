<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Annonce;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Utilisateurs', User::count())
                ->description('Total inscrits')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 8, 10]),

            Stat::make('Transactions', Transaction::count())
                ->description('Montant total : ' . number_format(Transaction::sum('montant'), 2) . ' XAF')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('warning'),

            Stat::make('Annonces', Annonce::count())
                ->description('En attente de matching')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('info'),

            Stat::make('Volume KYC', User::where('statut_kyc', 'VALIDE')->count())
                ->description('Comptes vérifiés')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}