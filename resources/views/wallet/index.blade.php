{{-- resources/views/wallet/index.blade.php --}}
@extends('layouts.user')

@section('title', 'Mon portefeuille - LinPay')

@section('content')
<div class="p-6 md:p-8 max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mon portefeuille</h1>
            <p class="text-gray-500">Gérez vos soldes et moyens de paiement</p>
        </div>
        <a href="{{ route('wallet.payment-methods.create') }}" class="bg-emerald-600 text-white px-5 py-2 rounded-xl shadow hover:bg-emerald-700 transition">
            + Ajouter un moyen de paiement
        </a>
    </div>

    <!-- Solde principal -->
    <div class="bg-gradient-to-br from-emerald-600 to-teal-600 text-white p-8 rounded-3xl shadow-xl mb-8">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-emerald-100 text-sm">Solde disponible</p>
                <h1 class="text-5xl font-bold mt-2 tracking-tight">
                    @if($wallet)
                        {{ number_format($wallet->solde, 2) }} {{ $wallet->devise }}
                    @else
                        0.00 XAF
                    @endif
                </h1>
                <p class="mt-2 text-emerald-100 text-sm">
                    Wallet sécurisé LinPay · N° {{ $wallet->numero_compte ?? '----' }}
                </p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs">
                @if($wallet && $wallet->est_actif) Actif @else En attente @endif
            </div>
        </div>

        <!-- Actions : liens -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('wallet.send') }}" class="bg-white text-emerald-700 px-5 py-2 rounded-full text-sm font-semibold shadow hover:bg-gray-100 transition inline-block text-center">
                💸 Envoyer
            </a>
            <a href="{{ route('wallet.deposit') }}" class="bg-white/20 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-white/30 transition inline-block text-center">
                ➕ Déposer
            </a>
            <a href="{{ route('wallet.withdraw') }}" class="bg-white/20 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-white/30 transition inline-block text-center">
                ⬇️ Retirer
            </a>
        </div>
    </div>

    <!-- Moyens de paiement -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">💳 Moyens de paiement</h2>
        @if($paymentMethods->count())
            <div class="space-y-3">
                @foreach($paymentMethods as $method)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <div>
                            <p class="font-medium">{{ $method->type }} - {{ $method->fournisseur }}</p>
                            <p class="text-sm text-gray-500">{{ $method->identifiant_compte }} · {{ $method->pays }} · {{ $method->devise }}</p>
                        </div>
                        <div class="flex gap-2 items-center">
                            @if($method->est_principal)
                                <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">Principal</span>
                            @endif
                            <a href="{{ route('wallet.payment-methods.edit', $method->id) }}" class="text-emerald-600 hover:underline text-sm">Modifier</a>
                            <form action="{{ route('wallet.payment-methods.destroy', $method->id) }}" method="POST" onsubmit="return confirm('Supprimer ce moyen de paiement ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-sm">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Aucun moyen de paiement enregistré.</p>
        @endif
    </div>

    <!-- Historique des transactions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">📜 Historique des transactions</h2>
            <a href="{{ route('wallet.transactions') }}" class="text-sm text-emerald-600 hover:underline">Voir tout</a>
        </div>
        @if($recentTransactions->count())
            <ul class="divide-y divide-gray-100">
                @foreach($recentTransactions as $tx)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-800">{{ ucfirst($tx->type) }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $tx->created_at ? $tx->created_at->format('d/m/Y H:i') : 'Date inconnue' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="font-semibold 
                                @if(in_array($tx->type, ['DEPOT','COMPENSATION'])) text-emerald-600
                                @elseif($tx->type == 'RETRAIT') text-red-500
                                @else text-gray-700 @endif">
                                @if(in_array($tx->type, ['DEPOT','COMPENSATION'])) +@endif
                                {{ number_format($tx->montant, 2) }} {{ $tx->devise }}
                            </span>
                            <p class="text-xs text-gray-400">{{ $tx->statut }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 py-4">Aucune transaction récente.</p>
        @endif
    </div>
</div>
@endsection

