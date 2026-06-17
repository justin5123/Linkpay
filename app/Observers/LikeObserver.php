<?php

namespace App\Observers;

use App\Models\Like;
use App\Models\Notification;
use App\Events\NotificationSent;

class LikeObserver
{
    public function created(Like $like)
    {
        // Incrémenter le compteur du post
        $like->post->incrementLikesCount();

        // Créer une notification pour l'auteur du post (sauf si c'est lui-même)
        if ($like->post->users_id !== $like->users_id) {
            $notification = Notification::create([
                'users_id' => $like->post->users_id,
                'type' => 'SOCIAL',
                'titre' => $like->user->prenom . ' a aimé votre publication',
                'message' => 'Votre post "' . substr($like->post->contenu, 0, 50) . '" a reçu un like',
                'lien_action' => route('posts.show', $like->post_id),
                'canal' => 'APP'
            ]);
            event(new NotificationSent($notification));
        }
    }

    public function deleted(Like $like)
    {
        $like->post->decrementLikesCount();
    }
}