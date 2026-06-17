<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_compensee_id',
        'users_id',
        'montant',
        'preuve',
        'date_paiement',
        'statut'
    ];

    public function transaction()
    {
        return $this->belongsTo(
            TransactionCompensee::class,
            'transaction_compensee_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'users_id'
        );
    }
}