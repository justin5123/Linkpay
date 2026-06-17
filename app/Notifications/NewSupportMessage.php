<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use App\Models\MessageSupport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSupportMessage extends Notification
{
    use Queueable;

    protected $ticket;
    protected $message;

    public function __construct(SupportTicket $ticket, MessageSupport $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // ou 'mail'
    }

    public function toDatabase($notifiable)
    {
        return [
            'titre' => 'Nouvelle réponse sur votre ticket',
            'message' => 'Un administrateur a répondu à votre ticket "' . $this->ticket->sujet . '"',
            'ticket_id' => $this->ticket->id,
        ];
    }
}