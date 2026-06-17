<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    /**
     * Envoyer un code OTP par email.
     */
    public function send(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255',
            ]);

            // Supprimer les anciens codes pour cet email
            OtpCode::where('email', $request->email)->delete();

            // Générer un code à 6 chiffres
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Enregistrer en base
            OtpCode::create([
                'email' => $request->email,
                'code' => $code,
                'expires_at' => now()->addMinutes(2),
            ]);

            // Envoyer l'email avec la vue stylisée
            Mail::to($request->email)->send(new OtpMail($code));

            return response()->json([
                'success' => true,
                'message' => 'Code envoyé avec succès.',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi OTP : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi du code.',
            ], 500);
        }
    }

    /**
     * Vérifier un code OTP (optionnel - pour validation AJAX).
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $otp = OtpCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->first();

        if ($otp && $otp->isValid()) {
            return response()->json([
                'success' => true,
                'message' => 'Code valide.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Code invalide ou expiré.',
        ], 422);
    }
}