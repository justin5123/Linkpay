<?php


use App\Models\Appariement;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;

class AppariementResource extends Resource
{
    protected static ?string $model = Appariement::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationLabel = 'Appariements';
    protected static ?string $pluralLabel = 'Appariements';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('annonceEnvoi.user.name')->label('Émetteur A'),
                TextColumn::make('annonceReception.user.name')->label('Émetteur B'),
                TextColumn::make('montant_compense')->money('XAF')->label('Montant compensé'),
                BadgeColumn::make('statut')
                    ->colors([
                        'warning' => 'EN_ATTENTE_VALIDATION',
                        'success' => 'VALIDE',
                        'danger' => 'TERMINE',
                        'secondary' => 'ANNULE',
                    ]),
                TextColumn::make('created_at')->dateTime()->label('Créé le'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'EN_ATTENTE_VALIDATION' => 'En attente',
                        'VALIDE' => 'Validé',
                        'TERMINE' => 'Terminé',
                        'ANNULE' => 'Annulé',
                    ]),
            ])
            ->actions([
                Action::make('valider')
                    ->label('Valider manuellement')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Appariement $record) {
                        // Logique de validation forcée (à implémenter)
                        $record->update(['statut' => 'VALIDE']);
                        // Déclencher la création de transaction si nécessaire
                    })
                    ->visible(fn (Appariement $record) => $record->statut === 'EN_ATTENTE_VALIDATION'),
                Action::make('annuler')
                    ->label('Annuler')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Appariement $record) {
                        $record->update(['statut' => 'ANNULE']);
                    }),
            ]);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('annonce_envoi_id')
                    ->relationship('annonceEnvoi', 'id')
                    ->required(),
                Forms\Components\Select::make('annonce_reception_id')
                    ->relationship('annonceReception', 'id')
                    ->required(),
                Forms\Components\TextInput::make('montant_compense')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('statut')
                    ->options([
                        'EN_ATTENTE_VALIDATION' => 'En attente',
                        'VALIDE' => 'Validé',
                        'TERMINE' => 'Terminé',
                        'ANNULE' => 'Annulé',
                    ])
                    ->required(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\AppariementResource\Pages\ListAppariements::route('/'),
            'create' => \App\Filament\Resources\AppariementResource\Pages\CreateAppariement::route('/create'),
            'edit' => \App\Filament\Resources\AppariementResource\Pages\EditAppariement::route('/{record}/edit'),
        ];
    }
}