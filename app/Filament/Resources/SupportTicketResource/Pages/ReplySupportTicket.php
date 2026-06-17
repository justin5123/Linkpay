<?php

namespace App\Filament\Resources\SupportTicketResource\Pages;

use App\Filament\Resources\SupportTicketResource;
use App\Models\MessageSupport;
use App\Models\Notification;
use App\Models\SupportTicket;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Auth;

class ReplySupportTicket extends Page
{
    protected static string $resource = SupportTicketResource::class;
    protected static string $view = 'filament.resources.support-ticket-resource.pages.reply-support-ticket';

    public SupportTicket $ticket;

    public function mount($record): void
    {
        $this->ticket = SupportTicket::with('user', 'messages.expediteur')->findOrFail($record);
    }

    public function sendMessage()
    {
        $data = request()->validate([
            'newMessage' => 'required|string|min:1',
        ]);

        $message = MessageSupport::create([
            'support_ticket_id' => $this->ticket->id,
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $this->ticket->users_id,
            'message' => $data['newMessage'],
            'est_lu' => false,
        ]);

        if ($this->ticket->statut === 'OUVERT') {
            $this->ticket->update(['statut' => 'EN_COURS']);
        }

        // Notification pour l'utilisateur
        $this->ticket->user->notifications()->create([
            'type' => 'SUPPORT',
            'titre' => 'Nouvelle réponse du support',
            'message' => 'Un agent a répondu à votre ticket #' . $this->ticket->reference,
            'canal' => 'APP',
            'priorite' => 'NORMALE',
            'est_lu' => false,
            'lien_action' => route('support.show', $this->ticket->id),
        ]);

        // Notification flash pour l'admin
        FilamentNotification::make()
            ->title('Message envoyé')
            ->success()
            ->send();

        return redirect()->to(static::getUrl(['record' => $this->ticket->id]));
    }

    public function markAsResolved()
    {
        $this->ticket->update(['statut' => 'RESOLU', 'date_resolution' => now()]);
        FilamentNotification::make()
            ->title('Ticket marqué comme résolu')
            ->success()
            ->send();
        return redirect()->to(static::getUrl(['record' => $this->ticket->id]));
    }

    public function closeTicket()
    {
        $this->ticket->update(['statut' => 'FERME', 'date_fermeture' => now()]);
        FilamentNotification::make()
            ->title('Ticket fermé')
            ->success()
            ->send();
        return redirect()->to(static::getUrl(['record' => $this->ticket->id]));
    }
}