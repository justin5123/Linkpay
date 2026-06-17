<?php

namespace App\Filament\Resources;

use App\Models\TransactionCompensee;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\LogsAdminActions;  // ← Ajout du trait

class TransactionCompenseeResource extends Resource
{
    use LogsAdminActions;  // ← Utilisation du trait

    protected static ?string $model = TransactionCompensee::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationLabel = 'Litiges';
    protected static ?string $navigationGroup = 'Gestion des transactions';

    // Ne montrer que les transactions en litige par défaut
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('statut', 'LITIGE');
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->label('Référence')->searchable(),
                TextColumn::make('payeurA.name')->label('Payeur A')->searchable(),
                TextColumn::make('payeurB.name')->label('Payeur B')->searchable(),
                TextColumn::make('montant_a')->label('Montant A')->money('XAF'),
                TextColumn::make('montant_b')->label('Montant B')->money('EUR'),
                BadgeColumn::make('statut')
                    ->colors(['danger' => 'LITIGE']),
                TextColumn::make('motif_litige')->label('Motif')->limit(50),
                TextColumn::make('litigePar.name')->label('Signalé par'),
                TextColumn::make('date_litige')->dateTime()->label('Date'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'LITIGE' => 'Litige',
                        'TERMINEE' => 'Terminée',
                        'ANNULEE' => 'Annulée',
                    ]),
            ])
            ->actions([
                Action::make('resoudre')
                    ->label('Résoudre')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Radio::make('decision')
                            ->label('Décision')
                            ->options([
                                'valider' => 'Valider la transaction (forcer le transfert)',
                                'annuler' => 'Annuler et rembourser les payeurs',
                                'rejeter' => 'Rejeter le litige (conserver LITIGE)',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('commentaire')
                            ->label('Commentaire interne')
                            ->maxLength(255),
                    ])
                    ->action(function (array $data, TransactionCompensee $record) {
                        // Instance du resource pour accéder à logAction
                        $resource = static::class;
                        $logger = new class {
                            use LogsAdminActions;
                        };

                        if ($data['decision'] === 'valider') {
                            $record->update(['statut' => 'TERMINEE']);
                            $logger->logAction(
                                'resoudre_litige',
                                'transaction',
                                $record->id,
                                ['decision' => 'valider', 'commentaire' => $data['commentaire'] ?? null]
                            );
                        } elseif ($data['decision'] === 'annuler') {
                            // Remboursement
                            $walletA = $record->payeurA->wallets()
                                ->where('devise', $record->appariement->annonceEnvoi->devise_source)
                                ->first();
                            $walletB = $record->payeurB->wallets()
                                ->where('devise', $record->appariement->annonceReception->devise_source)
                                ->first();
                            if ($walletA) $walletA->increment('solde', $record->montant_a);
                            if ($walletB) $walletB->increment('solde', $record->montant_b);
                            $record->update(['statut' => 'ANNULEE']);
                            $logger->logAction(
                                'resoudre_litige',
                                'transaction',
                                $record->id,
                                ['decision' => 'annuler', 'commentaire' => $data['commentaire'] ?? null]
                            );
                        } else {
                            // rejeter : on ne fait que loguer
                            $logger->logAction(
                                'resoudre_litige',
                                'transaction',
                                $record->id,
                                ['decision' => 'rejeter', 'commentaire' => $data['commentaire'] ?? null]
                            );
                        }

                        if (!empty($data['commentaire'])) {
                            $record->update(['admin_comment' => $data['commentaire']]);
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\TransactionCompenseeResource\Pages\ListTransactionCompensees::route('/'),
            'edit' => \App\Filament\Resources\TransactionCompenseeResource\Pages\EditTransactionCompensee::route('/{record}/edit'),
        ];
    }
}