<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnnonceController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $userPays = $user->pays;
        $userDevise = $this->getDeviseFromPays($userPays);
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
        ];
        return $map[$pays] ?? 'EUR';
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
            'beneficiaire_nom' => 'required_if:type,ENVOI|string|max:255',
            'beneficiaire_telephone' => 'required_if:type,ENVOI|string|max:255',
            'beneficiaire_email' => 'nullable|email|max:255',
        ]);

        // Taux de change
        $taux = $this->getLiveTaux($request->devise_source, $request->devise_cible);
        $montant_cible = $request->montant_source * $taux;

        // Création de l'annonce
        $annonce = Annonce::create([
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
            'beneficiaire_nom' => $request->beneficiaire_nom,
            'beneficiaire_telephone' => $request->beneficiaire_telephone,
            'beneficiaire_email' => $request->beneficiaire_email,
        ]);

        // Création de la publication dans le réseau social
        $post = Post::create([
            'users_id' => auth()->id(),
            'contenu' => 'Annonce : ' . $request->type . ' ' . $request->montant_source . ' ' . $request->devise_source . ' → ' . $request->devise_cible . ' (Montant cible : ' . $montant_cible . ' ' . $request->devise_cible . ')',
            'type' => 'ANNONCE',
            'annonce_id' => $annonce->id,
        ]);

        // 🔔 Notifier les amis
        $amis = auth()->user()->friends()->get();
        foreach ($amis as $ami) {
            Notification::create([
                'users_id' => $ami->id,
                'type' => 'SOCIAL',
                'titre' => 'Nouvelle annonce',
                'canal' => 'APP',
                'message' => auth()->user()->prenom . ' ' . auth()->user()->nom . ' a publié une annonce.',
                'est_lu' => false,
                'priorite' => 'NORMALE',
                'lien_action' => route('social.timeline'), // ou route('social.post.show', $post->id)
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Annonce publiée avec succès !');
    }

    private function getLiveTaux($from, $to)
    {
        $url = "https://api.exchangerate-api.com/v4/latest/{$from}";
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $data = $response->json();
                return $data['rates'][$to] ?? 1;
            }
        } catch (\Exception $e) {
            Log::error("Erreur API taux: " . $e->getMessage());
        }
        // Fallback
        return $this->getFallbackTaux($from, $to);
    }

    private function getFallbackTaux($from, $to)
    {
        $taux = [
            'XAF_EUR' => 0.0015,
            'EUR_XAF' => 655.96,
            'USD_EUR' => 0.92,
            'EUR_USD' => 1.09,
        ];
        $key = $from . '_' . $to;
        return $taux[$key] ?? 1;
    }
}