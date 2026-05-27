<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupérer le wallet de l'utilisateur (premier wallet, car un utilisateur peut avoir plusieurs devises)
        $wallet = $user->wallets()->first();
        // $wallet = $user->wallets()->where("user_id", $user->id)->first();
        // Transactions récentes (5 dernières)
        $recentTransactions = $user->transactions()
            ->with('annonce') // si besoin
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistiques : nombre d'annonces, nombre de transactions réussies, montant total économisé (optionnel)
        $totalAnnonces = $user->annonces()->count();
        $totalTransactions = $user->transactions()->where('statut', 'REUSSIE')->count();

        // Niveau KYC (vous pouvez définir des étapes selon vos critères)
        $kycStatus = $user->statut_kyc; // 'EN_ATTENTE', 'VALIDE', 'REJETE'
        $kycProgress = 0;
        if ($kycStatus == 'EN_ATTENTE') $kycProgress = 25;
        if ($kycStatus == 'VALIDE') $kycProgress = 100;
        // Si vous avez plusieurs étapes (documents uploadés, etc.), calculez plus finement.

        return view('dashboard', compact('user', 'wallet', 'recentTransactions', 'totalAnnonces', 'totalTransactions', 'kycStatus', 'kycProgress'));
    }
}