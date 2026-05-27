<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Follow extends Model
{
    use HasFactory;

    protected $table = 'follows';

    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Celui qui suit
    public function follower()
    {
        return $this->belongsTo(
            User::class,
            'follower_id'
        );
    }

    // Celui qui est suivi
    public function following()
    {
        return $this->belongsTo(
            User::class,
            'following_id'
        );
    }
}
