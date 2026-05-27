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
        'statut',
        'est_appariee',
        'espire_le',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
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
}
