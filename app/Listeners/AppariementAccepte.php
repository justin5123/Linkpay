<?php

namespace App\Listeners;

use App\Events\AppariementAccepte;
use App\Events\NewAppariement;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAppariementMail;

class SendAppariementAccepteNotification
{
    public function handle(AppariementAccepte $event)
    {
        $appariement = $event->appariement;
        $utilisateurs = [
            $appariement->annonceEnvoi->user,
            $appariement->annonceReception->user,
        ];

        foreach ($utilisateurs as $user) {
            // Notification in-app
            Notification::create([
                'users_id' => $user->id,
                'type' => 'MATCHING',
                'titre' => 'Appariement accepté',
                'message' => 'Un appariement a été accepté pour votre annonce.',
                'canal' => 'APP',
                'priorite' => 'NORMALE',
                'est_lu' => false,
            ]);

            // Email
            Mail::to($user->email)->send(new NewAppariementMail($appariement));
        }
    }
}