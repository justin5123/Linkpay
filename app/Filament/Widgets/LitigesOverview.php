<?php

namespace App\Filament\Widgets;

use App\Models\TransactionCompensee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LitigesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Litiges ouverts', TransactionCompensee::where('statut', 'LITIGE')->count())
                ->description('Transactions en conflit')
                ->color('danger'),
            Stat::make('Appariements en attente', \App\Models\Appariement::where('statut', 'EN_ATTENTE_VALIDATION')->count())
                ->description('Validation requise')
                ->color('warning'),
        ];
    }
}