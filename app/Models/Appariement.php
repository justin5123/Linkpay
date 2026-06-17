<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Appariement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'annonce_envoi_id',
        'annonce_reception_id',
        'montant_compense',
        'statut',
        'reference',
    ];

    public function annonceEnvoi()
    {
        return $this->belongsTo(
            Annonce::class,
            'annonce_envoi_id'
        );
    }

    public function transactionCompensee()
    {
        return $this->hasOne(TransactionCompensee::class);
    }
    public function annonceReception()
    {
        return $this->belongsTo(
            Annonce::class,
            'annonce_reception_id'
        );
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function cycleCompensation()
    {
        return $this->belongsTo(
            CycleCompensation::class
        );
    }
}
