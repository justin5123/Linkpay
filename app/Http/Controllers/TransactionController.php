<?php

namespace App\Http\Controllers;

use App\Models\TransactionCompensee;
use App\Models\Wallet;
use App\Events\PaiementConfirme;
use App\Events\TransactionTerminee;

class TransactionController extends Controller
{
    public function confirmerReception(TransactionCompensee $transaction)
    {
        $user = auth()->user();

        if ($transaction->statut == 'PAYER_A' && $user->id == $transaction->payeur_b_id) {
            // B confirme avoir reçu le paiement de A
            $transaction->statut = 'PAYER_B';
            $transaction->save();
            event(new PaiementConfirme($transaction, $user));
            return back()->with('success', 'Paiement confirmé. En attente de l\'autre paiement.');
        } 
        elseif ($transaction->statut == 'PAYER_B' && $user->id == $transaction->payeur_a_id) {
            // A confirme avoir reçu le paiement de B -> transaction terminée
            $transaction->statut = 'TERMINEE';
            $transaction->date_fin = now();
            $transaction->save();

            // Mise à jour des wallets (débit et crédit)
            $this->updateWallets($transaction);

            event(new TransactionTerminee($transaction));
            return back()->with('success', 'Transaction terminée !');
        }

        return back()->with('error', 'Action non autorisée.');
    }

    private function updateWallets(TransactionCompensee $transaction)
    {
        $app = $transaction->appariement;
        $envoi = $app->annonceEnvoi;
        $reception = $app->annonceReception;

        // Helper pour obtenir ou créer un wallet
        $getWallet = function($userId, $devise) {
            return Wallet::firstOrCreate(
                ['users_id' => $userId, 'devise' => $devise],
                [
                    'solde' => 0,
                    'numero_compte' => 'LIN' . uniqid(),
                    'pin_wallet' => bcrypt('0000'),
                    'statut' => 'ACTIF',
                    'est_actif' => 1
                ]
            );
        };

        // ========== PAYEUR A (celui qui envoie la devise source de l'envoi) ==========
        // Débiter sa devise source
        $walletA_source = $getWallet($transaction->payeur_a_id, $envoi->devise_source);
        if ($walletA_source->solde < $transaction->montant_a) {
            throw new \Exception("Solde insuffisant pour le payeur A");
        }
        $walletA_source->solde -= $transaction->montant_a;
        $walletA_source->save();

        // Créditer sa devise cible
        $walletA_cible = $getWallet($transaction->payeur_a_id, $envoi->devise_cible);
        $montantRecuA = $transaction->montant_a * $envoi->taux_change;
        $walletA_cible->solde += $montantRecuA;
        $walletA_cible->save();

        // ========== PAYEUR B (celui qui envoie la devise source de la réception) ==========
        // Débiter sa devise source
        $walletB_source = $getWallet($transaction->payeur_b_id, $reception->devise_source);
        if ($walletB_source->solde < $transaction->montant_b) {
            throw new \Exception("Solde insuffisant pour le payeur B");
        }
        $walletB_source->solde -= $transaction->montant_b;
        $walletB_source->save();

        // Créditer sa devise cible
        $walletB_cible = $getWallet($transaction->payeur_b_id, $reception->devise_cible);
        $montantRecuB = $transaction->montant_b * $reception->taux_change;
        $walletB_cible->solde += $montantRecuB;
        $walletB_cible->save();
    }
}