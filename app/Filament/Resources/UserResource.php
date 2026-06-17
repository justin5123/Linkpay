<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestion des utilisateurs';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations personnelles')
                    ->schema([
                        TextInput::make('nom')->required()->maxLength(255),
                        TextInput::make('prenom')->required()->maxLength(255),
                        TextInput::make('email')->email()->required()->maxLength(255),
                        TextInput::make('telephone')->tel()->required()->maxLength(255),
                        TextInput::make('pays')->required()->maxLength(255),
                        Select::make('role')
                            ->options([
                                'CLIENT' => 'Client',
                                'SUPPORT' => 'Support',
                                'CONFORMITE' => 'Conformité',
                                'FINANCE' => 'Finance',
                                'ADMIN' => 'Administrateur',
                            ])->required(),
                        Select::make('statut_compte')
                            ->options([
                                'EN_ATTENTE' => 'En attente',
                                'ACTIF' => 'Actif',
                                'SUSPENDU' => 'Suspendu',
                                'BLOQUE' => 'Bloqué',
                            ])->required(),
                        Toggle::make('is_suspected_fraud')->label('Suspect de fraude'),
                        Textarea::make('fraud_reason')->label('Motif de fraude')->columnSpanFull(),
                        DatePicker::make('email_verified_at')->label('Email vérifié le'),
                        DatePicker::make('last_login_at')->label('Dernière connexion'),
                    ])->columns(2),

                Section::make('KYC - Étape 1 : Identité')
                    ->schema([
                        TextInput::make('identity_first_name')->label('Prénom'),
                        TextInput::make('identity_last_name')->label('Nom'),
                        DatePicker::make('identity_birth_date')->label('Date de naissance'),
                        TextInput::make('identity_birth_place')->label('Lieu de naissance'),
                        TextInput::make('identity_nationality')->label('Nationalité'),
                        TextInput::make('kyc_status')->label('Statut KYC')->disabled(),
                    ])->columns(2),

                Section::make('KYC - Étape 2 : Justificatif de domicile')
                    ->schema([
                        TextInput::make('address_street')->label('Rue'),
                        TextInput::make('address_city')->label('Ville'),
                        TextInput::make('address_postal_code')->label('Code postal'),
                        TextInput::make('address_country')->label('Pays'),
                        FileUpload::make('proof_of_address_path')
                            ->label('Justificatif de domicile')
                            ->image()
                            ->disk('public')
                            ->directory('kyc/address')
                            ->downloadable()
                            ->openable()
                            ->visibility('public'),
                    ])->columns(2),

                Section::make('KYC - Étape 3 : Pièce d’identité')
                    ->schema([
                        Select::make('id_document_type')
                            ->options([
                                'CNI' => 'Carte nationale d\'identité',
                                'PASSEPORT' => 'Passeport',
                                'PERMIS_CONDUIRE' => 'Permis de conduire',
                            ])->label('Type de document'),
                        TextInput::make('id_document_number')->label('Numéro du document'),
                        FileUpload::make('id_document_front_path')
                            ->label('Recto')
                            ->image()
                            ->disk('public')
                            ->directory('kyc/id')
                            ->downloadable()
                            ->openable(),
                        FileUpload::make('id_document_back_path')
                            ->label('Verso')
                            ->image()
                            ->disk('public')
                            ->directory('kyc/id')
                            ->downloadable()
                            ->openable(),
                    ])->columns(2),

                Section::make('KYC - Étape 4 : Selfie et documents complémentaires')
                    ->schema([
                        FileUpload::make('selfie_with_id_path')
                            ->label('Selfie avec la pièce d’identité')
                            ->image()
                            ->disk('public')
                            ->directory('kyc/selfie')
                            ->downloadable()
                            ->openable(),
                        FileUpload::make('additional_document_path')
                            ->label('Document supplémentaire')
                            ->disk('public')
                            ->directory('kyc/extra')
                            ->downloadable(),
                    ])->columns(2),

                Section::make('Informations de validation')
                    ->schema([
                        Textarea::make('kyc_rejection_reason')->label('Motif de rejet'),
                        DatePicker::make('kyc_submitted_at')->label('Date de soumission'),
                        DatePicker::make('kyc_validated_at')->label('Date de validation finale'),
                        TextInput::make('kyc_validated_by')->label('Validé par (ID)')->numeric(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')->searchable()->sortable(),
                TextColumn::make('prenom')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('telephone')->searchable(),
                TextColumn::make('pays')->searchable(),
                TextColumn::make('role')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ADMIN' => 'success',
                        'SUPPORT' => 'warning',
                        'CONFORMITE' => 'info',
                        'FINANCE' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('statut_compte')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ACTIF' => 'success',
                        'BLOQUE' => 'danger',
                        'SUSPENDU' => 'warning',
                        default => 'secondary',
                    }),
                TextColumn::make('kyc_status')
                    ->label('KYC')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'NOT_STARTED' => 'gray',
                        'STEP1_PENDING', 'STEP2_PENDING', 'STEP3_PENDING', 'STEP4_PENDING' => 'warning',
                        'STEP1_REJECTED', 'STEP2_REJECTED', 'STEP3_REJECTED', 'STEP4_REJECTED' => 'danger',
                        'STEP1_VALIDATED', 'STEP2_VALIDATED', 'STEP3_VALIDATED' => 'info',
                        'COMPLETED' => 'success',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'NOT_STARTED' => 'Non commencé',
                        'STEP1_PENDING' => 'Étape 1 en attente',
                        'STEP1_REJECTED' => 'Étape 1 rejetée',
                        'STEP1_VALIDATED' => 'Étape 1 validée',
                        'STEP2_PENDING' => 'Étape 2 en attente',
                        'STEP2_REJECTED' => 'Étape 2 rejetée',
                        'STEP2_VALIDATED' => 'Étape 2 validée',
                        'STEP3_PENDING' => 'Étape 3 en attente',
                        'STEP3_REJECTED' => 'Étape 3 rejetée',
                        'STEP3_VALIDATED' => 'Étape 3 validée',
                        'STEP4_PENDING' => 'Étape 4 en attente',
                        'COMPLETED' => 'KYC complet',
                        default => $state,
                    }),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')->options([
                    'CLIENT' => 'Client',
                    'ADMIN' => 'Administrateur',
                    'SUPPORT' => 'Support',
                    'CONFORMITE' => 'Conformité',
                    'FINANCE' => 'Finance',
                ]),
                Tables\Filters\SelectFilter::make('statut_compte')->options([
                    'ACTIF' => 'Actif',
                    'SUSPENDU' => 'Suspendu',
                    'BLOQUE' => 'Bloqué',
                    'EN_ATTENTE' => 'En attente',
                ]),
                Tables\Filters\SelectFilter::make('kyc_status')
                    ->label('Statut KYC')
                    ->options([
                        'NOT_STARTED' => 'Non commencé',
                        'STEP1_PENDING' => 'Étape 1 en attente',
                        'STEP1_VALIDATED' => 'Étape 1 validée',
                        'STEP1_REJECTED' => 'Étape 1 rejetée',
                        'STEP2_PENDING' => 'Étape 2 en attente',
                        'STEP2_VALIDATED' => 'Étape 2 validée',
                        'STEP2_REJECTED' => 'Étape 2 rejetée',
                        'STEP3_PENDING' => 'Étape 3 en attente',
                        'STEP3_VALIDATED' => 'Étape 3 validée',
                        'STEP3_REJECTED' => 'Étape 3 rejetée',
                        'STEP4_PENDING' => 'Étape 4 en attente',
                        'COMPLETED' => 'KYC complet',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    // Bouton pour voir le KYC
                    Action::make('view_kyc')
                        ->label('Voir KYC')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn ($record) => static::getUrl('view-kyc', ['record' => $record]))
                        ->openUrlInNewTab(false),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view-kyc' => Pages\ViewKyc::route('/{record}/kyc'),
        ];
    }
}