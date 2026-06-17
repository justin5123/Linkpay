<?php

namespace App\Filament\Widgets;

use App\Models\TransactionCompensee;
use Filament\Widgets\ChartWidget;

class TransactionStatusPieChart extends ChartWidget
{
    protected static ?string $heading = 'Répartition par statut';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $stats = TransactionCompensee::selectRaw('statut, count(*) as count')
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();

        return [
            'datasets' => [
                [
                    'data' => array_values($stats),
                    'backgroundColor' => [
                        '#10b981', // TERMINEE
                        '#ef4444', // LITIGE
                        '#6b7280', // ANNULEE
                        '#f59e0b', // EN_ATTENTE (si existant)
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_keys($stats),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}