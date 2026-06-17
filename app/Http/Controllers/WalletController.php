<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Wallet;
use App\Models\User;
use App\Models\MoyenPaiement;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallets()->first();
        $paymentMethods = MoyenPaiement::where('users_id', $user->id)->orderBy('est_principal', 'desc')->get();
        $recentTransactions = Transaction::where('users_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
        
        return view('wallet.index', compact('user', 'wallet', 'paymentMethods', 'recentTransactions'));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = Transaction::where('users_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
        return view('wallet.transactions', compact('transactions'));
    }

    public function paymentMethodsCreate()
    {
        return view('wallet.payment-methods.create');
    }

    public function paymentMethodsStore(Request $request)
    {
        $request->validate([
            'type_category' => 'required|in:mobile,bancaire',
            'pays' => 'required|string',
        ]);

        $user = Auth::user();
        $pays = $request->pays;

        if ($request->type_category === 'mobile') {
            $validated = $request->validate([
                'fournisseur_mobile' => 'required|string',
                'identifiant_mobile' => 'required|string',
            ]);
            $type = 'MOBILE_MONEY';
            $fournisseur = $validated['fournisseur_mobile'];
            $identifiant = $validated['identifiant_mobile'];
            $devise = $this->getDeviseFromPays($pays);

            if ($pays === 'Cameroun') {
                if (!in_array($fournisseur, ['MTN-CAMEROUN', 'ORANGE-CAMEROUN'])) {
                    return back()->withErrors(['fournisseur_mobile' => 'Opérateur non reconnu au Cameroun'])->withInput();
                }
                if (!preg_match('/^[6-9][0-9]{8}$/', $identifiant)) {
                    return back()->withErrors(['identifiant_mobile' => 'Numéro de téléphone invalide (9 chiffres)'])->withInput();
                }
            }
        } else {
            $validated = $request->validate([
                'fournisseur_bancaire' => 'required|string',
                'identifiant_bancaire' => 'required|string',
            ]);
            $type = 'BANQUE';
            $fournisseur = $validated['fournisseur_bancaire'];
            $identifiant = $validated['identifiant_bancaire'];
            $devise = $request->devise ?? $this->getDeviseFromPays($pays);
        }

        $existingCount = MoyenPaiement::where('users_id', $user->id)->count();

        MoyenPaiement::create([
            'users_id' => $user->id,
            'type' => $type,
            'fournisseur' => $fournisseur,
            'identifiant_compte' => $identifiant,
            'pays' => $pays,
            'devise' => $devise,
            'est_verifie' => false,
            'est_principal' => $existingCount == 0,
            'est_actif' => true,
        ]);

        return redirect()->route('wallet.index')->with('success', 'Moyen de paiement ajouté.');
    }

    public function paymentMethodsEdit($id)
    {
        $paymentMethod = MoyenPaiement::where('users_id', Auth::id())->findOrFail($id);
        return view('wallet.payment-methods.edit', compact('paymentMethod'));
    }

    public function paymentMethodsUpdate(Request $request, $id)
    {
        $paymentMethod = MoyenPaiement::where('users_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'fournisseur' => 'required|string',
            'identifiant_compte' => 'required|string',
        ]);

        $paymentMethod->update([
            'fournisseur' => $request->fournisseur,
            'identifiant_compte' => $request->identifiant_compte,
        ]);

        return redirect()->route('wallet.index')->with('success', 'Moyen de paiement mis à jour.');
    }

    public function paymentMethodsDestroy($id)
    {
        $paymentMethod = MoyenPaiement::where('users_id', Auth::id())->findOrFail($id);
        $paymentMethod->delete();
        return redirect()->route('wallet.index')->with('success', 'Moyen de paiement supprimé.');
    }

    private function getDeviseFromPays($pays)
    {
        $map = [
            'Cameroun' => 'XAF',
            'France' => 'EUR',
            'USA' => 'USD',
            'Canada' => 'CAD',
        ];
        return $map[$pays] ?? 'EUR';
    }

    // Formulaire de dépôt
    public function depositForm()
    {
        return view('wallet.deposit');
    }

    // Traiter un dépôt
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method_id' => 'required|exists:moyens_paiements,id',
        ]);

        $user = Auth::user();
        $wallet = $user->wallets()->firstOrFail();
        $paymentMethod = MoyenPaiement::findOrFail($request->payment_method_id);

        do {
            $ref = 'DEP-' . Str::upper(Str::random(8));
        } while (Transaction::where('reference', $ref)->exists());

        $wallet->solde += $request->amount;
        $wallet->save();

        Transaction::create([
            'users_id' => $user->id,
            'type' => 'DEPOT',
            'montant' => $request->amount,
            'devise' => $wallet->devise,
            'reference' => $ref,
            'statut' => 'REUSSIE',
            'methode_paiement' => $paymentMethod->type,
            'description' => 'Dépôt via ' . $paymentMethod->fournisseur,
        ]);

        return redirect()->route('wallet.index')->with('success', 'Dépôt effectué avec succès.');
    }

    // Formulaire d'envoi
    public function sendForm()
    {
        return view('wallet.send');
    }

    // Traiter un envoi (transfert)
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:100',
        ]);

        $sender = Auth::user();
        $receiver = User::where('email', $request->email)->first();

        // ❌ auto‑transfert interdit
        if ($sender->id === $receiver->id) {
            return back()->withErrors(['email' => 'Vous ne pouvez pas vous envoyer d\'argent à vous‑même.'])->withInput();
        }

        $senderWallet = $sender->wallets()->firstOrFail();
        $receiverWallet = $receiver->wallets()->firstOrFail();

        // 💰 vérifier le solde
        if ($senderWallet->solde < $request->amount) {
            return back()->withErrors(['amount' => 'Solde insuffisant.'])->withInput();
        }

        // 🔑 générer deux références uniques (une pour chaque transaction)
        do {
            $refSender = 'TRF-' . Str::upper(Str::random(8));
        } while (Transaction::where('reference', $refSender)->exists());

        do {
            $refReceiver = 'TRF-' . Str::upper(Str::random(8));
        } while (Transaction::where('reference', $refReceiver)->exists());

        // ⚛️ transaction atomique
        DB::transaction(function () use ($sender, $receiver, $request, $senderWallet, $receiverWallet, $refSender, $refReceiver) {
            // Débiter l'expéditeur
            $senderWallet->solde -= $request->amount;
            $senderWallet->save();

            // Créditer le destinataire
            $receiverWallet->solde += $request->amount;
            $receiverWallet->save();

            // Transaction de l'expéditeur
            Transaction::create([
                'users_id' => $sender->id,
                'type' => 'TRANSFERT',
                'montant' => $request->amount,
                'devise' => $senderWallet->devise,
                'reference' => $refSender,
                'statut' => 'REUSSIE',
                'description' => 'Envoi à ' . $receiver->email,
            ]);

            // Transaction du destinataire
            Transaction::create([
                'users_id' => $receiver->id,
                'type' => 'TRANSFERT',
                'montant' => $request->amount,
                'devise' => $receiverWallet->devise,
                'reference' => $refReceiver,
                'statut' => 'REUSSIE',
                'description' => 'Réception de ' . $sender->email,
            ]);
        });

        return redirect()->route('wallet.index')->with('success', 'Transfert effectué avec succès.');
    }

    // Formulaire de retrait
    public function withdrawForm()
    {
        return view('wallet.withdraw');
    }

    // Traiter un retrait
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method_id' => 'required|exists:moyens_paiements,id',
        ]);

        $user = Auth::user();
        $wallet = $user->wallets()->firstOrFail();

        if ($wallet->solde < $request->amount) {
            return back()->withErrors(['amount' => 'Solde insuffisant.'])->withInput();
        }

        do {
            $ref = 'WDR-' . Str::upper(Str::random(8));
        } while (Transaction::where('reference', $ref)->exists());

        $wallet->solde -= $request->amount;
        $wallet->save();

        Transaction::create([
            'users_id' => $user->id,
            'type' => 'RETRAIT',
            'montant' => $request->amount,
            'devise' => $wallet->devise,
            'reference' => $ref,
            'statut' => 'REUSSIE',
            'description' => 'Retrait vers moyen de paiement #' . $request->payment_method_id,
        ]);

        return redirect()->route('wallet.index')->with('success', 'Retrait effectué.');
    }
}