<?php

namespace App\Filament\Pages;

use App\Models\SupportTicket;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MessageSupport;
use Filament\Notifications\Notification;

class SupportTicketConversation extends Page
{
    protected static string $view = 'filament.pages.support-ticket-conversation';
    protected static ?string $title = 'Conversation support';
    protected static ?string $slug = 'support-ticket-conversation';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public SupportTicket $ticket;

    public function mount(): void
    {
        $ticketId = request()->query('ticket');
        if (!$ticketId) {
            abort(404);
        }
        $this->ticket = SupportTicket::with('user', 'messages.expediteur')->findOrFail($ticketId);
        // Marquer les messages reçus comme lus
        $this->ticket->messages()->where('destinataire_id', Auth::id())->update(['est_lu' => true]);
    }

    public function sendReply(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:support_tickets,id',
            'reply' => 'required|string',
        ]);

        $ticket = SupportTicket::findOrFail($request->ticket_id);

        $message = MessageSupport::create([
            'support_ticket_id' => $ticket->id,
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $ticket->users_id,
            'message' => $request->reply,
            'est_lu' => false,
        ]);

        // Notification utilisateur
        $ticket->user->notifications()->create([
            'type' => 'SUPPORT',
            'titre' => 'Nouvelle réponse sur votre ticket',
            'message' => "Un agent a répondu à votre ticket {$ticket->reference}",
            'canal' => 'APP',
            'priorite' => 'NORMALE',
            'est_lu' => false,
        ]);

        event(new \App\Events\NewSupportMessage($ticket, $message));

        Notification::make()
            ->title('Réponse envoyée')
            ->success()
            ->send();

        return redirect()->back();
    }

    protected function getViewData(): array
    {
        return [
            'ticket' => $this->ticket,
        ];
    }
}