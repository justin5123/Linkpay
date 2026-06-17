<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Notification; // ← Import du modèle Notification
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 🔔 Créer une notification de bienvenue/connexion
        $user = Auth::user();
        Notification::create([
            'users_id' => $user->id,
            'type' => 'SECURITE',
            'titre' => 'Bienvenue sur LinPay 👋',
            'canal' => 'APP',
            'message' => 'Vous vous êtes connecté avec succès. Nous sommes heureux de vous revoir !',
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('dashboard'),
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}