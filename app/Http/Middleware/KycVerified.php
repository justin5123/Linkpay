<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycVerified
{
    /**
     * Vérifier que l'utilisateur a un KYC validé.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si l'utilisateur n'est pas connecté, rediriger vers login
        if (!$user) {
            return redirect()->route('login');
        }

        // Si le KYC est complet, autoriser l'accès
        if ($user->kyc_status === 'COMPLETED') {
            return $next($request);
        }

        // Sinon, rediriger vers la page KYC avec un message
        return redirect()->route('kyc.status')
            ->with('error', 'Vous devez compléter votre vérification KYC pour accéder à cette fonctionnalité.');
    }
}