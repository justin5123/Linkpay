<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoyenPaiement extends Model
{
    use HasFactory;

    protected $table = 'moyens_paiements';

    protected $fillable = [
        'users_id',
        'type',
        'fournisseur',
        'identifiant_compte',
        'pays',
        'devise',
        'est_verifie',
        'est_principal',
        'est_actif',
    ];

    protected $casts = [
        'est_verifie' => 'boolean',
        'est_principal' => 'boolean',
        'est_actif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
