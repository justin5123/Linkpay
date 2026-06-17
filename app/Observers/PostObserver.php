<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Share;

class PostObserver
{
    // Pas nécessaire de recalculer à chaque update, on utilisera des événements séparés.
    // Mais on peut initialiser les compteurs à la création.
    public function created(Post $post)
    {
        $post->likes_count = 0;
        $post->comments_count = 0;
        $post->shares_count = 0;
        $post->saveQuietly(); // éviter les boucles
    }
}