@extends('dashboard-layout')

@section('title', 'Publier une annonce')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Nouvelle annonce</h1>

    <form method="POST" action="{{ route('dashboard.store-ad') }}" id="annonceForm">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Type d'annonce</label>
            <select name="type" id="type" class="w-full border rounded-xl p-2" required>
                <option value="envoi">📤 Envoyer de l'argent</option>
                <option value="reception">📥 Recevoir de l'argent</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Montant</label>
            <div class="flex gap-2">
                <input type="number" step="0.01" name="montant" id="montant" class="flex-1 border rounded-xl p-2" placeholder="Ex: 500" required>
                <div class="w-24 bg-gray-100 border rounded-xl p-2 text-center">{{ $deviseSource }}</div>
                <input type="hidden" name="devise_source" id="devise_source_hidden" value="{{ $deviseSource }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pays de destination</label>
            <select name="pays_destination" id="pays_destination" class="w-full border rounded-xl p-2" required>
                <option value="">-- Sélectionnez --</option>
                <option value="CM">Cameroun (XAF)</option>
                <option value="FR">France (EUR)</option>
                <option value="US">États-Unis (USD)</option>
                <option value="GB">Royaume-Uni (GBP)</option>
                <option value="CA">Canada (CAD)</option>
                <option value="DE">Allemagne (EUR)</option>
                <option value="SN">Sénégal (XOF)</option>
                <option value="CI">Côte d'Ivoire (XOF)</option>
            </select>
        </div>

        <!-- Cadre de conversion avec spinner -->
        <div id="conversionCard" class="mb-6 p-4 bg-emerald-50 rounded-xl border border-emerald-200" style="display: none;">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1">
                    <h4 class="font-semibold text-emerald-800">Conversion en temps réel</h4>
                    <div id="conversionContent">
                        <p class="text-sm text-emerald-700 mt-1" id="conversionText">--</p>
                    </div>
                    <div id="spinner" class="hidden mt-2">
                        <div class="inline-block w-4 h-4 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-xs text-emerald-600 ml-2">Récupération du taux...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description (optionnelle)</label>
            <textarea name="description" rows="3" class="w-full border rounded-xl p-2" placeholder="Détails supplémentaires..."></textarea>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700">Publier l'annonce</button>
        </div>
    </form>
</div>

<script>
    const montantInput = document.getElementById('montant');
    const paysSelect = document.getElementById('pays_destination');
    const conversionCard = document.getElementById('conversionCard');
    const conversionText = document.getElementById('conversionText');
    const spinner = document.getElementById('spinner');
    const deviseSource = document.getElementById('devise_source_hidden').value;

    const countryCurrency = {
        'CM': 'XAF', 'FR': 'EUR', 'US': 'USD', 'GB': 'GBP',
        'CA': 'CAD', 'DE': 'EUR', 'SN': 'XOF', 'CI': 'XOF'
    };

    async function updateConversion() {
        const amount = parseFloat(montantInput.value);
        const pays = paysSelect.value;

        if (!amount || isNaN(amount) || amount <= 0 || !pays) {
            conversionCard.style.display = 'none';
            return;
        }

        const toCurrency = countryCurrency[pays];
        if (!toCurrency) {
            conversionCard.style.display = 'none';
            return;
        }

        // Afficher le spinner et masquer le texte
        conversionCard.style.display = 'block';
        conversionText.textContent = '--';
        spinner.classList.remove('hidden');

        try {
            const response = await fetch(`/api/convert?amount=${amount}&from=${deviseSource}&to=${toCurrency}`);
            const data = await response.json();

            spinner.classList.add('hidden');
            if (data.converted !== null && data.converted !== undefined) {
                conversionText.innerHTML = `${amount} ${deviseSource} = <strong>${data.converted} ${toCurrency}</strong> (1 ${deviseSource} = ${data.rate} ${toCurrency})`;
            } else {
                conversionText.textContent = 'Taux indisponible pour le moment.';
            }
        } catch (error) {
            console.error(error);
            spinner.classList.add('hidden');
            conversionText.textContent = 'Erreur de connexion au service de change.';
        }
    }

    montantInput.addEventListener('input', updateConversion);
    paysSelect.addEventListener('change', updateConversion);
</script>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 0.8s linear infinite;
    }
</style>
@endsection