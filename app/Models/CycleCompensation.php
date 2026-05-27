<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;




class CycleCompensation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'date_debut',
        'date_fin',
        'statut',
        'montant_total_envoi',
        'montant_total_reception',
        'solde_net',
        'nombre_appariements',
        'fonds_liquidite_utilise',
        'valide_par',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function appariements()
    {
        return $this->hasMany(Appariement::class);
    }

    public function validateur()
    {
        return $this->belongsTo(
            User::class,
            'valide_par'
        );
    }
}
