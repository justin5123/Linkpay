<div class="space-y-6">
    <!-- Suggestions d'utilisateurs -->
    <div class="bg-white rounded-2xl shadow p-4">
        <h3 class="font-bold text-gray-800 mb-3">Suggestions pour vous</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                    <span class="text-sm font-medium">Paul K.</span>
                </div>
                <button class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full hover:bg-emerald-200">Suivre</button>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                    <span class="text-sm font-medium">Sophie L.</span>
                </div>
                <button class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Suivre</button>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                    <span class="text-sm font-medium">David M.</span>
                </div>
                <button class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full">Suivre</button>
            </div>
        </div>
        <a href="#" class="block text-center text-sm text-emerald-600 mt-3 hover:underline">Voir plus</a>
    </div>

    <!-- Statistiques utilisateur -->
    <div class="bg-white rounded-2xl shadow p-4">
        <h3 class="font-bold text-gray-800 mb-3">Votre activité</h3>
        <ul class="space-y-2 text-sm">
            <li class="flex justify-between"><span class="text-gray-600">Annonces actives</span><span class="font-semibold">0</span></li>
            <li class="flex justify-between"><span class="text-gray-600">Matchs en cours</span><span class="font-semibold">0</span></li>
            <li class="flex justify-between"><span class="text-gray-600">Transactions complétées</span><span class="font-semibold">{{ Auth::user()->total_transactions }}</span></li>
            <li class="flex justify-between"><span class="text-gray-600">Réputation</span><span class="font-semibold">{{ number_format(Auth::user()->reputation, 1) }}/5</span></li>
        </ul>
    </div>

    <!-- Rappel KYC -->
    @if(Auth::user()->statut_kyc != 'verifie')
    <div class="bg-amber-50 rounded-2xl shadow p-4 border-l-4 border-amber-400">
        <p class="text-amber-800 text-sm font-medium">KYC en attente</p>
        <p class="text-amber-600 text-xs mt-1">Complétez votre vérification pour augmenter vos limites.</p>
        <a href="#" class="inline-block mt-2 text-xs font-semibold text-amber-700 underline">Commencer</a>
    </div>
    @endif
</div>