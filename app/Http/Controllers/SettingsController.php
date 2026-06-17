<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    

    public function index()
    {
        return redirect()->route('settings.profile');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'pays' => 'required|string|max:255',
        ]);

        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'pays' => $request->pays,
        ]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function security()
    {
        $user = Auth::user();
        return view('settings.security', compact('user'));
    }

    public function updateSecurity(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    public function preferences()
    {
        $user = Auth::user();
        return view('settings.preferences', compact('user'));
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'devise' => 'nullable|string|size:3',
            'notifications_email' => 'boolean',
        ]);

        $user->update([
            'devise' => $request->devise ?? $user->devise,
        ]);

        // Vous pouvez ajouter une table user_preferences pour gérer les préférences
        // Exemple : UserPreference::updateOrCreate(['users_id' => $user->id], ['email' => $request->notifications_email]);

        return back()->with('success', 'Préférences mises à jour.');
    }
}