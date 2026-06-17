<?php

namespace App\Filament\Widgets;

use App\Models\TransactionCompensee;
use Filament\Widgets\ChartWidget;

class MonthlyTransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Évolution des transactions par mois';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $count = TransactionCompensee::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Nombre de transactions',
                    'data' => $data,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#047857',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}