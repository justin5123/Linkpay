<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionCompensee;
use App\Models\Appariement;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Wallet
        $wallet = $user->wallets()->first();

        // Transactions classiques récentes
        $recentTransactions = $user->transactions()
            ->with('annonce')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistiques
        $totalAnnonces = $user->annonces()->count();
        $totalTransactions = $user->transactions()->where('statut', 'REUSSIE')->count();

        // KYC
        $kycStatus = $user->statut_kyc;
        $kycProgress = $kycStatus == 'VALIDE' ? 100 : ($kycStatus == 'EN_ATTENTE' ? 25 : 0);

        // Appariements en attente (sans transaction associée)
        $appariementsEnAttente = Appariement::where('statut', 'EN_ATTENTE_VALIDATION')
            ->whereDoesntHave('transactionCompensee')
            ->where(function($q) use ($user) {
                $q->whereHas('annonceEnvoi', fn($q2) => $q2->where('users_id', $user->id))
                  ->orWhereHas('annonceReception', fn($q2) => $q2->where('users_id', $user->id));
            })
            ->with(['annonceEnvoi.user', 'annonceReception.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Préparer les données d'affichage (calcul dynamique)
        foreach ($appariementsEnAttente as $app) {
            $annonceA = $app->annonceEnvoi;
            $annonceB = $app->annonceReception;

            if ($user->id == $annonceA->users_id) {
                $app->dejaAccepte = $app->accepte_par_emetteur;
                $app->estEmetteurA = true;
                $app->montant_a_payer = $app->montant_compense;
                $app->devise_a_payer = $annonceA->devise_source;
                $app->beneficiaire_nom = $annonceB->beneficiaire_nom ?? $annonceB->user->prenom;
                $app->beneficiaire_tel = $annonceB->beneficiaire_telephone ?? $annonceB->user->telephone;
            } else {
                $app->dejaAccepte = $app->accepte_par_recepteur;
                $app->estEmetteurA = false;
                $app->montant_a_payer = $app->montant_compense / $annonceB->taux_change;
                $app->devise_a_payer = $annonceB->devise_source;
                $app->beneficiaire_nom = $annonceA->beneficiaire_nom ?? $annonceA->user->prenom;
                $app->beneficiaire_tel = $annonceA->beneficiaire_telephone ?? $annonceA->user->telephone;
            }
        }

        // Filtres pour les transactions en cours
        $search = $request->input('search');
        $statutFilter = $request->input('statut');

        // Transactions compensées en cours (non terminées)
        $transactionsEnCours = TransactionCompensee::where(function($q) use ($user) {
                $q->where('payeur_a_id', $user->id)->orWhere('payeur_b_id', $user->id);
            })
            ->whereNotIn('statut', ['TERMINEE', 'ANNULEE'])
            ->when($search, function ($q, $search) {
                $q->where('reference', 'like', "%{$search}%");
            })
            ->when($statutFilter && in_array($statutFilter, ['EN_ATTENTE', 'PAYER_A', 'PAYER_B', 'LITIGE']), function ($q) use ($statutFilter) {
                $q->where('statut', $statutFilter);
            })
            ->with('appariement.annonceEnvoi', 'appariement.annonceReception')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Enrichir les transactions en cours
        foreach ($transactionsEnCours as $tx) {
            $app = $tx->appariement;
            $envoi = $app->annonceEnvoi;
            $reception = $app->annonceReception;

            if ($user->id == $tx->payeur_a_id) {
                $tx->montant_affiche = $tx->montant_a;
                $tx->devise_affiche = $envoi->devise_source;
                $tx->beneficiaire_nom = $reception->beneficiaire_nom ?? $reception->user->prenom;
                $tx->beneficiaire_tel = $reception->beneficiaire_telephone ?? $reception->user->telephone;
            } else {
                $tx->montant_affiche = $tx->montant_b;
                $tx->devise_affiche = $reception->devise_source;
                $tx->beneficiaire_nom = $envoi->beneficiaire_nom ?? $envoi->user->prenom;
                $tx->beneficiaire_tel = $envoi->beneficiaire_telephone ?? $envoi->user->telephone;
            }
        }

        // Transactions compensées terminées (pour l'historique et les litiges)
        $transactionsCompensees = TransactionCompensee::where('statut', 'TERMINEE')
            ->where(function($q) use ($user) {
                $q->where('payeur_a_id', $user->id)->orWhere('payeur_b_id', $user->id);
            })
            ->when($search, function ($q, $search) {
                $q->where('reference', 'like', "%{$search}%");
            })
            ->with('appariement.annonceEnvoi', 'appariement.annonceReception')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Enrichir les transactions terminées
        foreach ($transactionsCompensees as $tx) {
            $app = $tx->appariement;
            $envoi = $app->annonceEnvoi;
            $reception = $app->annonceReception;

            if ($user->id == $tx->payeur_a_id) {
                $tx->montant_affiche = $tx->montant_a;
                $tx->devise_affiche = $envoi->devise_source;
                $tx->beneficiaire_nom = $reception->beneficiaire_nom ?? $reception->user->prenom;
                $tx->beneficiaire_tel = $reception->beneficiaire_telephone ?? $reception->user->telephone;
            } else {
                $tx->montant_affiche = $tx->montant_b;
                $tx->devise_affiche = $reception->devise_source;
                $tx->beneficiaire_nom = $envoi->beneficiaire_nom ?? $envoi->user->prenom;
                $tx->beneficiaire_tel = $envoi->beneficiaire_telephone ?? $envoi->user->telephone;
            }
        }

        return view('dashboard', compact(
            'user', 'wallet', 'recentTransactions',
            'totalAnnonces', 'totalTransactions',
            'kycStatus', 'kycProgress',
            'appariementsEnAttente', 'transactionsEnCours', 'transactionsCompensees'
        ));
    }
}