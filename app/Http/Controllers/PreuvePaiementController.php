<?php

namespace App\Http\Controllers;

use App\Models\TransactionCompensee;
use App\Models\Paiement;
use App\Events\PreuveDeposee;
use Illuminate\Http\Request;

class PreuvePaiementController extends Controller
{
    public function store(Request $request, TransactionCompensee $transaction)
    {
        $user = auth()->user();

        // Vérifier que l'utilisateur est bien l'un des payeurs
        if ($user->id != $transaction->payeur_a_id && $user->id != $transaction->payeur_b_id) {
            abort(403);
        }

        $request->validate([
            'preuve' => 'required|file|mimes:jpg,png,jpeg,pdf|max:5120',
        ]);

        // Vérifier que la transaction est dans un état autorisé
        if (!in_array($transaction->statut, ['EN_ATTENTE', 'PAYER_A', 'PAYER_B'])) {
            return back()->with('error', 'Transaction déjà finalisée ou annulée.');
        }

        // Empêcher de déposer deux fois la même preuve pour le même payeur
        $paiementExistant = Paiement::where('transaction_compensee_id', $transaction->id)
            ->where('users_id', $user->id)
            ->exists();
        if ($paiementExistant) {
            return back()->with('error', 'Vous avez déjà déposé une preuve pour cette transaction.');
        }

        $montantPaye = ($user->id == $transaction->payeur_a_id) ? $transaction->montant_a : $transaction->montant_b;
        $path = $request->file('preuve')->store('preuves/' . $transaction->id, 'public');

        $paiement = Paiement::create([
            'transaction_compensee_id' => $transaction->id,
            'users_id' => $user->id,
            'montant' => $montantPaye,
            'preuve' => $path,
            'date_paiement' => now(),
            'statut' => 'EN_ATTENTE',
        ]);

        // Mettre à jour le statut de la transaction si c'est le premier paiement
        if ($transaction->statut == 'EN_ATTENTE') {
            $nouveauStatut = ($user->id == $transaction->payeur_a_id) ? 'PAYER_A' : 'PAYER_B';
            $transaction->update(['statut' => $nouveauStatut]);
        }

        // Déclencher l'événement pour notifier l'autre partie
        event(new PreuveDeposee($transaction, $user, $paiement));

        return back()->with('success', 'Preuve déposée. En attente de confirmation par l\'autre partie.');
    }
}