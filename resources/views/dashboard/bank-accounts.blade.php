@extends('dashboard-layout')

@section('title', 'Mes comptes bancaires')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mes comptes bancaires</h1>
        <button type="button" onclick="toggleForm()" class="bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Ajouter un compte</span>
        </button>
    </div>

    <!-- Formulaire d'ajout dynamique -->
    <div id="addAccountForm" class="hidden bg-gray-50 p-5 rounded-2xl mb-8 border border-gray-200">
        <h2 class="text-lg font-semibold mb-4">Nouveau compte bancaire</h2>
        <form id="bankAccountForm" action="{{ route('dashboard.bank-accounts.store') }}" method="POST">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type de compte *</label>
                    <select name="type" id="accountType" class="w-full border rounded-xl p-2" required>
                        <option value="national">🏦 Compte national (paiements locaux)</option>
                        <option value="international">🌍 Compte international (virements SWIFT/IBAN)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pays (code ISO 2 lettres) *</label>
                    <select name="pays" class="w-full border rounded-xl p-2" required>
                        <option value="CM">Cameroun (CM)</option>
                        <option value="FR">France (FR)</option>
                        <option value="SN">Sénégal (SN)</option>
                        <option value="CI">Côte d'Ivoire (CI)</option>
                        <option value="US">États-Unis (US)</option>
                        <option value="GB">Royaume-Uni (GB)</option>
                        <option value="DE">Allemagne (DE)</option>
                        <option value="CA">Canada (CA)</option>
                        <option value="BE">Belgique (BE)</option>
                        <option value="CH">Suisse (CH)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom de la banque *</label>
                    <input type="text" name="nom_banque" class="w-full border rounded-xl p-2" placeholder="ex: Société Générale" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Titulaire du compte *</label>
                    <input type="text" name="titulaire" class="w-full border rounded-xl p-2" placeholder="ex: Jean DUPONT" required>
                </div>

                <!-- Champs conditionnels pour compte national -->
                <div id="nationalFields" class="hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Numéro de compte *</label>
                        <input type="text" name="numero_compte_national" class="w-full border rounded-xl p-2" placeholder="ex: 12345678901">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">RIB (optionnel)</label>
                        <input type="text" name="rib" class="w-full border rounded-xl p-2" placeholder="ex: 12345 67890 12345678901 12">
                    </div>
                </div>

                <!-- Champs conditionnels pour compte international -->
                <div id="internationalFields" class="hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">IBAN *</label>
                        <input type="text" name="iban" class="w-full border rounded-xl p-2" placeholder="ex: FR76 3000 1007 0000 1234 5678 901">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code SWIFT/BIC *</label>
                        <input type="text" name="code_swift" class="w-full border rounded-xl p-2" placeholder="ex: SOGEFRPP">
                    </div>
                </div>

                <div class="flex items-center col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="est_par_defaut" value="1" class="rounded border-gray-300 text-emerald-600">
                        <span class="ml-2 text-sm text-gray-700">Définir comme compte par défaut</span>
                    </label>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700 transition">Enregistrer</button>
            </div>
        </form>
    </div>

    <!-- Liste des comptes -->
    <h2 class="text-xl font-semibold mb-4">Vos comptes enregistrés</h2>
    @if($comptes->count())
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($comptes as $compte)
            <div class="border rounded-xl p-4 {{ $compte->est_par_defaut ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-lg font-bold text-gray-800">{{ $compte->nom_banque }}</span>
                            @if($compte->type == 'national')
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">National</span>
                            @else
                                <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">International</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1"><strong>Pays :</strong> {{ $compte->pays }}</p>
                        <p class="text-sm text-gray-600"><strong>Titulaire :</strong> {{ $compte->titulaire }}</p>
                        @if($compte->type == 'national')
                            <p class="text-sm text-gray-600"><strong>N° compte :</strong> <span class="font-mono">{{ $compte->numero_compte }}</span></p>
                            @if($compte->rib)
                                <p class="text-sm text-gray-600"><strong>RIB :</strong> <span class="font-mono">{{ $compte->rib }}</span></p>
                            @endif
                        @else
                            <p class="text-sm text-gray-600"><strong>IBAN :</strong> <span class="font-mono">{{ chunk_split($compte->numero_compte, 4, ' ') }}</span></p>
                            <p class="text-sm text-gray-600"><strong>SWIFT :</strong> <span class="font-mono uppercase">{{ $compte->code_swift }}</span></p>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($compte->est_par_defaut)
                            <span class="bg-emerald-200 text-emerald-800 text-xs px-2 py-1 rounded-full">Défaut</span>
                        @endif
                        <form action="{{ route('dashboard.bank-accounts.destroy', $compte->id) }}" method="POST" class="inline-block ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-xl p-8 text-center text-gray-400">
            <p>Vous n'avez ajouté aucun compte bancaire.</p>
        </div>
    @endif
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('addAccountForm');
        form.classList.toggle('hidden');
    }

    // Gestion de l'affichage dynamique des champs selon le type
    const typeSelect = document.getElementById('accountType');
    const nationalFields = document.getElementById('nationalFields');
    const internationalFields = document.getElementById('internationalFields');

    function updateFields() {
        const type = typeSelect.value;
        if (type === 'national') {
            nationalFields.classList.remove('hidden');
            internationalFields.classList.add('hidden');
            // Rendre requis les champs nationaux
            document.querySelector('input[name="numero_compte_national"]').required = true;
            document.querySelector('input[name="iban"]').required = false;
            document.querySelector('input[name="code_swift"]').required = false;
        } else {
            nationalFields.classList.add('hidden');
            internationalFields.classList.remove('hidden');
            document.querySelector('input[name="numero_compte_national"]').required = false;
            document.querySelector('input[name="iban"]').required = true;
            document.querySelector('input[name="code_swift"]').required = true;
        }
    }

    // Initialisation et écouteur
    if (typeSelect) {
        typeSelect.addEventListener('change', updateFields);
        updateFields();
    }

    // Avant soumission, mapper les champs selon le type pour le bon champ 'numero_compte'
    const form = document.getElementById('bankAccountForm');
    form.addEventListener('submit', function(e) {
        const type = typeSelect.value;
        if (type === 'national') {
            const nationalNum = document.querySelector('input[name="numero_compte_national"]').value;
            const ibanField = document.querySelector('input[name="iban"]');
            ibanField.disabled = true; // désactiver pour éviter l'envoi
            // Créer un champ caché 'numero_compte' avec la valeur du national
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'numero_compte';
            hiddenInput.value = nationalNum;
            form.appendChild(hiddenInput);
        } else {
            const iban = document.querySelector('input[name="iban"]').value;
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'numero_compte';
            hiddenInput.value = iban;
            form.appendChild(hiddenInput);
        }
    });
</script>
@endsection