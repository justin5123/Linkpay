<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentKyc extends Model
{
    use HasFactory;
    protected $table = 'documents_kyc';

    protected $fillable = [
        'users_id',
        'type_document',
        'numero_document',
        'document_recto',
        'document_verso',
        'selfie_verification',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
