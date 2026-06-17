<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['users_id', 'contenu', 'media', 'likes_count', 'comments_count', 'shares_count'];
    protected $casts = [
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id')->latest();
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'post_id');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('users_id', $user->id)->exists();
    }

    public function isSharedBy(User $user)
    {
        return $this->shares()->where('user_id', $user->id)->exists();
    }

    // Helper pour incrémenter/décrémenter les compteurs (utilisé par les événements)
    public function incrementLikesCount()
    {
        $this->increment('likes_count');
    }

    public function decrementLikesCount()
    {
        $this->decrement('likes_count');
    }

    public function incrementCommentsCount()
    {
        $this->increment('comments_count');
    }

    public function decrementCommentsCount()
    {
        $this->decrement('comments_count');
    }

    public function incrementSharesCount()
    {
        $this->increment('shares_count');
    }

    public function decrementSharesCount()
    {
        $this->decrement('shares_count');
    }
}