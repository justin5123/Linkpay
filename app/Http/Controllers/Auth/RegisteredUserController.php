<?php

namespace App\Http\Controllers\Auth;

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Notification;
use App\Models\ReferralTransaction;
use App\Models\OtpCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'pays' => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        // === VÉRIFICATION OTP ===
        $otpCode = OtpCode::where('email', $request->email)
            ->where('code', $request->otp)
            ->where('is_used', false)
            ->first();

        if (!$otpCode || !$otpCode->isValid()) {
            return back()->withErrors([
                'otp' => 'Le code de vérification est invalide ou a expiré.',
            ])->withInput();
        }

        // Marquer le code comme utilisé
        $otpCode->update(['is_used' => true]);

        // === PARRAINAGE ===
        $referralCode = $request->input('ref');

        $numeroCompte = 'LIN' . strtoupper(Str::random(10));
        $devise = $this->getDeviseFromPays($request->pays);

        // Création de l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'pays' => $request->pays,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role' => 'CLIENT',
            'statut_compte' => 'EN_ATTENTE',
            'statut_kyc' => 'EN_ATTENTE',
            'kyc_status' => 'NOT_STARTED',
            'is_suspected_fraud' => false,
        ]);

        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
            if ($referrer && $referrer->id !== $user->id) {
                $user->referred_by = $referrer->id;
                $user->save();
            }
        }

        // Email de bienvenue
        Mail::to($user->email)->send(new WelcomeMail($user));

        // Création du wallet
        Wallet::create([
            'users_id' => $user->id,
            'solde' => 0.00,
            'numero_compte' => $numeroCompte,
            'devise' => $devise,
            'pin_wallet' => bcrypt('0000'),
            'statut' => 'ACTIF',
            'est_actif' => true,
        ]);

        // Bonus de parrainage
        if ($user->referred_by) {
            $referrer = User::find($user->referred_by);
            if ($referrer) {
                $this->applyReferralBonus($user, $referrer);
            }
        }

        // Notification de bienvenue
        Notification::create([
            'users_id' => $user->id,
            'type' => 'INSCRIPTION',
            'titre' => 'Bienvenue sur LinPay 🎉',
            'canal' => 'APP',
            'message' => 'Votre compte a été créé avec succès. Nous sommes ravis de vous compter parmi nos utilisateurs !',
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('dashboard'),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    private function getDeviseFromPays($pays)
    {
        return match($pays) {
            'Cameroun' => 'XAF',
            'France' => 'EUR',
            'USA' => 'USD',
            'Canada' => 'CAD',
            default => 'EUR',
        };
    }

    private function applyReferralBonus(User $user, User $referrer)
    {
        $bonusAmount = 50;
        $devise = $referrer->wallets()->first()?->devise ?? 'XAF';

        $user->referral_bonus = $bonusAmount;
        $user->save();

        $wallet = $user->wallets()->first();
        if ($wallet) {
            $wallet->solde += $bonusAmount;
            $wallet->save();
        }

        $referrer->referral_bonus += $bonusAmount;
        $referrer->referral_count += 1;
        $referrer->save();

        $referrerWallet = $referrer->wallets()->first();
        if ($referrerWallet) {
            $referrerWallet->solde += $bonusAmount;
            $referrerWallet->save();
        }

        ReferralTransaction::create([
            'referrer_id' => $referrer->id,
            'referred_user_id' => $user->id,
            'amount' => $bonusAmount,
            'type' => 'SIGNUP_BONUS',
            'description' => "Bonus d'inscription pour avoir parrainé {$user->prenom} {$user->nom}",
        ]);

        Notification::create([
            'users_id' => $referrer->id,
            'type' => 'PARRAINAGE',
            'titre' => 'Nouveau parrainage !',
            'canal' => 'APP',
            'message' => "Vous avez parrainé {$user->prenom} {$user->nom} et gagné {$bonusAmount} {$devise} !",
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('referral.index'),
        ]);

        Notification::create([
            'users_id' => $user->id,
            'type' => 'PARRAINAGE',
            'titre' => 'Bienvenue avec bonus !',
            'canal' => 'APP',
            'message' => "Vous avez été parrainé par {$referrer->prenom} {$referrer->nom} et recevez {$bonusAmount} {$devise} de bienvenue !",
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('wallet.index'),
        ]);
    }
}