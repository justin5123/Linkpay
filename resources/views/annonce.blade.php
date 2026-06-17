@extends('layouts.user')

@section('title', 'Créer une annonce - LinPay')

@section('content')
<div class="p-4 sm:p-6 md:p-8 max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-4 sm:px-6 py-4 sm:py-5 flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-xl shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-1.417 1.71l-4.677.837A1.75 1.75 0 013 20.06V4.94c0-.968.79-1.759 1.76-1.76l4.678-.837A1.75 1.75 0 0111 5.882zM13 5.882V19.24a1.76 1.76 0 001.417 1.71l4.677.837A1.75 1.75 0 0021 20.06V4.94c0-.968-.79-1.759-1.76-1.76l-4.678-.837A1.75 1.75 0 0013 5.882z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">Créer une annonce</h1>
                <p class="text-emerald-100 text-xs sm:text-sm">Publiez votre besoin d'échange de devises</p>
            </div>
        </div>

        <form action="{{ route('annonce.store') }}" method="POST" class="p-4 sm:p-6 md:p-8 space-y-6 md:space-y-8" id="annonceForm">
            @csrf

            <!-- Type d'annonce -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3">📢 Type d'annonce *</label>
                <div class="flex flex-wrap gap-3 sm:gap-4">
                    <label class="relative flex items-center gap-2 sm:gap-3 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl border-2 cursor-pointer transition-all duration-200 
                        {{ old('type') == 'ENVOI' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-emerald-300' }}">
                        <input type="radio" name="type" value="ENVOI" class="sr-only" {{ old('type') == 'ENVOI' ? 'checked' : '' }} required>
                        <span class="text-lg sm:text-xl">📤</span>
                        <span class="font-medium text-sm sm:text-base">Envoyer</span>
                    </label>
                    <label class="relative flex items-center gap-2 sm:gap-3 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl border-2 cursor-pointer transition-all duration-200 
                        {{ old('type') == 'RECEPTION' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-emerald-300' }}">
                        <input type="radio" name="type" value="RECEPTION" class="sr-only" {{ old('type') == 'RECEPTION' ? 'checked' : '' }} required>
                        <span class="text-lg sm:text-xl">📥</span>
                        <span class="font-medium text-sm sm:text-base">Recevoir</span>
                    </label>
                </div>
                @error('type') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Montant source -->
            <div>
                <label for="montant_source" class="block text-sm font-semibold text-gray-700 mb-2">💰 Montant *</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-emerald-700 text-lg sm:text-xl font-bold">{{ $userDevise }}</span>
                    </div>
                    <input type="number" name="montant_source" id="montant_source" value="{{ old('montant_source') }}" step="0.01" 
                           class="w-full pl-14 sm:pl-16 pr-4 py-3 sm:py-4 text-xl sm:text-2xl font-bold text-emerald-700 rounded-xl border-2 border-emerald-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none" 
                           placeholder="0.00" required>
                </div>
                @error('montant_source') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <!-- Devise source + Pays source (fixes) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="devise_source" class="block text-sm font-semibold text-gray-700 mb-2">💱 Devise source *</label>
                    <input type="text" name="devise_source" id="devise_source" value="{{ $userDevise }}" readonly 
                           class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed text-sm sm:text-base">
                    <p class="text-xs text-gray-400 mt-1">Votre devise locale (non modifiable)</p>
                </div>
                <div>
                    <label for="pays_source" class="block text-sm font-semibold text-gray-700 mb-2">🌍 Pays source *</label>
                    <input type="text" name="pays_source" id="pays_source" value="{{ $userPays }}" readonly 
                           class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed text-sm sm:text-base">
                    <p class="text-xs text-gray-400 mt-1">Votre pays de résidence (non modifiable)</p>
                </div>
            </div>

            <!-- Devise cible + Pays destination -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="devise_cible" class="block text-sm font-semibold text-gray-700 mb-2">🎯 Devise cible *</label>
                    <select name="devise_cible" id="devise_cible" required
                            class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm sm:text-base">
                        <option value="">Sélectionner</option>
                        <option value="XAF" {{ old('devise_cible') == 'XAF' ? 'selected' : '' }}>XAF - Franc CFA</option>
                        <option value="EUR" {{ old('devise_cible') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="USD" {{ old('devise_cible') == 'USD' ? 'selected' : '' }}>USD - Dollar américain</option>
                        <option value="GBP" {{ old('devise_cible') == 'GBP' ? 'selected' : '' }}>GBP - Livre sterling</option>
                    </select>
                    @error('devise_cible') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="pays_destination" class="block text-sm font-semibold text-gray-700 mb-2">📍 Pays de destination *</label>
                    <select name="pays_destination" id="pays_destination" required
                            class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm sm:text-base">
                        <option value="">Sélectionnez d'abord une devise cible</option>
                    </select>
                    @error('pays_destination') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Section Bénéficiaire (visible uniquement pour un ENVOI) -->
            <div id="beneficiaireSection" class="space-y-4 border-t border-gray-200 pt-6" style="display: none;">
                <h3 class="text-md font-semibold text-gray-800 flex items-center gap-2">
                    <span>👤</span> Informations du bénéficiaire
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="beneficiaire_nom" class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                        <input type="text" name="beneficiaire_nom" id="beneficiaire_nom" value="{{ old('beneficiaire_nom') }}"
                               class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                        @error('beneficiaire_nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="beneficiaire_telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                        <input type="tel" name="beneficiaire_telephone" id="beneficiaire_telephone" value="{{ old('beneficiaire_telephone') }}"
                               class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                        @error('beneficiaire_telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="beneficiaire_email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email (optionnel)</label>
                        <input type="email" name="beneficiaire_email" id="beneficiaire_email" value="{{ old('beneficiaire_email') }}"
                               class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                        @error('beneficiaire_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Taux de change automatique -->
            <div class="bg-emerald-50 rounded-xl p-4 sm:p-5 border border-emerald-200">
                <label for="taux_change" class="block text-sm font-semibold text-gray-700 mb-2">⚖️ Taux de change (automatique)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-emerald-800 font-bold text-sm sm:text-base">1 {{ $userDevise }} =</span>
                    </div>
                    <input type="number" name="taux_change" id="taux_change" step="0.000001" readonly
                           class="w-full pl-24 sm:pl-28 pr-4 py-2 sm:py-3 text-base sm:text-lg font-semibold text-emerald-700 rounded-xl border border-emerald-300 bg-white cursor-not-allowed"
                           placeholder="Chargement...">
                </div>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Taux actualisé en temps réel.
                </p>
                <input type="hidden" name="taux_reel" id="taux_reel">
            </div>

            <!-- Montant cible estimé -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">💰 Montant reçu (estimé)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-emerald-700 text-lg sm:text-xl font-bold" id="devise_cible_symbole">??</span>
                    </div>
                    <input type="text" id="montant_cible_estime" readonly
                           class="w-full pl-14 sm:pl-16 pr-4 py-3 sm:py-4 text-xl sm:text-2xl font-bold text-emerald-700 rounded-xl border-2 border-emerald-200 bg-gray-50 cursor-not-allowed"
                           placeholder="0.00">
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex justify-center items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all duration-200 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Annuler
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-emerald-600 text-white font-semibold rounded-xl shadow-md hover:bg-emerald-700 hover:shadow-lg transform transition-all duration-200 hover:-translate-y-0.5 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Publier l'annonce
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ========== Gestion des pays selon devise ==========
    const deviseToPays = {
        'XAF': ['Cameroun', 'Gabon', 'Congo', 'Sénégal', 'Côte d\'Ivoire', 'Mali', 'Burkina Faso', 'Bénin', 'Togo', 'Niger', 'Guinée-Bissau', 'Tchad', 'Centrafrique', 'Guinée équatoriale'],
        'EUR': ['France', 'Allemagne', 'Italie', 'Espagne', 'Pays-Bas', 'Belgique', 'Portugal', 'Irlande', 'Finlande', 'Autriche', 'Grèce', 'Slovaquie', 'Slovénie', 'Estonie', 'Lettonie', 'Lituanie', 'Luxembourg', 'Malte', 'Chypre'],
        'USD': ['États-Unis', 'Équateur', 'Salvador', 'Zimbabwe', 'Panama', 'Îles Marshall', 'Micronésie', 'Palaos', 'Timor oriental'],
        'GBP': ['Royaume-Uni', 'Îles Malouines', 'Gibraltar', 'Sainte-Hélène', 'Îles Vierges britanniques', 'Îles Caïmans']
    };
    const deviseCibleSelect = document.getElementById('devise_cible');
    const paysDestinationSelect = document.getElementById('pays_destination');
    const montantSource = document.getElementById('montant_source');
    const tauxChange = document.getElementById('taux_change');
    const tauxReel = document.getElementById('taux_reel');
    const montantCibleEstime = document.getElementById('montant_cible_estime');
    const deviseCibleSymbole = document.getElementById('devise_cible_symbole');
    const deviseSource = '{{ $userDevise }}';

    function updatePaysDestination() {
        const devise = deviseCibleSelect.value;
        paysDestinationSelect.innerHTML = '<option value="">Sélectionner un pays</option>';
        if (devise && deviseToPays[devise]) {
            deviseToPays[devise].forEach(pays => {
                const option = document.createElement('option');
                option.value = pays;
                option.textContent = pays;
                paysDestinationSelect.appendChild(option);
            });
        } else if (devise) {
            paysDestinationSelect.innerHTML = '<option value="">Aucun pays connu</option>';
        }
        fetchTaux();
    }

    async function fetchTaux() {
        const deviseCible = deviseCibleSelect.value;
        if (!deviseCible || deviseCible === deviseSource) {
            tauxChange.value = '';
            tauxReel.value = '';
            montantCibleEstime.value = '';
            deviseCibleSymbole.textContent = deviseCible || '??';
            return;
        }
        const url = `https://api.exchangerate-api.com/v4/latest/${deviseSource}`;
        try {
            const response = await fetch(url);
            const data = await response.json();
            const taux = data.rates[deviseCible];
            if (taux) {
                tauxChange.value = taux;
                tauxReel.value = taux;
                deviseCibleSymbole.textContent = deviseCible;
                updateMontantCible();
            } else {
                console.warn('Taux non trouvé');
            }
        } catch (error) {
            console.error('Erreur API taux:', error);
            tauxChange.value = 'Erreur';
        }
    }

    function updateMontantCible() {
        const montant = parseFloat(montantSource.value) || 0;
        const taux = parseFloat(tauxChange.value);
        if (!isNaN(taux) && taux > 0) {
            montantCibleEstime.value = (montant * taux).toFixed(2);
        } else {
            montantCibleEstime.value = '';
        }
    }

    // ========== Gestion de l'affichage de la section bénéficiaire ==========
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const beneficiaireSection = document.getElementById('beneficiaireSection');

    function toggleBeneficiaireSection() {
        const selectedType = document.querySelector('input[name="type"]:checked')?.value;
        if (selectedType === 'ENVOI') {
            beneficiaireSection.style.display = 'block';
            // Rendre les champs requis (optionnel, la validation côté serveur gère déjà)
            document.getElementById('beneficiaire_nom').required = true;
            document.getElementById('beneficiaire_telephone').required = true;
        } else {
            beneficiaireSection.style.display = 'none';
            // Supprimer l'obligation côté client pour éviter le blocage du formulaire en RECEPTION
            document.getElementById('beneficiaire_nom').required = false;
            document.getElementById('beneficiaire_telephone').required = false;
            // Vider les champs pour éviter d'envoyer des données inutiles
            document.getElementById('beneficiaire_nom').value = '';
            document.getElementById('beneficiaire_telephone').value = '';
            document.getElementById('beneficiaire_email').value = '';
        }
    }

    typeRadios.forEach(radio => radio.addEventListener('change', toggleBeneficiaireSection));
    // Appel initial pour la valeur par défaut (si déjà sélectionné ou chargement en erreur)
    toggleBeneficiaireSection();

    // ========== Événements pour le calcul du montant ==========
    deviseCibleSelect.addEventListener('change', updatePaysDestination);
    montantSource.addEventListener('input', updateMontantCible);
    if (deviseCibleSelect.value) {
        updatePaysDestination();
    } else {
        paysDestinationSelect.innerHTML = '<option value="">Sélectionnez d\'abord une devise cible</option>';
    }
</script>
@endsection