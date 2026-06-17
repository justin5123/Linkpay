<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class UserRegistrationsChart extends ChartWidget
{
    protected static ?string $heading = 'Inscriptions mensuelles';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            $count = User::whereYear('created_at', $month->year)
                         ->whereMonth('created_at', $month->month)
                         ->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Nouveaux utilisateurs',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
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