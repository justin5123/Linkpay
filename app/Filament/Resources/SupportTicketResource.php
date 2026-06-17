<?php

namespace App\Filament\Resources;

use App\Models\SupportTicket;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Notifications\NewSupportMessage;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Tickets support';
    protected static ?string $navigationGroup = 'Support';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('sujet')->required(),
            Forms\Components\Textarea::make('description')->required(),
            Forms\Components\Select::make('statut')
                ->options([
                    'OUVERT' => 'Ouvert',
                    'EN_COURS' => 'En cours',
                    'EN_ATTENTE_UTILISATEUR' => 'En attente utilisateur',
                    'RESOLU' => 'Résolu',
                    'FERME' => 'Fermé',
                ])
                ->required(),
            Forms\Components\Select::make('priorite')
                ->options([
                    'FAIBLE' => 'Faible',
                    'NORMALE' => 'Normale',
                    'ELEVEE' => 'Élevée',
                    'URGENTE' => 'Urgente',
                ])
                ->required(),
            Forms\Components\Select::make('users_id')
                ->relationship('user', 'prenom')
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->label('Réf.')->searchable(),
                TextColumn::make('user.prenom')->label('Utilisateur')->searchable(),
                TextColumn::make('sujet')->limit(50),
                BadgeColumn::make('statut')
                    ->colors([
                        'warning' => 'OUVERT',
                        'info' => 'EN_COURS',
                        'secondary' => 'EN_ATTENTE_UTILISATEUR',
                        'success' => 'RESOLU',
                        'danger' => 'FERME',
                    ]),
                BadgeColumn::make('priorite')
                    ->colors([
                        'danger' => 'URGENTE',
                        'warning' => 'ELEVEE',
                        'info' => 'NORMALE',
                    ]),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\Action::make('repondre')
                    ->label('Répondre')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->url(fn (SupportTicket $record): string => "/admin/support-ticket-conversation?ticket={$record->id}")
                    ->openUrlInNewTab(false),
                Tables\Actions\Action::make('changer_statut')
                    ->label('Changer statut')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('statut')
                            ->options([
                                'OUVERT' => 'Ouvert',
                                'EN_COURS' => 'En cours',
                                'EN_ATTENTE_UTILISATEUR' => 'En attente utilisateur',
                                'RESOLU' => 'Résolu',
                                'FERME' => 'Fermé',
                            ])
                            ->required(),
                    ])
                    ->action(fn (array $data, SupportTicket $record) => $record->update(['statut' => $data['statut']])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\SupportTicketResource\Pages\ListSupportTickets::route('/'),
            'create' => \App\Filament\Resources\SupportTicketResource\Pages\CreateSupportTicket::route('/create'),
            'edit' => \App\Filament\Resources\SupportTicketResource\Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}