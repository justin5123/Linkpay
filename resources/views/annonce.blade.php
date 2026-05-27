{{-- resources/views/annonce.blade.php --}}
@extends('layouts.user')

@section('title', 'Créer une annonce - LinPay')

@section('content')
<div class="p-6 md:p-8 max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <!-- En-tête avec dégradé et icône -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5 flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-full">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-1.417 1.71l-4.677.837A1.75 1.75 0 013 20.06V4.94c0-.968.79-1.759 1.76-1.76l4.678-.837A1.75 1.75 0 0111 5.882zM13 5.882V19.24a1.76 1.76 0 001.417 1.71l4.677.837A1.75 1.75 0 0021 20.06V4.94c0-.968-.79-1.759-1.76-1.76l-4.678-.837A1.75 1.75 0 0013 5.882z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Créer une annonce</h1>
                <p class="text-emerald-100 text-sm">Publiez votre besoin d'échange de devises</p>
            </div>
        </div>

        <form action="{{ route('annonce.store') }}" method="POST" class="p-6 md:p-8 space-y-8">
            @csrf

            <!-- Type d'annonce (boutons radio stylisés) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">📢 Type d'annonce *</label>
                <div class="flex flex-wrap gap-4">
                    <label class="relative flex items-center gap-3 px-5 py-2.5 rounded-xl border-2 cursor-pointer transition-all duration-200 
                        {{ old('type') == 'ENVOI' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-emerald-300' }}">
                        <input type="radio" name="type" value="ENVOI" class="sr-only" {{ old('type') == 'ENVOI' ? 'checked' : '' }} required>
                        <span class="text-xl">📤</span>
                        <span class="font-medium">Envoyer</span>
                    </label>
                    <label class="relative flex items-center gap-3 px-5 py-2.5 rounded-xl border-2 cursor-pointer transition-all duration-200 
                        {{ old('type') == 'RECEPTION' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-emerald-300' }}">
                        <input type="radio" name="type" value="RECEPTION" class="sr-only" {{ old('type') == 'RECEPTION' ? 'checked' : '' }} required>
                        <span class="text-xl">📥</span>
                        <span class="font-medium">Recevoir</span>
                    </label>
                </div>
                @error('type')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Montant source (avec icône) -->
            <div>
                <label for="montant_source" class="block text-sm font-semibold text-gray-700  mb-2">💰 Montant *</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 text-lg ">{{ $userDevise }}</span>
                    </div>
                    <input type="number" name="montant_source" id="montant_source" value="{{ old('montant_source') }}" step="0.01" 
                           class="w-full pl-10 pr-8 py-3  rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none" 
                           placeholder="0.00" required>
                </div>
                @error('montant_source')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grille 2 colonnes pour les paires de devises/pays -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Devise source -->
                <div>
                    <label for="devise_source" class="block text-sm font-semibold text-gray-700 mb-2">💱 Devise source *</label>
                    <input type="text" 
                        name="devise_source" 
                        id="devise_source" 
                        value="{{ $userDevise }}" 
                        readonly 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Votre devise locale (non modifiable)</p>
                </div>


                <!-- Pays source -->
                <div>
                    <label for="pays_source" class="block text-sm font-semibold text-gray-700 mb-2">🌍 Pays source *</label>
                    <input type="text" 
                        name="pays_source" 
                        id="pays_source" 
                        value="{{ $userPays }}" 
                        readonly 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Votre pays de résidence (non modifiable)</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Devise cible -->
                <div>
                    <label for="devise_cible" class="block text-sm font-semibold text-gray-700 mb-2">🎯 Devise cible *</label>
                    <select name="devise_cible" id="devise_cible" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none appearance-none bg-white">
                        <option value="">Sélectionner</option>
                        <option value="XAF" {{ old('devise_cible') == 'XAF' ? 'selected' : '' }}>XAF - Franc CFA</option>
                        <option value="EUR" {{ old('devise_cible') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="USD" {{ old('devise_cible') == 'USD' ? 'selected' : '' }}>USD - Dollar américain</option>
                        <option value="GBP" {{ old('devise_cible') == 'GBP' ? 'selected' : '' }}>GBP - Livre sterling</option>
                    </select>
                    @error('devise_cible')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pays destination -->
                <div>
                    <label for="pays_destination" class="block text-sm font-semibold text-gray-700 mb-2">📍 Pays de destination *</label>
                    <select name="pays_destination" id="pays_destination" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none appearance-none bg-white">
                        <option value="">Sélectionner</option>
                        @foreach(['Cameroun', 'France', 'USA', 'Canada'] as $pays)
                            <option value="{{ $pays }}" {{ old('pays_destination') == $pays ? 'selected' : '' }}>{{ $pays }}</option>
                        @endforeach
                    </select>
                    @error('pays_destination')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Taux de change optionnel (avec icône info) -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                <label for="taux_change" class="block text-sm font-semibold text-gray-700 mb-2">⚖️ Taux de change (optionnel)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400">1 :</span>
                    </div>
                    <input type="number" name="taux_change" id="taux_change" value="{{ old('taux_change') }}" step="0.000001" 
                           class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none" 
                           placeholder="Ex: 655.96">
                </div>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Laissez vide pour que le système calcule automatiquement selon le marché.
                </p>
                @error('taux_change')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Annuler
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl shadow-md hover:bg-emerald-700 hover:shadow-lg transform transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Publier l'annonce
                </button>
            </div>
        </form>
    </div>
</div>
@endsection