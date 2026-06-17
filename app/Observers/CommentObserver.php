<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Notification;

class CommentObserver
{
    public function created(Comment $comment)
    {
        $comment->post->incrementCommentsCount();

        if ($comment->post->users_id !== $comment->users_id) {
            $notification = Notification::create([
                'users_id' => $comment->post->users_id,
                'type' => 'SOCIAL',
                'titre' => $comment->user->prenom . ' a commenté votre publication',
                'message' => substr($comment->contenu, 0, 100),
                'lien_action' => route('posts.show', $comment->post_id),
                'canal' => 'APP'
            ]);
            event(new Notification($notification));
        }
    }

    public function deleted(Comment $comment)
    {
        $comment->post->decrementCommentsCount();
    }
}