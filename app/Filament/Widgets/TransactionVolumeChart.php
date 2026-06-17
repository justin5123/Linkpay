<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TransactionVolumeChart extends ChartWidget
{
    protected static ?string $heading = 'Volume des transactions (XAF)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            $total = Transaction::whereYear('created_at', $month->year)
                                ->whereMonth('created_at', $month->month)
                                ->sum('montant');
            $data[] = round($total, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Montant total (XAF)',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
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