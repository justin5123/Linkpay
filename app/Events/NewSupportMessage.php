<?php

namespace App\Events;

use App\Models\SupportTicket;
use App\Models\MessageSupport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSupportMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(SupportTicket $ticket, MessageSupport $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('ticket.' . $this->ticket->id);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'new-message';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user_id' => $this->message->expediteur_id,
            'user_name' => $this->message->expediteur->prenom . ' ' . $this->message->expediteur->nom,
            'created_at' => $this->message->created_at->format('H:i'),
        ];
    }
}