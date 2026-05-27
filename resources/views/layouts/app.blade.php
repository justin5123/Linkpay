<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Linpay - Réseau social financier')</title>

    <!-- Fonts avec variantes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Tailwind + Alpine via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Animation subtile d'apparition pour le contenu principal */
        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(12px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        main {
            animation: fadeSlideUp 0.5s ease-out;
        }
        /* Effet de brillance au survol des liens */
        .nav-link-glow {
            transition: all 0.2s ease;
        }
        .nav-link-glow:hover {
            text-shadow: 0 0 6px rgba(16, 185, 129, 0.3);
        }
        /* Fond avec grain subtil (optionnel) */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
            z-index: 0;
        }
        /* Glassmorphisme plus marqué sur la nav */
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }
        /* Bouton inscription animé */
        .btn-register {
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        .btn-register:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(16, 185, 129, 0.4);
        }
        /* Footer avec dégradé */
        .footer-grad {
            background: linear-gradient(135deg, #111827 0%, #1e293b 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100 relative">
    <div class="min-h-screen flex flex-col relative z-10">
        <!-- Navigation principale (glassmorphisme amélioré) -->
        <nav class="glass-nav shadow-sm sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo avec animation légère -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-extrabold bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent hover:from-emerald-400 hover:to-teal-400 transition-all duration-300">
                            Linpay
                        </a>
                    </div>

                    <!-- Liens desktop (effet de survol amélioré) -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="nav-link-glow text-gray-700 hover:text-emerald-600 transition font-medium {{ request()->routeIs('home') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">Accueil</a>
                        <a href="{{ route('about') }}" class="nav-link-glow text-gray-700 hover:text-emerald-600 transition font-medium {{ request()->routeIs('about') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">À propos</a>
                        <a href="{{ route('features') }}" class="nav-link-glow text-gray-700 hover:text-emerald-600 transition font-medium {{ request()->routeIs('features') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">Fonctionnalités</a>
                        <a href="{{ route('contact') }}" class="nav-link-glow text-gray-700 hover:text-emerald-600 transition font-medium {{ request()->routeIs('contact') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">Contact</a>
                    </div>

                    <!-- Boutons connexion / utilisateur -->
                    <div class="hidden md:flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 transition font-medium">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-register bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-5 py-2 rounded-full font-semibold shadow-md transition">
                                    Inscription
                                </a>
                            @endif
                        @else
                            <div class="relative" id="user-menu-container">
                                <button id="user-menu-button" class="flex items-center space-x-2 text-gray-700 hover:text-emerald-600 focus:outline-none transition">
                                    <span>{{ Auth::user()->nom }}</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 hidden z-50 transition-all duration-200 origin-top-right">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-t-xl">Tableau de bord</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">Mon profil</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-b-xl">Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <!-- Bouton menu mobile (amélioré) -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" class="text-gray-500 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-md p-1 transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menu mobile (animé) -->
            <div id="mobile-menu" class="md:hidden bg-white/90 backdrop-blur-md border-t border-gray-200 hidden overflow-hidden transition-all duration-300">
                <div class="px-4 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-md font-medium transition">Accueil</a>
                    <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-md font-medium transition">À propos</a>
                    <a href="{{ route('features') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-md font-medium transition">Fonctionnalités</a>
                    <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-md font-medium transition">Contact</a>
                    <div class="border-t border-gray-200 my-2"></div>
                    @guest
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-md font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-md font-medium text-center">Inscription</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-md">Tableau de bord</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-md">Mon profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-md">Déconnexion</button>
                        </form>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Contenu principal (animation d'apparition) -->
        <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        <!-- Footer stylisé avec dégradé et liens lumineux -->
        <footer class="footer-grad text-gray-300 mt-auto">
            <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="text-2xl font-extrabold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">Linpay</div>
                        <p class="mt-2 text-sm text-gray-400">Le réseau social financier qui révolutionne les transferts d'argent.</p>
                        <div class="flex space-x-4 mt-4">
                            <a href="#" class="text-gray-400 hover:text-white transition transform hover:scale-110"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                            <a href="#" class="text-gray-400 hover:text-white transition transform hover:scale-110"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.72-11.572 10.044 10.044 0 002.462-2.548z"/></svg></a>
                            <a href="#" class="text-gray-400 hover:text-white transition transform hover:scale-110"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12c0 5.302 3.438 9.8 8.205 11.387.6.113.82-.26.82-.58 0-.287-.01-1.05-.015-2.06-3.338.726-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.082-.73.082-.73 1.205.085 1.838 1.237 1.838 1.237 1.07 1.834 2.807 1.304 3.492.997.108-.775.418-1.305.762-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.468-2.38 1.235-3.22-.123-.3-.535-1.52.117-3.16 0 0 1.008-.322 3.3 1.23.96-.267 1.98-.4 3-.405 1.02.005 2.04.138 3 .405 2.29-1.552 3.297-1.23 3.297-1.23.653 1.64.24 2.86.118 3.16.768.84 1.233 1.91 1.233 3.22 0 4.61-2.804 5.62-5.476 5.92.43.37.824 1.102.824 2.22 0 1.602-.015 2.894-.015 3.287 0 .322.216.698.83.578C20.565 21.795 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg></a>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold mb-3">Liens rapides</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors duration-200">Accueil</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-emerald-400 transition-colors duration-200">À propos</a></li>
                            <li><a href="{{ route('features') }}" class="hover:text-emerald-400 transition-colors duration-200">Fonctionnalités</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-emerald-400 transition-colors duration-200">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold mb-3">Informations légales</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-emerald-400 transition-colors duration-200">Conditions d'utilisation</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors duration-200">Confidentialité</a></li>
                            <li><a href="#" class="hover:text-emerald-400 transition-colors duration-200">Mentions légales</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold mb-3">Nous contacter</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg><span>support@linpay.com</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg><span>+237 658 428 239</span></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} Linpay. Tous droits réservés.
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts améliorés pour le dropdown et le menu mobile -->
    <script>
        (function() {
            // Menu mobile
            const mobileButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileButton && mobileMenu) {
                mobileButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Dropdown utilisateur
            const userButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-menu-dropdown');
            if (userButton && userDropdown) {
                let closeTimeout;
                function closeDropdown() { userDropdown.classList.add('hidden'); }
                function toggleDropdown(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                }
                userButton.addEventListener('click', toggleDropdown);
                document.addEventListener('click', (event) => {
                    if (!userButton.contains(event.target) && !userDropdown.contains(event.target)) closeDropdown();
                });
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDropdown(); });
            }
        })();
    </script>

    @stack('scripts')
    @livewireScripts
</body>
</html>