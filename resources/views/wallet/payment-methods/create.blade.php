@extends('layouts.user')

@section('title', 'Ajouter un moyen de paiement - LinPay')

@section('content')
<div class="p-6 md:p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Ajouter un moyen de paiement</h1>
            <p class="text-emerald-100 text-sm">Choisissez le type et renseignez vos coordonnées</p>
        </div>

        <form action="{{ route('wallet.payment-methods.store') }}" method="POST" class="p-6 space-y-6" id="paymentForm">
            @csrf

            <!-- Pays de l'utilisateur (non modifiable, lu depuis la base) -->
            <input type="hidden" name="pays" value="{{ Auth::user()->pays }}">

            <!-- Type de moyen de paiement -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Type de moyen de paiement *</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type_category" value="mobile" id="type_mobile" class="w-4 h-4 text-emerald-600" required>
                        <span class="text-gray-700">📱 Mobile Money</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type_category" value="bancaire" id="type_bancaire" class="w-4 h-4 text-emerald-600" required>
                        <span class="text-gray-700">🏦 Compte bancaire</span>
                    </label>
                </div>
                @error('type_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Champs dynamiques pour mobile money -->
            <div id="mobile_fields" class="space-y-4 hidden">
                <div>
                    <label for="fournisseur_mobile" class="block text-sm font-semibold text-gray-700 mb-2">Opérateur *</label>
                    <select name="fournisseur_mobile" id="fournisseur_mobile" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <option value="">Sélectionnez votre opérateur</option>
                    </select>
                </div>
                <div>
                    <label for="identifiant_mobile" class="block text-sm font-semibold text-gray-700 mb-2">Numéro de téléphone *</label>
                    <input type="tel" name="identifiant_mobile" id="identifiant_mobile" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="6XXXXXXXX">
                    <p class="text-xs text-gray-500 mt-1">Format : 9 chiffres (ex: 691234567)</p>
                </div>
            </div>

            <!-- Champs dynamiques pour compte bancaire -->
            <div id="bancaire_fields" class="space-y-4 hidden">
                <div>
                    <label for="fournisseur_bancaire" class="block text-sm font-semibold text-gray-700 mb-2">Banque *</label>
                    <select name="fournisseur_bancaire" id="fournisseur_bancaire" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <option value="">Sélectionnez votre banque</option>
                    </select>
                </div>
                <div>
                    <label for="identifiant_bancaire" class="block text-sm font-semibold text-gray-700 mb-2">Numéro de compte / IBAN *</label>
                    <input type="text" name="identifiant_bancaire" id="identifiant_bancaire" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="IBAN ou numéro de compte">
                </div>
            </div>

            <!-- Devise (cachée pour mobile money, à choisir pour bancaire ? On peut déduire de la devise du pays) -->
            <input type="hidden" name="devise" id="devise" value="">

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('wallet.index') }}" class="px-6 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 shadow">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Récupérer le pays de l'utilisateur depuis le serveur (affiché dans le champ hidden, ou directement via Blade)
    const userPays = '{{ Auth::user()->pays }}'; // ex: "Cameroun"

    // Définition des opérateurs mobiles par pays
    const mobileOperators = {
        'Cameroun': ['MTN-CAMEROUN', 'ORANGE-CAMEROUN'],
        'France': [], // pas de mobile money courant
        'USA': [],
        'Canada': []
    };

    // Définition des banques par pays
    const bankList = {
        'Cameroun': ['BICEC', 'SCB Cameroun', 'Afriland First Bank', 'Société Générale Cameroun'],
        'France': ['BNP Paribas', 'Société Générale', 'Crédit Agricole', 'LCL', 'CIC', 'Banque Populaire'],
        'USA': ['Chase', 'Bank of America', 'Wells Fargo', 'Citibank'],
        'Canada': ['RBC', 'TD Bank', 'Scotiabank', 'BMO']
    };

    const typeMobileRadio = document.getElementById('type_mobile');
    const typeBancaireRadio = document.getElementById('type_bancaire');
    const mobileFields = document.getElementById('mobile_fields');
    const bancaireFields = document.getElementById('bancaire_fields');
    const fournisseurMobileSelect = document.getElementById('fournisseur_mobile');
    const fournisseurBancaireSelect = document.getElementById('fournisseur_bancaire');
    const identifiantMobile = document.getElementById('identifiant_mobile');
    const identifiantBancaire = document.getElementById('identifiant_bancaire');
    const deviseInput = document.getElementById('devise');

    // Fonction pour remplir les listes déroulantes selon le pays
    function populateMobileOperators() {
        fournisseurMobileSelect.innerHTML = '<option value="">Sélectionnez votre opérateur</option>';
        const operators = mobileOperators[userPays] || [];
        operators.forEach(op => {
            const option = document.createElement('option');
            option.value = op;
            option.textContent = op;
            fournisseurMobileSelect.appendChild(option);
        });
        if (operators.length === 0) {
            fournisseurMobileSelect.disabled = true;
            fournisseurMobileSelect.innerHTML = '<option value="">Aucun opérateur mobile disponible dans votre pays</option>';
        } else {
            fournisseurMobileSelect.disabled = false;
        }
    }

    function populateBanks() {
        fournisseurBancaireSelect.innerHTML = '<option value="">Sélectionnez votre banque</option>';
        const banks = bankList[userPays] || [];
        banks.forEach(bank => {
            const option = document.createElement('option');
            option.value = bank;
            option.textContent = bank;
            fournisseurBancaireSelect.appendChild(option);
        });
        if (banks.length === 0) {
            fournisseurBancaireSelect.disabled = true;
            fournisseurBancaireSelect.innerHTML = '<option value="">Aucune banque répertoriée dans votre pays</option>';
        } else {
            fournisseurBancaireSelect.disabled = false;
        }
    }

    // Gérer le changement de type
    function toggleFields() {
        if (typeMobileRadio.checked) {
            mobileFields.classList.remove('hidden');
            bancaireFields.classList.add('hidden');
            // Rendre les champs mobile requis
            fournisseurMobileSelect.required = true;
            identifiantMobile.required = true;
            fournisseurBancaireSelect.required = false;
            identifiantBancaire.required = false;
            // Devise automatique (par défaut XAF pour Cameroun, sinon EUR, etc.)
            let devise = 'EUR';
            if (userPays === 'Cameroun') devise = 'XAF';
            else if (userPays === 'USA') devise = 'USD';
            else if (userPays === 'Canada') devise = 'CAD';
            else devise = 'EUR';
            deviseInput.value = devise;
        } else if (typeBancaireRadio.checked) {
            mobileFields.classList.add('hidden');
            bancaireFields.classList.remove('hidden');
            fournisseurMobileSelect.required = false;
            identifiantMobile.required = false;
            fournisseurBancaireSelect.required = true;
            identifiantBancaire.required = true;
            // Devise : selon le pays (laisser l'utilisateur choisir ou forcer ? Ici on force)
            let devise = 'EUR';
            if (userPays === 'Cameroun') devise = 'XAF';
            else if (userPays === 'USA') devise = 'USD';
            else if (userPays === 'Canada') devise = 'CAD';
            else devise = 'EUR';
            deviseInput.value = devise;
        }
    }

    typeMobileRadio.addEventListener('change', toggleFields);
    typeBancaireRadio.addEventListener('change', toggleFields);

    // Initialisation : remplir les listes
    populateMobileOperators();
    populateBanks();

    // Validation supplémentaire pour le numéro mobile (Cameroun)
    identifiantMobile.addEventListener('input', function() {
        const value = this.value.trim();
        const phoneRegex = /^[6-9][0-9]{8}$/; // 9 chiffres
        if (userPays === 'Cameroun' && typeMobileRadio.checked && value && !phoneRegex.test(value)) {
            this.setCustomValidity('Numéro invalide : 9 chiffres requis (ex: 691234567)');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endsection