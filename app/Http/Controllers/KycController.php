<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DocumentKyc;

use App\Mail\KycStepSubmitted;
use Illuminate\Support\Facades\Mail;
// use App\Models\User;
class KycController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentStep = $user->current_kyc_step;
        return redirect()->route('kyc.step', ['step' => $currentStep]);
    }

    public function status()
    {
        $user = Auth::user();
        return view('kyc.status', compact('user'));
    }

    public function showStep($step)
    {

        
        $step = (int)$step;
        if ($step < 1 || $step > 4) {
            return redirect()->route('kyc.status');
        }

        $user = Auth::user();
        // Si kyc_status est NULL, on le traite comme NOT_STARTED
        $kycStatus = $user->kyc_status ?? 'NOT_STARTED';

        // Étape 1 : autoriser NOT_STARTED, STEP1_PENDING, STEP1_REJECTED (mais les deux derniers sont traités plus bas)
        if ($step === 1) {
            if ($kycStatus === 'STEP1_PENDING') {
                return redirect()->route('kyc.status')->with('info', 'Étape 1 en cours de vérification.');
            }
            if ($kycStatus === 'STEP1_VALIDATED') {
                return redirect()->route('kyc.status')->with('info', 'Étape 1 déjà validée.');
            }
            if ($kycStatus === 'STEP1_REJECTED') {
                session()->flash('rejection_reason', $user->kyc_rejection_reason);
                return view("kyc.step{$step}", compact('user', 'step'));
            }
            // NOT_STARTED ou autre → afficher le formulaire
            return view("kyc.step{$step}", compact('user', 'step'));
        }

        // Pour les étapes > 1, l'étape précédente doit être validée
        $prevValidated = ($kycStatus === ("STEP" . ($step - 1) . "_VALIDATED"));
        if (!$prevValidated) {
            return redirect()->route('kyc.status')->with('error', "Vous devez d'abord faire valider l'étape " . ($step - 1) . ".");
        }

        // Gestion des statuts de l'étape courante
        if ($kycStatus === "STEP{$step}_PENDING") {
            return redirect()->route('kyc.status')->with('info', "Étape $step en cours de vérification.");
        }
        if ($kycStatus === "STEP{$step}_VALIDATED") {
            return redirect()->route('kyc.status')->with('info', "Étape $step déjà validée.");
        }
        if ($kycStatus === "STEP{$step}_REJECTED") {
            session()->flash('rejection_reason', $user->kyc_rejection_reason);
            return view("kyc.step{$step}", compact('user', 'step'));
        }

        // Si aucun statut spécifique (ex: NOT_STARTED pour une étape >1), on affiche le formulaire (normalement ne devrait pas arriver)
        return view("kyc.step{$step}", compact('user', 'step'));
    }

    public function postStep1(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->kyc_status, ['NOT_STARTED','STEP1_REJECTED'])) {
            return redirect()->route('kyc.status')->with('error', 'Vous ne pouvez pas soumettre cette étape.');
        }

        $user->update([
            'identity_first_name' => $request->input('first_name'),
            'identity_last_name' => $request->input('last_name'),
            'identity_birth_date' => $request->input('birth_date'),
            'identity_birth_place' => $request->input('birth_place'),
            'identity_nationality' => $request->input('nationality'),
            'kyc_status' => 'STEP1_PENDING',
            'kyc_submitted_at' => now(),
            'kyc_rejection_reason' => null,
        ]);

        // 🔔 ENVOI D'EMAIL AUX ADMINISTRATEURS
        $admins = User::whereIn('role', ['ADMIN', 'SUPPORT'])->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new KycStepSubmitted($user, 1));
        }

        return redirect()->route('kyc.status')->with('success', 'Étape 1 soumise. En attente de validation.');
    }

    public function postStep2(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->kyc_status, ['STEP1_VALIDATED', 'STEP2_REJECTED'])) {
            return redirect()->route('kyc.status')->with('error', 'Vous ne pouvez pas soumettre cette étape.');
        }

        $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'proof_of_address' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('proof_of_address')->store('kyc/address', 'public');

        $user->update([
            'address_street' => $request->street,
            'address_city' => $request->city,
            'address_postal_code' => $request->postal_code,
            'address_country' => $request->country,
            'proof_of_address_path' => $path,
            'kyc_status' => 'STEP2_PENDING',
            'kyc_rejection_reason' => null,
        ]);

        // Envoi d'email aux administrateurs
        $admins = User::whereIn('role', ['ADMIN', 'SUPPORT'])->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new KycStepSubmitted($user, 2));
        }

        return redirect()->route('kyc.status')->with('success', 'Justificatif de domicile soumis.');
    }

    
    public function postStep3(Request $request)
    {
        $user = Auth::user();
        // Autoriser si l'étape 2 est validée (première soumission) ou si l'étape 3 est en attente/rejetée
        if (!in_array($user->kyc_status, ['STEP2_VALIDATED', 'STEP3_PENDING', 'STEP3_REJECTED'])) {
            return redirect()->route('kyc.status')->with('error', 'Vous ne pouvez pas soumettre cette étape.');
        }

        $request->validate([
            'document_type' => 'required|in:CNI,PASSEPORT,PERMIS_CONDUIRE',
            'document_number' => 'required|string|max:255',
            'front_image' => 'required|file|image|max:5120',
            'back_image' => 'nullable|file|image|max:5120',
        ]);

        $frontPath = $request->file('front_image')->store('kyc/id', 'public');
        $backPath = $request->file('back_image') ? $request->file('back_image')->store('kyc/id', 'public') : null;

        DocumentKyc::create([
            'users_id' => $user->id,
            'type_document' => $request->document_type,
            'numero_document' => $request->document_number,
            'image_recto' => $frontPath,
            'image_verso' => $backPath,
            'statut' => 'EN_ATTENTE',
            'date_soumission' => now(),
        ]);

        $user->update([
            'id_document_type' => $request->document_type,
            'id_document_number' => $request->document_number,
            'id_document_front_path' => $frontPath,
            'id_document_back_path' => $backPath,
            'kyc_status' => 'STEP3_PENDING',
            'kyc_rejection_reason' => null,
        ]);


        // Envoi d'email aux administrateurs
        $admins = User::whereIn('role', ['ADMIN', 'SUPPORT'])->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new KycStepSubmitted($user, 3));
        }
        return redirect()->route('kyc.status')->with('success', 'Pièce d’identité soumise.');
    }

    public function postStep4(Request $request)
    {
        $user = Auth::user();
        // Autoriser si l’étape 3 est validée, ou si l’étape 4 est en attente/rejetée
        if (!in_array($user->kyc_status, ['STEP3_VALIDATED', 'STEP4_PENDING', 'STEP4_REJECTED'])) {
            return redirect()->route('kyc.status')->with('error', 'Vous ne pouvez pas soumettre cette étape.');
        }

        $request->validate([
            'selfie' => 'required|file|image|max:5120',
            'additional_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $selfiePath = $request->file('selfie')->store('kyc/selfie', 'public');
        $additionalPath = $request->file('additional_document') ? $request->file('additional_document')->store('kyc/extra', 'public') : null;

        // Récupérer le dernier document en attente (créé à l’étape 3)
        $document = DocumentKyc::where('users_id', $user->id)
                    ->where('statut', 'EN_ATTENTE')
                    ->latest()
                    ->first();

        if (!$document) {
            return back()->with('error', 'Aucun document en attente. Veuillez recommencer l’étape 3.');
        }

        // Mettre à jour le document avec le selfie
        $document->update([
            'image_selfie' => $selfiePath,
        ]);

        // Mettre à jour l’utilisateur avec les chemins des fichiers
        $user->update([
            'selfie_with_id_path' => $selfiePath,
            'additional_document_path' => $additionalPath,
            'kyc_status' => 'STEP4_PENDING',
            'kyc_rejection_reason' => null,
        ]);
        
        // Envoi d'email aux administrateurs
        $admins = User::whereIn('role', ['ADMIN', 'SUPPORT'])->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new KycStepSubmitted($user, 4));
        }
        return redirect()->route('kyc.status')->with('success', 'Selfie soumis. Dernière étape.');
    }
}