<?php

use App\Models\SupportTicket;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('support-ticket.{ticketId}', function ($user, $ticketId) {
    $ticket = SupportTicket::find($ticketId);
    if (!$ticket) return false;
    // L'utilisateur doit être soit le propriétaire soit un admin/support
    return $user->id === $ticket->users_id || in_array($user->role, ['ADMIN', 'SUPPORT']);
});