<?php

namespace App\Http\Controllers;

use App\Models\Appariement;
use App\Models\TransactionCompensee;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Notification;
use App\Events\AppariementAccepte;
use App\Events\TransactionCompenseeCreated;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionNotificationMail;

class AppariementController extends Controller
{
    /**
     * Accepter un appariement par l'une des deux parties
     */
    public function accepter(Appariement $appariement)
    {
        $user = auth()->user();
        $annonceA = $appariement->annonceEnvoi;      // première annonce ENVOI
        $annonceB = $appariement->annonceReception;  // deuxième annonce ENVOI

        // Vérifier que l'utilisateur est bien l'une des deux parties
        if ($user->id != $annonceA->users_id && $user->id != $annonceB->users_id) {
            abort(403);
        }

        // Empêcher la double acceptation
        if ($user->id == $annonceA->users_id && $appariement->accepte_par_emetteur) {
            return redirect()->route('dashboard')->with('error', '⚠️ Vous avez déjà accepté cet appariement.');
        }
        if ($user->id == $annonceB->users_id && $appariement->accepte_par_recepteur) {
            return redirect()->route('dashboard')->with('error', '⚠️ Vous avez déjà accepté cet appariement.');
        }

        // Marquer l'acceptation
        if ($user->id == $annonceA->users_id) {
            $appariement->accepte_par_emetteur = true;
        } else {
            $appariement->accepte_par_recepteur = true;
        }
        $appariement->save();

        // Événement pour notification in-app (acceptation)
        event(new AppariementAccepte($appariement, $user));

        // Si les deux ont accepté, exécuter la transaction interne
        if ($appariement->accepte_par_emetteur && $appariement->accepte_par_recepteur) {
            DB::beginTransaction();
            try {
                // Vérifier qu'aucune transaction n'existe déjà pour cet appariement
                if ($appariement->transactionCompensee) {
                    throw new \Exception('Une transaction est déjà associée à cet appariement.');
                }

                // Montant que A doit payer (dans sa devise source)
                $montantA = $appariement->montant_compense;
                if ($montantA <= 0) {
                    throw new \Exception('Montant invalide pour cet appariement.');
                }

                // Montant que B doit payer (dans sa devise source) : conversion via taux de B
                $tauxB = $annonceB->taux_change;
                if ($tauxB <= 0) {
                    throw new \Exception('Taux de change invalide pour l\'annonce B.');
                }
                $montantB = $montantA / $tauxB;

                // Bénéficiaires (destinataires finaux)
                $beneficiaireA = $this->getBeneficiaire($annonceB->beneficiaire_email, $annonceB->beneficiaire_telephone);
                $beneficiaireB = $this->getBeneficiaire($annonceA->beneficiaire_email, $annonceA->beneficiaire_telephone);
                if (!$beneficiaireA) {
                    throw new \Exception("Bénéficiaire introuvable pour l'annonce B (email: {$annonceB->beneficiaire_email}, tel: {$annonceB->beneficiaire_telephone}).");
                }
                if (!$beneficiaireB) {
                    throw new \Exception("Bénéficiaire introuvable pour l'annonce A (email: {$annonceA->beneficiaire_email}, tel: {$annonceA->beneficiaire_telephone}).");
                }

                // Wallets des payeurs (émetteurs)
                $walletPayeurA = Wallet::where('users_id', $annonceA->users_id)
                    ->where('devise', $annonceA->devise_source)
                    ->lockForUpdate()
                    ->first();
                $walletPayeurB = Wallet::where('users_id', $annonceB->users_id)
                    ->where('devise', $annonceB->devise_source)
                    ->lockForUpdate()
                    ->first();

                if (!$walletPayeurA) {
                    throw new \Exception("Vous n'avez pas de wallet en {$annonceA->devise_source}.");
                }
                if ($walletPayeurA->solde < $montantA) {
                    throw new \Exception("Solde insuffisant : {$walletPayeurA->solde} {$annonceA->devise_source} requis {$montantA}");
                }
                if (!$walletPayeurB) {
                    throw new \Exception("L'autre utilisateur n'a pas de wallet en {$annonceB->devise_source}.");
                }
                if ($walletPayeurB->solde < $montantB) {
                    throw new \Exception("Solde insuffisant pour l'autre utilisateur : {$walletPayeurB->solde} {$annonceB->devise_source} requis {$montantB}");
                }

                // Wallets des bénéficiaires (dans leurs devises cibles)
                $walletBenefA = Wallet::where('users_id', $beneficiaireA->id)
                    ->where('devise', $annonceA->devise_cible)
                    ->lockForUpdate()
                    ->first();
                $walletBenefB = Wallet::where('users_id', $beneficiaireB->id)
                    ->where('devise', $annonceB->devise_cible)
                    ->lockForUpdate()
                    ->first();

                if (!$walletBenefA) {
                    throw new \Exception("Le bénéficiaire {$beneficiaireA->email} ne possède pas de wallet {$annonceA->devise_cible}.");
                }
                if (!$walletBenefB) {
                    throw new \Exception("Le bénéficiaire {$beneficiaireB->email} ne possède pas de wallet {$annonceB->devise_cible}.");
                }

                // Vérifications d'intégrité
                if ($beneficiaireA->id == $annonceA->users_id) {
                    throw new \Exception("Le bénéficiaire A ne peut pas être le même utilisateur que le payeur A.");
                }
                if ($beneficiaireB->id == $annonceB->users_id) {
                    throw new \Exception("Le bénéficiaire B ne peut pas être le même utilisateur que le payeur B.");
                }
                if ($beneficiaireA->id == $beneficiaireB->id) {
                    throw new \Exception("Les deux bénéficiaires ne peuvent pas être le même utilisateur.");
                }

                // Montants à créditer après conversion
                $montantBenefA = $montantA * $annonceA->taux_change;
                $montantBenefB = $montantB * $annonceB->taux_change;

                // Débiter les payeurs
                $walletPayeurA->solde -= $montantA;
                $walletPayeurB->solde -= $montantB;
                $walletPayeurA->save();
                $walletPayeurB->save();

                // Créditer les bénéficiaires
                $walletBenefA->solde += $montantBenefA;
                $walletBenefB->solde += $montantBenefB;
                $walletBenefA->save();
                $walletBenefB->save();

                // Créer la transaction compensée (historique)
                $transaction = TransactionCompensee::create([
                    'reference' => 'TXN-' . strtoupper(Str::random(10)),
                    'appariement_id' => $appariement->id,
                    'payeur_a_id' => $annonceA->users_id,
                    'payeur_b_id' => $annonceB->users_id,
                    'montant_a' => $montantA,
                    'montant_b' => $montantB,
                    'statut' => 'TERMINEE',
                    'date_debut' => now(),
                    'date_fin' => now(),
                ]);

                // Mettre à jour l'appariement
                $appariement->statut = 'TERMINE';
                $appariement->date_validation = now();
                $appariement->save();

                DB::commit();

                // === NOTIFICATIONS (uniquement en cas de succès) ===
                $this->sendSuccessNotifications($transaction, $annonceA, $annonceB, $beneficiaireA, $beneficiaireB, $montantA, $montantB, $montantBenefA, $montantBenefB);

                event(new TransactionCompenseeCreated($transaction));

                return redirect()->route('dashboard')->with('success', '✅ Transaction effectuée avec succès ! Les fonds ont été transférés.');
            } catch (\Exception $e) {
                DB::rollBack();
                // Notification d'erreur (uniquement in-app)
                $this->sendErrorNotification($user, $e->getMessage());
                Log::error('Erreur transaction interne: ' . $e->getMessage(), [
                    'appariement_id' => $appariement->id,
                    'user_id' => $user->id
                ]);
                return redirect()->route('dashboard')->with('error', 'Erreur : ' . $e->getMessage());
            }
        }

        // Première acceptation seulement
        return redirect()->route('dashboard')->with('success', '📌 Acceptation enregistrée. En attente de l\'autre partie.');
    }

    /**
     * Trouver un utilisateur par email ET téléphone (les deux doivent correspondre)
     */
    private function getBeneficiaire($email, $telephone)
    {
        if (empty($email) || empty($telephone)) {
            return null;
        }
        return User::where('email', $email)
            ->where('telephone', $telephone)
            ->first();
    }

    /**
     * Envoie les notifications (in-app + email) aux 4 intervenants en cas de succès
     */
    private function sendSuccessNotifications($transaction, $annonceA, $annonceB, $beneficiaireA, $beneficiaireB, $montantA, $montantB, $montantBenefA, $montantBenefB)
    {
        $payeurA = $annonceA->user;
        $payeurB = $annonceB->user;
        $ref = $transaction->reference;

        // 1. Payeur A a payé à bénéficiaire B
        $this->notifyUser($payeurA, "Paiement effectué", "Vous avez payé {$montantA} {$annonceA->devise_source} à {$beneficiaireB->prenom} {$beneficiaireB->nom}.", $montantA, $annonceA->devise_source, $beneficiaireB->prenom . ' ' . $beneficiaireB->nom, 'paye', $ref);
        // 2. Bénéficiaire B a reçu de payeur A
        $this->notifyUser($beneficiaireB, "Réception de fonds", "Vous avez reçu {$montantBenefB} {$annonceB->devise_cible} de {$payeurA->prenom} {$payeurA->nom}.", $montantBenefB, $annonceB->devise_cible, $payeurA->prenom . ' ' . $payeurA->nom, 'recu', $ref);
        // 3. Payeur B a payé à bénéficiaire A
        $this->notifyUser($payeurB, "Paiement effectué", "Vous avez payé {$montantB} {$annonceB->devise_source} à {$beneficiaireA->prenom} {$beneficiaireA->nom}.", $montantB, $annonceB->devise_source, $beneficiaireA->prenom . ' ' . $beneficiaireA->nom, 'paye', $ref);
        // 4. Bénéficiaire A a reçu de payeur B
        $this->notifyUser($beneficiaireA, "Réception de fonds", "Vous avez reçu {$montantBenefA} {$annonceA->devise_cible} de {$payeurB->prenom} {$payeurB->nom}.", $montantBenefA, $annonceA->devise_cible, $payeurB->prenom . ' ' . $payeurB->nom, 'recu', $ref);
    }

    /**
     * Envoie une notification in-app + email à un utilisateur
     */
    private function notifyUser($user, $titre, $message, $montant, $devise, $autrePartieNom, $type, $reference)
    {
        if (!$user) return;
        // Exclure les administrateurs (ils ne doivent pas recevoir ces notifications)
        if ($user->role === 'ADMIN') return;

        // Notification in-app
        Notification::create([
            'users_id' => $user->id,
            'type' => 'TRANSACTION',
            'titre' => $titre,
            'message' => $message,
            'canal' => 'APP',
            'priorite' => 'NORMALE',
            'est_lu' => false,
        ]);

        // Email
        try {
            Mail::to($user->email)->send(new TransactionNotificationMail($user, $montant, $devise, $autrePartieNom, $type, $reference));
        } catch (\Exception $e) {
            Log::error("Erreur envoi email à {$user->email} : " . $e->getMessage());
        }
    }

    /**
     * Envoie une notification d'erreur (uniquement in-app, pas d'email)
     */
    private function sendErrorNotification($user, $erreurMessage)
    {
        if (!$user) return;
        // Également, ne pas notifier les admins pour les erreurs de transaction
        if ($user->role === 'ADMIN') return;

        Notification::create([
            'users_id' => $user->id,
            'type' => 'TRANSACTION',
            'titre' => '❌ Échec de la transaction',
            'message' => 'Une erreur est survenue : ' . $erreurMessage,
            'canal' => 'APP',
            'priorite' => 'ELEVEE',
            'est_lu' => false,
        ]);
    }
}