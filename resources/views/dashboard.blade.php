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
        .sidebar-link.active { background-color: #ecfdf5; color: #047857; }
        .transition-smooth { transition: all 0.2s ease; }
        .modal-hidden { display: none; }
        .toast { transition: opacity 0.5s ease-in-out; }
        .progress-bar { transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen flex-col lg:flex-row">
    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:flex lg:shrink-0">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-emerald-600">LinPay</h1>
                <p class="text-xs text-gray-400 mt-1">Finance sans frontières</p>
            </div>
            <button id="closeSidebarBtn" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth active">
                <span class="text-xl">📊</span> Dashboard
            </a>
            <a href="{{ route('wallet.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>💼</span> Portefeuille
            </a>
            <a href="{{ route('wallet.transactions') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span class="text-xl">💸</span> Transactions
            </a>
            <a href="{{ route('annonce.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span>📢</span> Nouvelle annonce
            </a>
            <a href="{{ route('social.timeline') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span class="text-xl">👥</span> Réseau social
            </a>
            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span class="text-xl">🔔</span> Notifications
            </a>
            <a href="{{ route('support.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>🎫</span> Support
            </a>
            <a href="{{ route('referral.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span class="text-xl">🎯</span> Parrainage
            </a>
            <a href="{{ route('settings.profile') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-smooth">
                <span class="text-xl">⚙️</span> Paramètres
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-left text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                    <span class="text-lg">🚪</span> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"></div>

    <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- HEADER -->
        <header class="bg-white/70 backdrop-blur-md shadow-sm sticky top-0 z-10">
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 lg:px-8">
                <button id="openSidebarBtn" class="lg:hidden text-gray-600 hover:text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex-1">
                    <h2 class="text-base sm:text-xl md:text-3xl font-bold text-gray-800 truncate">
                        Bonjour {{ $user->prenom ?? $user->nom }} 👋
                    </h2>
                    <p class="text-xs text-gray-500">{{ now()->translatedFormat('l j F Y') }}</p>
                </div>
                <div class="hidden md:block flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 bg-white/90 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm shadow-sm">
                        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button id="notificationBell" class="relative text-gray-600 hover:text-emerald-600 transition">
                            <span class="text-xl sm:text-2xl">🔔</span>
                            <span id="notificationBadge" class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold hidden">0</span>
                        </button>
                        <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 hidden">
                            <div class="p-3 border-b border-gray-100 font-semibold text-gray-700">Notifications</div>
                            <div id="notificationList" class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                                <div class="p-4 text-center text-gray-500 text-sm">Chargement...</div>
                            </div>
                            <div class="p-2 border-t border-gray-100 text-center">
                                <a href="{{ route('notifications.index') }}" class="text-xs text-emerald-600 hover:underline">Voir toutes</a>
                            </div>
                        </div>
                    </div>
                    <!-- Profil -->
                    <div class="relative">
                        <button id="profileButton" class="flex items-center gap-2 focus:outline-none group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold shadow-md group-hover:shadow-lg transition">
                                {{ strtoupper(substr($user->prenom ?? $user->nom, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs sm:text-sm font-semibold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</p>
                                <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $user->email }}</p>
                            </div>
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
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

        <!-- Toasts messages -->
        @if(session('success'))
            <div id="toastSuccess" class="fixed top-20 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md toast">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="toastError" class="fixed top-20 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-md toast">
                {{ session('error') }}
            </div>
        @endif

        <div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto space-y-6 md:space-y-8">

            <!-- ============================================================ -->
            <!-- BANDEAU KYC (amélioré)                                       -->
            <!-- ============================================================ -->
            @if($user->kyc_status !== 'COMPLETED' && $user->kyc_status !== 'STEP4_VALIDATED')
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg shadow-sm">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-700 font-medium">
                                    <strong>⚠️ Vérification KYC incomplète</strong>
                                </p>
                                <p class="text-xs text-amber-600 mt-0.5">
                                    Certaines fonctionnalités (Wallet, Annonces, Réseau social) sont bloquées tant que vous n'avez pas terminé votre vérification KYC.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0 flex-wrap">
                            <span class="text-xs font-medium text-amber-700 bg-amber-100 px-3 py-1 rounded-full">
                                @php
                                    $progressText = match($user->kyc_status) {
                                        'STEP1_PENDING' => 'Étape 1 en attente',
                                        'STEP1_REJECTED' => 'Étape 1 rejetée',
                                        'STEP2_PENDING' => 'Étape 2 en attente',
                                        'STEP2_REJECTED' => 'Étape 2 rejetée',
                                        'STEP3_PENDING' => 'Étape 3 en attente',
                                        'STEP3_REJECTED' => 'Étape 3 rejetée',
                                        'STEP4_PENDING' => 'Étape 4 en attente',
                                        'STEP4_REJECTED' => 'Étape 4 rejetée',
                                        default => 'Non commencé'
                                    };
                                @endphp
                                {{ $progressText }}
                            </span>
                            <a href="{{ route('kyc.status') }}" class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm hover:shadow-md">
                                Compléter mon KYC
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                    <!-- Barre de progression KYC -->
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-amber-600 mb-1">
                            <span>Identité</span>
                            <span>Domicile</span>
                            <span>Pièce d'identité</span>
                            <span>Selfie</span>
                        </div>
                        <div class="w-full bg-amber-200 h-1.5 rounded-full overflow-hidden">
                            @php
                                $progress = 0;
                                if (in_array($user->kyc_status, ['STEP1_PENDING', 'STEP1_VALIDATED', 'STEP1_REJECTED'])) $progress = 25;
                                if (in_array($user->kyc_status, ['STEP2_PENDING', 'STEP2_VALIDATED', 'STEP2_REJECTED'])) $progress = 50;
                                if (in_array($user->kyc_status, ['STEP3_PENDING', 'STEP3_VALIDATED', 'STEP3_REJECTED'])) $progress = 75;
                                if (in_array($user->kyc_status, ['STEP4_PENDING'])) $progress = 100;
                                if ($user->kyc_status == 'COMPLETED') $progress = 100;
                            @endphp
                            <div class="h-full bg-emerald-500 rounded-full progress-bar" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Wallet Card -->
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white p-6 sm:p-8 rounded-3xl shadow-xl">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-emerald-100 text-sm">Solde disponible</p>
                        <h1 class="text-3xl sm:text-4xl font-bold mt-2 tracking-tight">
                            @if($wallet) {{ number_format($wallet->solde, 2) }} {{ $wallet->devise }} @else 0.00 {{ $user->devise ?? 'XAF' }} @endif
                        </h1>
                        <p class="mt-2 text-emerald-100 text-sm truncate">N° {{ $wallet->numero_compte ?? '----' }}</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs whitespace-nowrap">
                        @if($wallet && $wallet->est_actif) Actif @else En attente @endif
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('wallet.send') }}" class="bg-white text-emerald-700 px-4 py-2 rounded-full text-sm font-semibold shadow hover:bg-gray-100 transition">💸 Envoyer</a>
                    <a href="{{ route('wallet.deposit') }}" class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-white/30 transition">➕ Déposer</a>
                    <a href="{{ route('wallet.withdraw') }}" class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-white/30 transition">⬇️ Retirer</a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center"><span class="text-gray-500 text-sm">Annonces publiées</span><span class="text-xl">📢</span></div>
                    <p class="text-2xl font-bold mt-2">{{ $totalAnnonces }}</p>
                </div>
                <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center"><span class="text-gray-500 text-sm">Transactions réussies</span><span class="text-xl">✅</span></div>
                    <p class="text-2xl font-bold mt-2">{{ $totalTransactions }}</p>
                </div>
                <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center"><span class="text-gray-500 text-sm">Niveau KYC</span><span class="text-xl">🪪</span></div>
                    <p class="text-2xl font-bold mt-2">
                        @if($user->kyc_status == 'COMPLETED') Validé
                        @elseif($user->kyc_status == 'STEP4_PENDING') En attente (étape 4)
                        @elseif($user->kyc_status == 'STEP3_PENDING') En attente (étape 3)
                        @elseif($user->kyc_status == 'STEP2_PENDING') En attente (étape 2)
                        @elseif($user->kyc_status == 'STEP1_PENDING') En attente (étape 1)
                        @elseif($user->kyc_status == 'NOT_STARTED') Non commencé
                        @else En attente @endif
                    </p>
                    <div class="w-full bg-gray-200 h-1.5 rounded-full mt-2">
                        @php
                            $progress = 0;
                            if (in_array($user->kyc_status, ['STEP1_PENDING', 'STEP1_VALIDATED', 'STEP1_REJECTED'])) $progress = 25;
                            if (in_array($user->kyc_status, ['STEP2_PENDING', 'STEP2_VALIDATED', 'STEP2_REJECTED'])) $progress = 50;
                            if (in_array($user->kyc_status, ['STEP3_PENDING', 'STEP3_VALIDATED', 'STEP3_REJECTED'])) $progress = 75;
                            if (in_array($user->kyc_status, ['STEP4_PENDING', 'COMPLETED'])) $progress = 100;
                        @endphp
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Transactions & KYC -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4"><h3 class="font-bold text-lg text-gray-800">Transactions récentes</h3><a href="{{ route('wallet.transactions') }}" class="text-sm text-emerald-600 hover:underline">Voir tout</a></div>
                    @if($recentTransactions->count())
                        <ul class="divide-y divide-gray-100">
                            @foreach($recentTransactions as $tx)
                                <li class="py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                    <div><p class="font-medium text-gray-800">{{ ucfirst($tx->type) }}</p><p class="text-xs text-gray-400">{{ $tx->created_at ? $tx->created_at->diffForHumans() : 'Date inconnue' }}</p></div>
                                    <div class="text-right"><span class="font-semibold @if($tx->type == 'DEPOT' || $tx->type == 'COMPENSATION') text-emerald-600 @elseif($tx->type == 'RETRAIT') text-red-500 @else text-gray-700 @endif">@if($tx->type == 'DEPOT' || $tx->type == 'COMPENSATION')+@endif{{ number_format($tx->montant, 2) }} {{ $tx->devise }}</span><p class="text-xs text-gray-400">{{ $tx->statut }}</p></div>
                                </li>
                            @endforeach
                        </ul>
                    @else <p class="text-gray-500 text-sm py-4">Aucune transaction récente.</p> @endif
                </div>
                <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Vérification KYC</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        @if($user->kyc_status == 'COMPLETED') ✅ Votre compte est entièrement vérifié.
                        @elseif(str_contains($user->kyc_status, 'REJECTED')) ❌ Votre dossier a été rejeté. Contactez le support.
                        @else 🔒 Finalisez votre vérification pour débloquer toutes les fonctionnalités. @endif
                    </p>
                    <div class="bg-gray-100 h-2 rounded-full mb-2"><div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $progress }}%"></div></div>
                    <div class="flex justify-between text-xs text-gray-500 mb-6"><span>Identité</span><span>Domicile</span><span>Pièce d'identité</span><span>Selfie</span></div>
                    @if($user->kyc_status != 'COMPLETED')
                        <a href="{{ route('kyc.status') }}" class="inline-block bg-emerald-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-emerald-700 transition">Compléter mon KYC</a>
                    @endif
                </div>
            </div>

            <!-- Dernières annonces -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2"><h3 class="font-bold text-lg text-gray-800">Dernières annonces</h3><a href="{{ route('annonce.create') }}" class="text-sm text-emerald-600 hover:underline">Créer une annonce</a></div>
                @if($user->annonces()->latest()->take(3)->get()->count())
                    <ul class="divide-y divide-gray-100">
                        @foreach($user->annonces()->latest()->take(3)->get() as $ad)
                            <li class="py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2"><div><span class="font-medium">{{ $ad->type }}</span><span class="text-gray-500 text-sm ml-2">{{ $ad->devise_source }} → {{ $ad->devise_cible }}</span></div><span class="text-xs text-gray-400">{{ $ad->created_at->diffForHumans() }}</span></li>
                        @endforeach
                    </ul>
                @else <p class="text-gray-500 text-sm py-4">Aucune annonce pour le moment.</p> @endif
            </div>

            <!-- APPARIEMENTS EN ATTENTE -->
            @if(isset($appariementsEnAttente) && $appariementsEnAttente->count())
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-gray-800 mb-4">📌 Appariements en attente de votre confirmation</h3>
                <div class="space-y-4">
                    @foreach($appariementsEnAttente as $app)
                        @php
                            $annonceA = $app->annonceEnvoi;
                            $annonceB = $app->annonceReception;
                            $estEmetteurA = $app->estEmetteurA ?? ($user->id == $annonceA->users_id);
                            if ($estEmetteurA) {
                                $montant = $app->montant_a_payer;
                                $devise = $app->devise_a_payer;
                                $beneficiaire = $app->beneficiaire_nom;
                                $telephone = $app->beneficiaire_tel;
                            } else {
                                $montant = $app->montant_a_payer;
                                $devise = $app->devise_a_payer;
                                $beneficiaire = $app->beneficiaire_nom;
                                $telephone = $app->beneficiaire_tel;
                            }
                        @endphp
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <p class="text-gray-700">Vous devez payer <strong class="text-emerald-600">{{ number_format($montant,2) }} {{ $devise }}</strong> à <strong>{{ $beneficiaire }}</strong> (tél: {{ $telephone }}).</p>
                            @if($app->dejaAccepte)
                                <p class="text-green-600 text-sm mt-2">✓ Vous avez déjà accepté cet appariement. En attente de l'autre partie.</p>
                            @else
                                <form method="POST" action="{{ route('appariement.accepter', $app) }}" class="mt-3">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">✅ Accepter l'appariement</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- TRANSACTIONS EN COURS -->
            @if($transactionsEnCours->total() > 0)
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                    <h3 class="font-bold text-lg text-gray-800">🔄 Transactions en cours</h3>
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-2">
                        <input type="text" name="search" placeholder="Réf. transaction" value="{{ request('search') }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-200">
                        <select name="statut" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200">
                            <option value="">Tous statuts</option>
                            <option value="EN_ATTENTE" {{ request('statut') == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                            <option value="PAYER_A" {{ request('statut') == 'PAYER_A' ? 'selected' : '' }}>Payeur A payé</option>
                            <option value="PAYER_B" {{ request('statut') == 'PAYER_B' ? 'selected' : '' }}>Payeur B payé</option>
                            <option value="LITIGE" {{ request('statut') == 'LITIGE' ? 'selected' : '' }}>Litige</option>
                        </select>
                        <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-300">Filtrer</button>
                        <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-200">Réinitialiser</a>
                    </form>
                </div>
                <div class="space-y-5">
                    @foreach($transactionsEnCours as $tx)
                        @php
                            $appTx = $tx->appariement;
                            $envoi = $appTx->annonceEnvoi;
                            $reception = $appTx->annonceReception;
                            if ($user->id == $tx->payeur_a_id) {
                                $montant_affiche = $tx->montant_a;
                                $devise_affiche = $envoi->devise_source;
                                $beneficiaire_nom = $reception->beneficiaire_nom ?: ($reception->user->prenom ?? 'Inconnu');
                                $beneficiaire_tel = $reception->beneficiaire_telephone ?: ($reception->user->telephone ?? 'Non renseigné');
                            } else {
                                $montant_affiche = $tx->montant_b;
                                $devise_affiche = $reception->devise_source;
                                $beneficiaire_nom = $envoi->beneficiaire_nom ?: ($envoi->user->prenom ?? 'Inconnu');
                                $beneficiaire_tel = $envoi->beneficiaire_telephone ?: ($envoi->user->telephone ?? 'Non renseigné');
                            }
                        @endphp
                        <div class="border border-gray-200 rounded-xl p-4">
                            <div class="flex flex-wrap justify-between items-start gap-2 mb-3">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">Réf: {{ $tx->reference }}</span>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($tx->statut == 'EN_ATTENTE') bg-yellow-100 text-yellow-800
                                    @elseif($tx->statut == 'PAYER_A') bg-blue-100 text-blue-800
                                    @elseif($tx->statut == 'PAYER_B') bg-blue-100 text-blue-800
                                    @elseif($tx->statut == 'LITIGE') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $tx->statut }}
                                </span>
                            </div>
                            <p class="text-gray-700">Vous devez payer <strong class="text-emerald-600">{{ number_format($montant_affiche,2) }} {{ $devise_affiche }}</strong> à <strong>{{ $beneficiaire_nom }}</strong> (tél: {{ $beneficiaire_tel }}).</p>

                            @if($tx->statut == 'EN_ATTENTE')
                                <form method="POST" action="{{ route('preuve.store', $tx) }}" enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <div class="flex flex-wrap items-center gap-3">
                                        <input type="file" name="preuve" accept="image/*,application/pdf" required class="text-sm border border-gray-300 rounded-lg p-1.5">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">📎 Déposer la preuve de paiement</button>
                                    </div>
                                </form>
                            @elseif($tx->statut == 'PAYER_A' && $user->id == $tx->payeur_b_id)
                                <form method="POST" action="{{ route('transaction.confirmer', $tx) }}" class="mt-4">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700">✅ Confirmer la réception (paiement de l'autre partie)</button>
                                </form>
                            @elseif($tx->statut == 'PAYER_B' && $user->id == $tx->payeur_a_id)
                                <form method="POST" action="{{ route('transaction.confirmer', $tx) }}" class="mt-4">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700">✅ Confirmer la réception (paiement de l'autre partie)</button>
                                </form>
                            @elseif($tx->statut == 'TERMINEE')
                                <p class="text-green-600 mt-2 text-sm">✔️ Transaction terminée.</p>
                            @elseif($tx->statut == 'LITIGE')
                                <p class="text-red-600 mt-2 text-sm">⚠️ Litige en cours. Un administrateur va traiter votre demande.</p>
                            @endif

                            @if(!in_array($tx->statut, ['TERMINEE', 'ANNULEE']))
                                <button onclick="openLitigeModal({{ $tx->id }})" class="mt-3 text-red-500 text-sm underline hover:text-red-700">Signaler un problème</button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $transactionsEnCours->appends(request()->query())->links() }}
                </div>
            </div>
            @endif

            <!-- HISTORIQUE DES TRANSACTIONS TERMINÉES -->
            @if($transactionsCompensees->total() > 0)
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-gray-800 mb-4">📋 Historique des transactions terminées</h3>
                <div class="space-y-5">
                    @foreach($transactionsCompensees as $tx)
                        @php
                            $appTx = $tx->appariement;
                            $envoi = $appTx->annonceEnvoi;
                            $reception = $appTx->annonceReception;
                            if ($user->id == $tx->payeur_a_id) {
                                $montant_affiche = $tx->montant_a;
                                $devise_affiche = $envoi->devise_source;
                                $beneficiaire_nom = $reception->beneficiaire_nom ?: ($reception->user->prenom ?? 'Inconnu');
                                $beneficiaire_tel = $reception->beneficiaire_telephone ?: ($reception->user->telephone ?? 'Non renseigné');
                            } else {
                                $montant_affiche = $tx->montant_b;
                                $devise_affiche = $reception->devise_source;
                                $beneficiaire_nom = $envoi->beneficiaire_nom ?: ($envoi->user->prenom ?? 'Inconnu');
                                $beneficiaire_tel = $envoi->beneficiaire_telephone ?: ($envoi->user->telephone ?? 'Non renseigné');
                            }
                        @endphp
                        <div class="border border-gray-200 rounded-xl p-4">
                            <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">Réf: {{ $tx->reference }}</span>
                                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">Terminée</span>
                            </div>
                            <p class="text-gray-700">Vous avez payé <strong class="text-emerald-600">{{ number_format($montant_affiche,2) }} {{ $devise_affiche }}</strong> à <strong>{{ $beneficiaire_nom }}</strong> (tél: {{ $beneficiaire_tel }}).</p>
                            <button onclick="openLitigeModal({{ $tx->id }})" class="mt-3 text-red-500 text-sm underline hover:text-red-700">⚠️ Signaler un litige</button>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $transactionsCompensees->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Modal Litige -->
<div id="litigeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96 max-w-full mx-4">
        <h3 class="text-lg font-bold mb-2">Signaler un litige</h3>
        <form id="litigeForm" method="POST">
            @csrf
            <textarea name="motif" rows="4" class="w-full border rounded p-2" placeholder="Décrivez le problème..." required></textarea>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeLitigeModal()" class="px-4 py-2 bg-gray-300 rounded">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<script>
    // SIDEBAR
    const sidebar = document.getElementById('sidebar');
    const openSidebarBtn = document.getElementById('openSidebarBtn');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const overlay = document.getElementById('sidebarOverlay');
    function openSidebar() { sidebar.classList.remove('-translate-x-full'); sidebar.classList.add('translate-x-0'); overlay.classList.remove('hidden'); }
    function closeSidebar() { sidebar.classList.add('-translate-x-full'); sidebar.classList.remove('translate-x-0'); overlay.classList.add('hidden'); }
    if (openSidebarBtn) openSidebarBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // PROFIL
    const profileButton = document.getElementById('profileButton');
    const profileModal = document.getElementById('profileModal');
    if (profileButton && profileModal) {
        profileButton.addEventListener('click', (e) => { e.stopPropagation(); profileModal.classList.toggle('modal-hidden'); profileModal.classList.toggle('block'); });
        document.addEventListener('click', (e) => { if (!profileButton.contains(e.target) && !profileModal.contains(e.target)) { profileModal.classList.add('modal-hidden'); profileModal.classList.remove('block'); } });
    }

    // NOTIFICATIONS
    const notificationBell = document.getElementById('notificationBell');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationList = document.getElementById('notificationList');
    const notificationBadge = document.getElementById('notificationBadge');
    function loadNotifications() {
        fetch('{{ route("notifications.json") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(res => res.json())
        .then(data => {
            const notifications = data.notifications || data;
            const unreadCount = Array.isArray(notifications) ? notifications.filter(n => !n.est_lu).length : 0;
            if (unreadCount > 0) { notificationBadge.textContent = unreadCount > 9 ? '9+' : unreadCount; notificationBadge.classList.remove('hidden'); } else { notificationBadge.classList.add('hidden'); }
            if (Array.isArray(notifications) && notifications.length > 0) {
                notificationList.innerHTML = notifications.map(n => `<div class="p-3 hover:bg-gray-50 border-b border-gray-100"><p class="text-sm font-medium text-gray-800">${n.titre}</p><p class="text-xs text-gray-500">${n.message}</p><span class="text-xs text-gray-400">${new Date(n.created_at).toLocaleString()}</span></div>`).join('');
            } else { notificationList.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Aucune notification</div>'; }
        }).catch(err => console.error('Erreur chargement notifications:', err));
    }
    function updateUnreadCount() {
        fetch('{{ route("notifications.unread-count") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json()).then(data => { if (data.count > 0) { notificationBadge.textContent = data.count > 9 ? '9+' : data.count; notificationBadge.classList.remove('hidden'); } else { notificationBadge.classList.add('hidden'); } })
        .catch(err => console.error('Erreur chargement compteur:', err));
    }
    if (notificationBell && notificationDropdown) {
        notificationBell.addEventListener('click', (e) => { e.stopPropagation(); notificationDropdown.classList.toggle('hidden'); if (!notificationDropdown.classList.contains('hidden')) { loadNotifications(); } });
        document.addEventListener('click', (e) => { if (!notificationBell.contains(e.target) && !notificationDropdown.contains(e.target)) { notificationDropdown.classList.add('hidden'); } });
    }
    updateUnreadCount();

    // MODALE LITIGE
    function openLitigeModal(transactionId) {
        const modal = document.getElementById('litigeModal');
        const form = document.getElementById('litigeForm');
        if (modal && form) { form.action = `/transaction/${transactionId}/litige`; modal.classList.remove('hidden'); modal.classList.add('flex'); }
    }
    function closeLitigeModal() { const modal = document.getElementById('litigeModal'); if (modal) { modal.classList.remove('flex'); modal.classList.add('hidden'); } }

    // DISPARITION DES TOASTS
    setTimeout(() => { const success = document.getElementById('toastSuccess'); const error = document.getElementById('toastError'); if (success) { success.style.opacity = '0'; setTimeout(() => success.remove(), 500); } if (error) { error.style.opacity = '0'; setTimeout(() => error.remove(), 500); } }, 5000);
</script>
</body>
</html>