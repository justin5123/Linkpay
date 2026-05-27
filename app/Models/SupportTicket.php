<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'assigne_a',
        'categorie',
        'sujet',
        'description',
        'priorite',
        'statut',
        'reference',
        'date_resolution',
        'date_fermeture',
    ];

    protected $casts = [
        'date_resolution' => 'datetime',
        'date_fermeture' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function agentSupport()
    {
        return $this->belongsTo(User::class, 'assigne_a');
    }

    public function messages()
    {
        return $this->hasMany(MessageSupport::class);
    }
}
