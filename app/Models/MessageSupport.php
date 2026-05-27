<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MessageSupport extends Model
{
    use HasFactory;

    protected $table = 'messages_support';

    protected $fillable = [
        'support_ticket_id',
        'expediteur_id',
        'destinataire_id',
        'message',
        'piece_jointe',
        'est_lu',
        'date_lecture',
    ];

    protected $casts = [
        'est_lu' => 'boolean',
        'date_lecture' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(
            SupportTicket::class,
            'support_ticket_id'
        );
    }

    public function expediteur()
    {
        return $this->belongsTo(
            User::class,
            'expediteur_id'
        );
    }

    public function destinataire()
    {
        return $this->belongsTo(
            User::class,
            'destinataire_id'
        );
    }
}
