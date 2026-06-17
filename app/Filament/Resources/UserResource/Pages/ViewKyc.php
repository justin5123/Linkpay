<?php

namespace App\Filament\Resources\UserResource\Pages;

use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use App\Models\User;
use App\Models\Notification as NotificationModel; // Ajout
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ViewKyc extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.view-kyc';

    public User $user;

    public function mount($record): void
    {
        $this->user = User::findOrFail($record);
        $this->fillForm();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Étape 1 – Identité')
                    ->schema([
                        TextInput::make('identity_first_name')
                            ->label('Prénom')
                            ->disabled()
                            ->default($this->user->identity_first_name),
                        TextInput::make('identity_last_name')
                            ->label('Nom')
                            ->disabled()
                            ->default($this->user->identity_last_name),
                        DatePicker::make('identity_birth_date')
                            ->label('Date de naissance')
                            ->disabled()
                            ->default($this->user->identity_birth_date),
                        TextInput::make('identity_birth_place')
                            ->label('Lieu de naissance')
                            ->disabled()
                            ->default($this->user->identity_birth_place),
                        TextInput::make('identity_nationality')
                            ->label('Nationalité')
                            ->disabled()
                            ->default($this->user->identity_nationality),
                        Placeholder::make('step1_status')
                            ->label('Statut')
                            ->content($this->getStatusText(1)),
                    ])->columns(2)
                    ->headerActions($this->getStepActions(1)),

                Section::make('Étape 2 – Justificatif de domicile')
                    ->schema([
                        TextInput::make('address_street')
                            ->label('Rue')
                            ->disabled()
                            ->default($this->user->address_street),
                        TextInput::make('address_city')
                            ->label('Ville')
                            ->disabled()
                            ->default($this->user->address_city),
                        TextInput::make('address_postal_code')
                            ->label('Code postal')
                            ->disabled()
                            ->default($this->user->address_postal_code),
                        TextInput::make('address_country')
                            ->label('Pays')
                            ->disabled()
                            ->default($this->user->address_country),
                        $this->getFileField('proof_of_address_path', 'Justificatif de domicile'),
                        Placeholder::make('step2_status')
                            ->label('Statut')
                            ->content($this->getStatusText(2)),
                    ])->columns(2)
                    ->headerActions($this->getStepActions(2)),

                Section::make('Étape 3 – Pièce d’identité')
                    ->schema([
                        TextInput::make('id_document_type')
                            ->label('Type de document')
                            ->disabled()
                            ->default($this->user->id_document_type),
                        TextInput::make('id_document_number')
                            ->label('Numéro')
                            ->disabled()
                            ->default($this->user->id_document_number),
                        $this->getFileField('id_document_front_path', 'Recto'),
                        $this->getFileField('id_document_back_path', 'Verso'),
                        Placeholder::make('step3_status')
                            ->label('Statut')
                            ->content($this->getStatusText(3)),
                    ])->columns(2)
                    ->headerActions($this->getStepActions(3)),

                Section::make('Étape 4 – Selfie et finalisation')
                    ->schema([
                        $this->getFileField('selfie_with_id_path', 'Selfie'),
                        $this->getFileField('additional_document_path', 'Document supplémentaire'),
                        Placeholder::make('step4_status')
                            ->label('Statut')
                            ->content($this->getStatusText(4)),
                    ])->columns(2)
                    ->headerActions($this->getStepActions(4)),
            ]);
    }

    protected function getStatusText(int $step): string
    {
        $status = $this->user->kyc_status;
        if ($status === "STEP{$step}_VALIDATED") {
            return '✅ Étape validée';
        }
        if ($status === "STEP{$step}_REJECTED") {
            $reason = $this->user->kyc_rejection_reason ?? 'Aucun motif fourni';
            return "❌ Étape rejetée (Motif : $reason)";
        }
        if ($status === "STEP{$step}_PENDING") {
            return '⏳ En attente de validation';
        }
        return '⚪ Non commencée';
    }

    protected function getFileField(string $column, string $label): Placeholder
    {
        $path = $this->user->$column;
        if (!$path) {
            return Placeholder::make($column)
                ->label($label)
                ->content('Aucun fichier soumis');
        }
        $url = Storage::disk('public')->url($path);
        return Placeholder::make($column)
            ->label($label)
            ->content(new HtmlString('<a href="' . $url . '" target="_blank" class="text-emerald-600 hover:underline">📄 Voir le fichier</a>'));
    }

    protected function getStepActions(int $step): array
    {
        if ($this->user->kyc_status !== "STEP{$step}_PENDING") {
            return [];
        }
        return [
            $this->getValidateAction($step),
            $this->getRejectAction($step),
        ];
    }

    protected function getValidateAction(int $step): FormAction
    {
        $nextStatus = "STEP{$step}_VALIDATED";
        return FormAction::make("validate_$step")
            ->label("Valider l’étape $step")
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->action(function () use ($step, $nextStatus) {
                $this->user->update(['kyc_status' => $nextStatus]);
                $this->user->refresh();
                $this->fillForm(); // Recharge le formulaire

                // 🔔 Notification à l'utilisateur
                NotificationModel::create([
                    'users_id' => $this->user->id,
                    'type' => 'KYC',
                    'titre' => "Étape $step KYC validée",
                    'canal' => 'APP',
                    'message' => "Félicitations ! Votre étape $step de vérification KYC a été validée par l'administrateur.",
                    'est_lu' => false,
                    'priorite' => 'NORMALE',
                    'lien_action' => route('kyc.status'),
                ]);

                Notification::make()->title("Étape $step validée")->success()->send();
            });
    }

    protected function getRejectAction(int $step): FormAction
    {
        return FormAction::make("reject_$step")
            ->label("Rejeter l’étape $step")
            ->color('danger')
            ->icon('heroicon-o-x-circle')
            ->form([
                \Filament\Forms\Components\Textarea::make('reason')->required()->label('Motif du rejet'),
            ])
            ->action(function (array $data) use ($step) {
                $this->user->update([
                    'kyc_status' => "STEP{$step}_REJECTED",
                    'kyc_rejection_reason' => $data['reason'],
                ]);
                $this->user->refresh();
                $this->fillForm(); // Recharge le formulaire

                // 🔔 Notification de rejet à l'utilisateur
                NotificationModel::create([
                    'users_id' => $this->user->id,
                    'type' => 'KYC',
                    'titre' => "Étape $step KYC rejetée",
                    'canal' => 'APP',
                    'message' => "Votre étape $step de vérification KYC a été rejetée. Motif : " . $data['reason'],
                    'est_lu' => false,
                    'priorite' => 'ELEVEE',
                    'lien_action' => route('kyc.status'),
                ]);

                Notification::make()->title("Étape $step rejetée")->danger()->send();
            });
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'identity_first_name' => $this->user->identity_first_name,
            'identity_last_name' => $this->user->identity_last_name,
            'identity_birth_date' => $this->user->identity_birth_date,
            'identity_birth_place' => $this->user->identity_birth_place,
            'identity_nationality' => $this->user->identity_nationality,
            'address_street' => $this->user->address_street,
            'address_city' => $this->user->address_city,
            'address_postal_code' => $this->user->address_postal_code,
            'address_country' => $this->user->address_country,
            'proof_of_address_path' => $this->user->proof_of_address_path,
            'id_document_type' => $this->user->id_document_type,
            'id_document_number' => $this->user->id_document_number,
            'id_document_front_path' => $this->user->id_document_front_path,
            'id_document_back_path' => $this->user->id_document_back_path,
            'selfie_with_id_path' => $this->user->selfie_with_id_path,
            'additional_document_path' => $this->user->additional_document_path,
        ]);
    }
}