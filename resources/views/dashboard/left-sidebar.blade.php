<div class="bg-white rounded-2xl shadow p-4 mb-6">
    <!-- Profil utilisateur -->
    <div class="text-center">
        <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold shadow-md">
            {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
        </div>
        <h2 class="mt-3 text-xl font-bold text-gray-800">{{ Auth::user()->nom }}</h2>
        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
    </div>

    <div class="border-t my-4"></div>

    <!-- Navigation -->
    <nav class="space-y-2">
        <a href="{{ route('dashboard.feed') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-gray-700">Fil d'actualité</span>
        </a>

        <a href="{{ route('dashboard.create-ad') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="text-gray-700">Publier une annonce</span>
        </a>

        <a href="{{ route('dashboard.my-ads') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <span class="text-gray-700">Mes annonces</span>
        </a>

        <a href="{{ route('dashboard.history') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="text-gray-700">Historique</span>
        </a>

        <a href="{{ route('dashboard.notifications') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span class="text-gray-700">Notifications</span>
        </a>

        <a href="{{ route('dashboard.kyc') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span class="text-gray-700">Vérification KYC</span>
        </a>

        <a href="{{ route('dashboard.profile') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-gray-700">Mon profil</span>
        </a>

        <!-- NOUVEAU LIEN : Comptes bancaires -->
        <a href="{{ route('dashboard.bank-accounts') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-emerald-50 transition">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <span class="text-gray-700">Comptes bancaires</span>
        </a>
    </nav>
</div>