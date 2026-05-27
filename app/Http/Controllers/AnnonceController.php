<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\User;
use GuzzleHttp\Middleware;

class AnnonceController extends Controller
{
    

    public function create()
    {
        $user = auth()->user();
        $userPays = $user->pays; // ex: 'Cameroun'
        $userDevise = $this->getDeviseFromPays($userPays); // ex: 'XAF'
        
        return view('annonce', compact('userPays', 'userDevise'));
    }

    private function getDeviseFromPays($pays)
    {
        $map = [
            'Cameroun' => 'XAF',
            'France' => 'EUR',
            'USA' => 'USD',
            'Canada' => 'CAD',
            'Royaume-Uni' => 'GBP',
            // ... ajoutez d'autres pays
        ];
        return $map[$pays] ?? 'EUR'; // par défaut EUR
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ENVOI,RECEPTION',
            'montant_source' => 'required|numeric|min:0.01',
            'devise_source' => 'required|string|size:3',
            'pays_source' => 'required|string|max:255',
            'devise_cible' => 'required|string|size:3',
            'pays_destination' => 'required|string|max:255',
            'taux_change' => 'nullable|numeric|min:0',
        ]);

        // Calcul du montant cible
        $taux = $request->taux_change ?? $this->getTauxChange($request->devise_source, $request->devise_cible);
        $montant_cible = $request->montant_source * $taux;

        Annonce::create([
            'users_id' => auth()->id(),
            'type' => $request->type,
            'montant_source' => $request->montant_source,
            'montant_cible' => $montant_cible,
            'devise_source' => $request->devise_source,
            'devise_cible' => $request->devise_cible,
            'pays_source' => $request->pays_source,
            'pays_destination' => $request->pays_destination,
            'taux_change' => $taux,
            'statut' => 'EN_ATTENTE',
        ]);

        return redirect()->route('dashboard')->with('success', 'Annonce publiée avec succès !');
    }

    private function getTauxChange($from, $to)
    {
        // Ici vous pouvez appeler une API externe ou utiliser une table de taux fixes
        // Exemple simplifié :
        $taux = [
            'XAF_EUR' => 0.0015,
            'EUR_XAF' => 655.96,
            'USD_EUR' => 0.92,
            'EUR_USD' => 1.09,
            // ... ajoutez d'autres paires
        ];
        $key = $from . '_' . $to;
        return $taux[$key] ?? 1;
    }
}
