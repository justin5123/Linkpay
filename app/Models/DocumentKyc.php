<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentKyc extends Model
{
    use HasFactory;
    protected $table = 'documents_kyc';

    protected $fillable = [
    'users_id',   // ou 'users_id' selon votre table
    'type_document',
    'numero_document',
    'image_recto',
    'image_verso',
    'image_selfie',
    'score_similarite',
    'statut',
    'motif_rejet',
    'valide_par',
    'date_soumission',
    'date_validation',
];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
