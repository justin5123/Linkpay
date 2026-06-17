<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionCompensee extends Model
{
    use HasFactory;
    protected $table = 'transactions_compensees';

    
    protected $fillable = [
        'reference',
        'appariement_id',
        'payeur_a_id',
        'payeur_b_id',
        'montant_a',
        'montant_b',
        'statut',
        'date_debut',
        'date_fin',
    ];

    public function appariement()
    {
        return $this->belongsTo(
            Appariement::class
        );
    }
    public function scopeLitiges($query)
    {
        return $query->where('statut', 'LITIGE');
    }
    public function payeurA()
    {
        return $this->belongsTo(
            User::class,
            'payeur_a_id'
        );
    }

    public function payeurB()
    {
        return $this->belongsTo(
            User::class,
            'payeur_b_id'
        );
    }

    public function paiements()
    {
        return $this->hasMany(
            Paiement::class,
            'transaction_compensee_id'
        );
    }
}