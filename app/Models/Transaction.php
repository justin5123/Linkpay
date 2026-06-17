<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'users_id',
        'annonces_id',
        'appariement_id',
        'montant',
        'devise',
        'type',
        'statut',
        'reference',
        'description',
        'date_traitement',
        'methode_paiement',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function appariement()
    {
        return $this->belongsTo(Appariement::class);
    }
    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }
}
