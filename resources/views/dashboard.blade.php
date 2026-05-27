<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinPay - Tableau de bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active {
            background-color: #ecfdf5;
            color: #047857;
        }
        .transition-smooth { transition: all 0.2s ease; }
        /* Masquer le modal par défaut */
        .modal-hidden { display: none; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR GAUCHE FIXE -->
    <aside class="w-64 bg-white shadow-xl flex flex-col h-full shrink-0 z-20">
        <div class="p-6 border-b border-gray-100">
            <h1 class="text-2xl font-bold text-emerald-600">LinPay</h1>
            <p class="text-xs text-gray-400 mt-1">Finance sans frontières</p>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth active">
                <span class="text-xl">📊</span> Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span class="text-xl">💼</span> Wallet
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span class="text-xl">💸</span> Transactions
            </a>
            <a href="{{ route('annonce.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span>📢</span> Nouvelle annonce
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span class="text-xl">👥</span> Réseau social
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span class="text-xl">🔔</span> Notifications
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span class="text-xl">🎫</span> Support
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span class="text-xl">⚙️</span> Paramètres
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <!-- Pas de bouton de déconnexion ici car il est dans le modal -->
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- HEADER STATIC + TRANSPARENT avec ordre : salutation, recherche, notif, profil -->
        <header class="bg-white/70 backdrop-blur-md shadow-sm sticky top-0 z-10">
            <div class="flex flex-wrap items-center justify-between gap-4 px-8 py-4">
                <!-- 1. Salutation -->
                <div class="">
                    <h2 class="text-3xl font-bold text-gray-800">
                        Bonjour {{ $user->prenom ?? $user->nom }} 👋
                    </h2>
                    <p class="text-gray-500 mt-1">
                        Bienvenue sur votre espace LinPay · {{ now()->translatedFormat('l j F Y') }}
                    </p>
                </div>

                <!-- 2. Barre de recherche -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher une transaction, un utilisateur..." 
                               class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 bg-white/90 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm shadow-sm">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-lg">🔍</span>
                    </div>
                </div>

                <!-- 3. Notifications + 4. Profil utilisateur (avec modal) -->
                <div class="flex items-center gap-6">
                    <!-- Notifications -->
                    <button class="relative text-gray-600 hover:text-emerald-600 transition">
                        <span class="text-2xl">🔔</span>
                        <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">3</span>
                    </button>

                    <!-- Profil (ouvre le modal) -->
                    <div class="relative">
                        <button id="profileButton" class="flex items-center gap-3 focus:outline-none group">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold shadow-md group-hover:shadow-lg transition">
                                {{ strtoupper(substr($user->prenom ?? $user->nom, 0, 1)) }}
                            </div>
                            <div class="text-sm hidden sm:block text-left">
                                <p class="font-semibold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- MODAL (dropdown) -->
                        <div id="profileModal" class="modal-hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 z-30 overflow-hidden">
                            <div class="p-3 border-b border-gray-100 bg-gray-50">
                                <p class="text-sm font-medium text-gray-700">{{ $user->email }}</p>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="text-lg">⚙️</span> Paramètres
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-left text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                        <span class="text-lg">🚪</span> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENU PRINCIPAL avec animations -->
        <div class="p-8 max-w-7xl mx-auto space-y-8">
            
            <!-- ================================================== -->
            <!-- CONTENU PRINCIPAL DU DASHBOARD (extrait)            -->
            <!-- ================================================== -->

            <!-- Wallet Card -->
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white p-8 rounded-3xl shadow-xl mb-8">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-emerald-100 text-sm">Solde disponible</p>
                        <h1 class="text-4xl font-bold mt-2 tracking-tight">
                            @if($wallet)
                                {{ number_format($wallet->solde, 2) }} {{ $wallet->devise }}
                            @else
                                0.00 {{ $user->devise ?? 'XAF' }}
                            @endif
                        </h1>
                        <p class="mt-2 text-emerald-100 text-sm">
                            Wallet sécurisé LinPay · N° {{ $wallet->numero_compte ?? '----' }}
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs">
                        @if($wallet && $wallet->est_actif) Actif @else En attente @endif
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button class="bg-white text-emerald-700 px-5 py-2 rounded-full text-sm font-semibold shadow hover:bg-gray-100 transition">💸 Envoyer</button>
                    <button class="bg-white/20 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-white/30 transition">➕ Déposer</button>
                    <button class="bg-white/20 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-white/30 transition">⬇️ Retirer</button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Annonces publiées</span>
                        <span class="text-2xl">📢</span>
                    </div>
                    <p class="text-2xl font-bold mt-2">{{ $totalAnnonces }}</p>
                    <p class="text-xs text-gray-400 mt-1">En attente / actives</p>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Transactions réussies</span>
                        <span class="text-2xl">✅</span>
                    </div>
                    <p class="text-2xl font-bold mt-2">{{ $totalTransactions }}</p>
                    <p class="text-xs text-gray-400 mt-1">Depuis votre inscription</p>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Niveau KYC</span>
                        <span class="text-2xl">🪪</span>
                    </div>
                    <p class="text-2xl font-bold mt-2">
                        @if($kycStatus == 'VALIDE') Validé
                        @elseif($kycStatus == 'REJETE') Rejeté
                        @else En attente
                        @endif
                    </p>
                    <div class="w-full bg-gray-200 h-1.5 rounded-full mt-2">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $kycProgress }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Transactions & KYC Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Transactions -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-gray-800">Transactions récentes</h3>
                        <a href="#" class="text-sm text-emerald-600 hover:underline">Voir tout</a>
                    </div>
                    @if($recentTransactions->count())
                        <ul class="divide-y divide-gray-100">
                            @foreach($recentTransactions as $tx)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ ucfirst($tx->type) }}</p>
                                        <p class="text-xs text-gray-400">{{ $tx->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold 
                                            @if($tx->type == 'DEPOT' || $tx->type == 'COMPENSATION') text-emerald-600
                                            @elseif($tx->type == 'RETRAIT') text-red-500
                                            @else text-gray-700 @endif">
                                            @if($tx->type == 'DEPOT' || $tx->type == 'COMPENSATION') +@endif
                                            {{ number_format($tx->montant, 2) }} {{ $tx->devise }}
                                        </span>
                                        <p class="text-xs text-gray-400">{{ $tx->statut }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-sm py-4">Aucune transaction récente.</p>
                    @endif
                </div>

                <!-- KYC Progress & Actions -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Vérification d'identité (KYC)</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        @if($kycStatus == 'VALIDE')
                            ✅ Votre compte est entièrement vérifié. Vous bénéficiez de limites plus élevées.
                        @elseif($kycStatus == 'REJETE')
                            ❌ Votre dossier a été rejeté. Contactez le support.
                        @else
                            🔒 Finalisez votre vérification pour débloquer toutes les fonctionnalités.
                        @endif
                    </p>
                    <div class="bg-gray-100 h-2 rounded-full mb-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $kycProgress }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mb-6">
                        <span>Identité</span>
                        <span>Justificatif domicile</span>
                        <span>Selfie</span>
                    </div>
                    @if($kycStatus != 'VALIDE')
                        <a href="#" class="inline-block bg-emerald-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-emerald-700 transition">
                            Compléter mon KYC
                        </a>
                    @endif
                </div>
            </div>

            <!-- Recent Ads -->
            <div class="mt-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800">Dernières annonces</h3>
                    <a href="#" class="text-sm text-emerald-600 hover:underline">Créer une annonce</a>
                </div>
                @if($user->annonces()->latest()->take(3)->get()->count())
                    <ul class="divide-y divide-gray-100">
                        @foreach($user->annonces()->latest()->take(3)->get() as $ad)
                            <li class="py-3 flex justify-between">
                                <div>
                                    <span class="font-medium">{{ $ad->type }}</span>
                                    <span class="text-gray-500 text-sm ml-2">{{ $ad->devise_source }} → {{ $ad->devise_cible }}</span>
                                </div>
                                <span class="text-sm text-gray-400">{{ $ad->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-sm py-4">Vous n'avez pas encore d'annonces.</p>
                @endif
            </div>
        </div>
    </main>
</div>

<!-- JavaScript pour le modal -->
<script>
    const profileBtn = document.getElementById('profileButton');
    const modal = document.getElementById('profileModal');

    function toggleModal(event) {
        event.stopPropagation();
        modal.classList.toggle('modal-hidden');
    }

    function closeModal() {
        if (!modal.classList.contains('modal-hidden')) {
            modal.classList.add('modal-hidden');
        }
    }

    profileBtn.addEventListener('click', toggleModal);
    // Fermer le modal en cliquant ailleurs
    document.addEventListener('click', function(event) {
        if (!profileBtn.contains(event.target) && !modal.contains(event.target)) {
            closeModal();
        }
    });
    // Empêcher la fermeture si on clique à l'intérieur du modal
    modal.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>

</body>
</html>