<?php

namespace App\Filament\Widgets;

use App\Models\AdminLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminLogs extends BaseWidget
{
    protected static ?int $sort = 3; // ordre d'affichage dans le dashboard
    protected int | string | array $columnSpan = 'full'; // occupe toute la largeur

    public function table(Table $table): Table
    {
        return $table
            ->query(AdminLog::query()->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Administrateur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'changement_statut' => 'warning',
                        'resoudre_litige' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('target_type')
                    ->label('Type cible'),
                Tables\Columns\TextColumn::make('target_id')
                    ->label('ID cible'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}