<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinPay - @yield('title', 'Espace membre')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .transition-smooth { transition: all 0.2s ease; }
    </style>
    @stack('styles')
    @vite(['resources/js/app.js'])
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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }} transition-smooth">
                <span>📊</span> Dashboard
            </a>
            <a href="{{ route('wallet.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>💼</span> Portefeuille
            </a>
            <a href="{{ route('kyc.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>🪪</span> Vérification KYC
            </a>
            
            <a href="{{ route('wallet.transactions') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('wallet.transactions') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }} transition-smooth">
                <span>💸</span> Transactions
            </a>
            <a href="{{ route('annonce.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('annonce.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }} transition-smooth">
                <span>📢</span> Annonces
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
        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 rounded-xl text-red-600 hover:bg-red-50 transition-smooth">
                    <span>🚪</span> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay pour fermer la sidebar sur mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" style="display: none;"></div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto bg-gray-50">
        <!-- Header responsive -->
        <header class="bg-white/70 backdrop-blur-md shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                <button id="openSidebarBtn" class="lg:hidden text-gray-600 hover:text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex-1"></div>
                <!-- Notifications avec badge -->
                <div class="relative">
                    <button id="notificationBell" class="relative text-gray-600 hover:text-emerald-600 transition">
                        <span class="text-2xl">🔔</span>
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
                <div class="w-10 hidden lg:block"></div>
            </div>
        </header>

        <!-- Contenu dynamique -->
        <div class="container mx-auto px-4 py-6 lg:px-8">
            @yield('content')
        </div>
    </main>
</div>

<script>
    (function() {
        'use strict';

        // ============================================================
        // 1. Éléments DOM
        // ============================================================
        const bell = document.getElementById('notificationBell');
        const dropdown = document.getElementById('notificationDropdown');
        const list = document.getElementById('notificationList');
        const badge = document.getElementById('notificationBadge');

        // Si les éléments n'existent pas, on sort silencieusement
        if (!bell || !dropdown || !list || !badge) {
            console.warn('Éléments de notification introuvables. Vérifiez les IDs.');
            return;
        }

        // Token CSRF depuis la balise meta
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        // ============================================================
        // 2. Fonctions utilitaires
        // ============================================================
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            const div = document.createElement('div');
            div.textContent = unsafe;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // ============================================================
        // 3. Mise à jour du badge (compteur)
        // ============================================================
        function updateBadge() {
            fetch('/notifications/unread-count', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(data => {
                const count = data.count || 0;
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(err => console.error('Erreur mise à jour badge:', err));
        }

        // ============================================================
        // 4. Chargement de la liste des notifications
        // ============================================================
        function loadNotifications() {
            fetch('/notifications/json', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(data => {
                // La réponse peut être un tableau directement ou un objet avec une clé 'notifications'
                const notifications = Array.isArray(data) ? data : (data.notifications || []);

                if (notifications.length === 0) {
                    list.innerHTML = `
                        <div class="p-4 text-center text-gray-500 text-sm">
                            Aucune notification
                        </div>
                    `;
                    return;
                }

                let html = '';
                notifications.forEach(notif => {
                    const isUnread = !notif.est_lu;
                    const bgClass = isUnread ? 'bg-emerald-50' : 'hover:bg-gray-50';
                    html += `
                        <div class="p-3 border-b border-gray-100 ${bgClass} transition notification-item" data-id="${notif.id}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 mr-2">
                                    <p class="text-sm font-semibold text-gray-800">${escapeHtml(notif.titre)}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">${escapeHtml(notif.message)}</p>
                                    <span class="text-xs text-gray-400">${formatDate(notif.created_at)}</span>
                                </div>
                                ${isUnread ? `
                                    <button class="mark-read-btn text-emerald-600 text-xs hover:underline shrink-0 ml-2" data-id="${notif.id}">
                                        Marquer lu
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });
                list.innerHTML = html;

                // Attacher les événements "Marquer comme lu"
                document.querySelectorAll('.mark-read-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const id = this.dataset.id;
                        markAsRead(id, this.closest('.notification-item'));
                    });
                });
            })
            .catch(err => {
                console.error('Erreur chargement notifications:', err);
                list.innerHTML = `
                    <div class="p-4 text-center text-red-500 text-sm">
                        Erreur de chargement
                    </div>
                `;
            });
        }

        // ============================================================
        // 5. Marquer une notification comme lue
        // ============================================================
        function markAsRead(id, element) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(() => {
                // Supprimer le fond vert et le bouton
                if (element) {
                    element.classList.remove('bg-emerald-50');
                    const btn = element.querySelector('.mark-read-btn');
                    if (btn) btn.remove();
                }
                // Mettre à jour le badge
                updateBadge();
            })
            .catch(err => console.error('Erreur marquage lu:', err));
        }

        // ============================================================
        // 6. Gestion de l'ouverture/fermeture du dropdown
        // ============================================================
        bell.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                loadNotifications(); // Charger au moment de l'ouverture
            } else {
                dropdown.classList.add('hidden');
            }
        });

        // Fermer le dropdown en cliquant à l'extérieur
        document.addEventListener('click', function(e) {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // ============================================================
        // 7. Initialisation et polling
        // ============================================================
        // Mise à jour du badge au chargement
        updateBadge();

        // Mise à jour périodique toutes les 30 secondes
        setInterval(updateBadge, 30000);

        // Optionnel : si le dropdown est déjà ouvert au chargement (rare), on charge la liste
        if (!dropdown.classList.contains('hidden')) {
            loadNotifications();
        }

        console.log('Notifications initialisées ✅');
    })();
</script>

@stack('scripts')
</body>
</html>