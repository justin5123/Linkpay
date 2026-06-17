<?php

namespace App\Http\Controllers;

use App\Models\TransactionCompensee;
use App\Models\User;
use App\Models\Notification; // Utiliser notre propre modèle
use App\Events\LitigeSignale;
use Illuminate\Http\Request;

class LitigeController extends Controller
{
    public function signaler(Request $request, TransactionCompensee $transaction)
    {
        $user = auth()->user();
        if ($user->id != $transaction->payeur_a_id && $user->id != $transaction->payeur_b_id) {
            abort(403);
        }

        $request->validate(['motif' => 'required|string|min:10']);

        $transaction->update([
            'statut' => 'LITIGE',
            'litige_par' => $user->id,
            'motif_litige' => $request->motif,
            'date_litige' => now(),
        ]);

        // Notifier tous les administrateurs (rôle 'ADMIN') via notre propre table de notifications
        $admins = User::where('role', 'ADMIN')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'users_id' => $admin->id,
                'type' => 'LITIGE',
                'titre' => 'Nouveau litige signalé',
                'message' => "Transaction {$transaction->reference} : {$request->motif}",
                'canal' => 'APP',
                'priorite' => 'ELEVEE',
                'est_lu' => false,
            ]);
        }

        // Déclencher l'événement pour d'autres notifications (email, etc.)
        event(new LitigeSignale($transaction));

        return back()->with('success', 'Litige signalé. Un administrateur va traiter votre demande.');
    }
}