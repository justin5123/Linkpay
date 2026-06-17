<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentKycResource\Pages;
use App\Models\DocumentKyc;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class DocumentKycResource extends Resource
{
    protected static ?string $model = DocumentKyc::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Demandes KYC';
    protected static ?string $navigationGroup = 'KYC';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('utilisateur_id')
                    ->relationship('utilisateur', 'email')
                    ->label('Client')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('type_document')
                    ->options([
                        'CNI' => 'Carte nationale d\'identité (CNI)',
                        'PASSEPORT' => 'Passeport',
                        'PERMIS_CONDUIRE' => 'Permis de conduire',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('numero_document')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_recto')
                    ->label('Recto')
                    ->image()
                    ->disk('public')
                    ->directory('kyc/id')
                    ->required(),
                Forms\Components\FileUpload::make('image_verso')
                    ->label('Verso')
                    ->image()
                    ->disk('public')
                    ->directory('kyc/id'),
                Forms\Components\FileUpload::make('image_selfie')
                    ->label('Selfie avec la pièce')
                    ->image()
                    ->disk('public')
                    ->directory('kyc/selfie'),
                Forms\Components\TextInput::make('score_similarite')
                    ->label('Score de similarité')
                    ->numeric()
                    ->step(0.01),
                Forms\Components\Select::make('statut')
                    ->options([
                        'EN_ATTENTE' => 'En attente',
                        'VALIDE' => 'Validé',
                        'REJETE' => 'Rejeté',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('motif_rejet')
                    ->label('Motif de rejet')
                    ->columnSpanFull(),
                Forms\Components\Select::make('valide_par')
                    ->relationship('validateur', 'email')
                    ->label('Validé par')
                    ->searchable(),
                Forms\Components\DateTimePicker::make('date_soumission')
                    ->label('Date de soumission'),
                Forms\Components\DateTimePicker::make('date_validation')
                    ->label('Date de validation'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('utilisateur.email')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_document')
                    ->label('Type de document'),
                Tables\Columns\TextColumn::make('numero_document')
                    ->label('Numéro')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_recto')
                    ->label('Recto')
                    ->width(50)
                    ->height(50),
                Tables\Columns\ImageColumn::make('image_verso')
                    ->label('Verso')
                    ->width(50)
                    ->height(50),
                Tables\Columns\ImageColumn::make('image_selfie')
                    ->label('Selfie')
                    ->width(50)
                    ->height(50),
                Tables\Columns\TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'EN_ATTENTE' => 'warning',
                        'VALIDE' => 'success',
                        'REJETE' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('date_soumission')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'EN_ATTENTE' => 'En attente',
                        'VALIDE' => 'Validé',
                        'REJETE' => 'Rejeté',
                    ]),
                Tables\Filters\SelectFilter::make('type_document'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('validate')
                    ->label('Valider')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (DocumentKyc $record) => $record->statut === 'EN_ATTENTE')
                    ->action(function (DocumentKyc $record) {
                        $record->update([
                            'statut' => 'VALIDE',
                            'date_validation' => now(),
                            'valide_par' => Auth::id(),
                        ]);
                        // Mettre à jour le statut KYC de l'utilisateur
                        $user = $record->utilisateur;
                        $user->update([
                            'statut_kyc' => 'VALIDE',
                            'kyc_status' => 'COMPLETED',
                            'kyc_validated_at' => now(),
                            'kyc_validated_by' => Auth::id(),
                        ]);
                        Notification::make()
                            ->title('Document validé')
                            ->body('Le KYC de l’utilisateur ' . $user->email . ' a été validé.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Rejeter')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (DocumentKyc $record) => $record->statut === 'EN_ATTENTE')
                    ->form([
                        Textarea::make('motif_rejet')
                            ->required()
                            ->label('Motif du rejet')
                            ->rows(3),
                    ])
                    ->action(function (DocumentKyc $record, array $data) {
                        $record->update([
                            'statut' => 'REJETE',
                            'motif_rejet' => $data['motif_rejet'],
                            'valide_par' => Auth::id(),
                        ]);
                        $record->utilisateur->update([
                            'statut_kyc' => 'REJETE',
                            'kyc_status' => 'STEP3_REJECTED',
                        ]);
                        Notification::make()
                            ->title('Document rejeté')
                            ->body('Motif : ' . $data['motif_rejet'])
                            ->danger()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentKycs::route('/'),
            'create' => Pages\CreateDocumentKyc::route('/create'),
            'edit' => Pages\EditDocumentKyc::route('/{record}/edit'),
        ];
    }
}