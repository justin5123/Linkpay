<?php

namespace App\Observers;

use App\Models\Follow;
use App\Models\Notification;

class FollowObserver
{
    public function created(Follow $follow)
    {
        if ($follow->follower_id !== $follow->following_id) {
            $notification = Notification::create([
                'users_id' => $follow->following_id,
                'type' => 'SOCIAL',
                'titre' => 'Nouveau follower',
                'message' => $follow->follower->prenom . ' a commencé à vous suivre',
                'lien_action' => route('profile.show', $follow->follower_id),
                'canal' => 'APP'
            ]);
            event(new Notification($notification));
        }
    }
}