@extends('layouts.user')
@section('title', 'Modifier un moyen de paiement')
@section('content')
<div class="p-6 md:p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Modifier le moyen de paiement</h1>
        </div>
        <form action="{{ route('wallet.payment-methods.update', $paymentMethod->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <div><label class="block text-sm font-semibold">Fournisseur</label><input type="text" name="fournisseur" value="{{ $paymentMethod->fournisseur }}" class="w-full px-4 py-2 rounded-xl border" required></div>
            <div><label class="block text-sm font-semibold">Identifiant du compte</label><input type="text" name="identifiant_compte" value="{{ $paymentMethod->identifiant_compte }}" class="w-full px-4 py-2 rounded-xl border" required></div>
            <div class="flex justify-end"><a href="{{ route('wallet.index') }}" class="px-6 py-2 border rounded-xl mr-2">Annuler</a><button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl">Mettre à jour</button></div>
        </form>
    </div>
</div>
@endsection