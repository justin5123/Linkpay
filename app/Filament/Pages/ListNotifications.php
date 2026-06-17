<?php

namespace App\Filament\Pages;

use App\Models\Notification;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;

class ListNotifications extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notifications';
    protected static ?string $title = 'Mes notifications';
    protected static string $view = 'filament.pages.list-notifications';

    public function table(Table $table): Table
    {
        return $table
            ->query(Notification::where('users_id', auth()->id())->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('titre')->label('Titre')->searchable(),
                TextColumn::make('message')->label('Message')->limit(50),
                IconColumn::make('est_lu')->label('Lu')->boolean(),
                TextColumn::make('created_at')->label('Date')->dateTime(),
            ])
            ->actions([
                Action::make('markAsRead')
                    ->label('Marquer comme lu')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn (Notification $record) => $record->update(['est_lu' => true, 'date_lecture' => now()]))
                    ->visible(fn (Notification $record) => !$record->est_lu),
            ])
            ->defaultSort('created_at', 'desc');
    }
}