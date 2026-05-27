<header class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex justify-between items-center">
        <!-- Logo -->
        <div class="text-2xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
            Linpay
        </div>

        <!-- Barre de recherche (optionnelle) -->
        <div class="hidden md:block flex-1 max-w-md mx-4">
            <div class="relative">
                <input type="text" placeholder="Rechercher..." class="w-full bg-gray-100 rounded-full py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <div class="absolute left-3 top-2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Icônes et menu utilisateur -->
        <div class="flex items-center space-x-4">
            <button class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>
            <div class="relative" id="user-menu-container">
                <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                    <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                    </div>
                    <span class="hidden md:inline text-gray-700">{{ Auth::user()->nom }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 hidden z-50">
                    <a href="{{ route('dashboard.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-t-xl">Mon profil</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">Paramètres</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-b-xl">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Dropdown utilisateur (clic)
    const userButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-menu-dropdown');
    if (userButton && userDropdown) {
        function closeDropdown() { userDropdown.classList.add('hidden'); }
        userButton.addEventListener('click', (e) => { e.stopPropagation(); userDropdown.classList.toggle('hidden'); });
        document.addEventListener('click', (e) => { if (!userButton.contains(e.target) && !userDropdown.contains(e.target)) closeDropdown(); });
    }
</script>