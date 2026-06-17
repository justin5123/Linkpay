<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'type',
        'montant_source',
        'montant_cible',
        'devise_source',
        'devise_cible',
        'pays_source',
        'pays_destination',
        'taux_change',
        'beneficiaire_nom',
        'beneficiaire_telephone',
        'beneficiaire_email',
        'statut',
        'est_appariee',
        'expire_le',
    ];

    protected $casts = [
        'est_appariee' => 'boolean',
        'montant_source' => 'decimal:2',
        'montant_cible' => 'decimal:2',
        'taux_change' => 'decimal:6',
        'expire_le' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'users_id'
        );
    }

    public function appariementsEnvoi()
    {
        return $this->hasMany(
            Appariement::class,
            'annonce_envoi_id'
        );
    }

    public function appariementsReception()
    {
        return $this->hasMany(
            Appariement::class,
            'annonce_reception_id'
        );
    }

    public function estExpiree(): bool
    {
        return $this->expire_le !== null
            && $this->expire_le->isPast();
    }
}