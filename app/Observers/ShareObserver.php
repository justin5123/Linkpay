<?php

namespace App\Observers;

use App\Models\Share;
use App\Models\Notification;

class ShareObserver
{
    public function created(Share $share)
    {
        $share->post->incrementSharesCount();

        if ($share->post->users_id !== $share->user_id) {
            $notification = Notification::create([
                'users_id' => $share->post->users_id,
                'type' => 'SOCIAL',
                'titre' => $share->user->prenom . ' a partagé votre publication',
                'message' => $share->commentaire ?? 'Partage sans commentaire',
                'lien_action' => route('posts.show', $share->post_id),
                'canal' => 'APP'
            ]);
            event(new Notification($notification));
        }
    }

    public function deleted(Share $share)
    {
        $share->post->decrementSharesCount();
    }
}