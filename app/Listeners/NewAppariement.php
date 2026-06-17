<?php

namespace App\Listeners;

use App\Events\NewAppariement;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAppariementMail;

class SendNewAppariementNotification
{
    public function handle(NewAppariement $event)
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
                'titre' => 'Nouvel appariement',
                'message' => 'Un appariement a été trouvé pour votre annonce. Connectez-vous pour l\'accepter.',
                'canal' => 'APP',
                'priorite' => 'NORMALE',
                'est_lu' => false,
            ]);

            // Email
            Mail::to($user->email)->send(new NewAppariementMail($appariement));
        }
    }
}