<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $fillable = ['users_id', 'solde', 'numero_compte', 'devise', 'pin_wallet', 'statut', 'est_actif', 'tentatives_pin_echouees', 'bloque_jusqua'];

    // Relation inverse : un wallet appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}