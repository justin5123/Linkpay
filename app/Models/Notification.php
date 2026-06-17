<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'users_id',
        'type',
        'canal',
        'titre',
        'message',
        'est_lu',
        'priorite',
        'lien_action',
        'date_lecture',
    ];

    protected $casts = [
        'est_lu' => 'boolean',
        'date_lecture' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    protected static function booted()
    {
        static::created(function ($notification) {
            broadcast(new \App\Events\NotificationSent($notification));
        });
    }
}
