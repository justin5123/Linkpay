@extends('layouts.user')

@section('title', 'Nouveau ticket - Support LinPay')

@section('content')
<div class="p-6 md:p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Nouveau ticket</h1>
            <p class="text-emerald-100 text-sm">Décrivez votre problème, nous vous répondrons rapidement.</p>
        </div>

        <form action="{{ route('support.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="categorie" class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                <select name="categorie" id="categorie" required class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                    <option value="">Sélectionnez</option>
                    <option value="KYC">Vérification KYC</option>
                    <option value="TRANSACTION">Transaction</option>
                    <option value="WALLET">Portefeuille</option>
                    <option value="ANNONCE">Annonce</option>
                    <option value="REMBOURSEMENT">Remboursement</option>
                    <option value="SECURITE">Sécurité</option>
                    <option value="COMPTE">Compte</option>
                    <option value="AUTRE">Autre</option>
                </select>
                @error('categorie') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="sujet" class="block text-sm font-semibold text-gray-700 mb-2">Sujet *</label>
                <input type="text" name="sujet" id="sujet" value="{{ old('sujet') }}" required class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                @error('sujet') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description détaillée *</label>
                <textarea name="description" id="description" rows="5" required class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="priorite" class="block text-sm font-semibold text-gray-700 mb-2">Priorité *</label>
                <select name="priorite" id="priorite" required class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                    <option value="FAIBLE">Faible</option>
                    <option value="NORMALE">Normale</option>
                    <option value="ELEVEE">Élevée</option>
                    <option value="URGENTE">Urgente</option>
                </select>
                @error('priorite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('support.index') }}" class="px-6 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow">Envoyer</button>
            </div>
        </form>
    </div>
</div>
@endsection