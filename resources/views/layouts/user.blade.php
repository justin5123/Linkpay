{{-- resources/views/layouts/user.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinPay - @yield('title', 'Espace membre')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .transition-smooth { transition: all 0.2s ease; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg flex flex-col h-full">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-emerald-600">LinPay</h1>
            <p class="text-xs text-gray-400 mt-1">Finance sans frontières</p>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }} transition-smooth">
                <span>📊</span> Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>💼</span> Portefeuille
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>💸</span> Transactions
            </a>
            <a href="{{ route('annonce.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('annonce.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }} transition-smooth">
                <span>📢</span> Annonces
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>👥</span> Réseau social
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>🔔</span> Notifications
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>🎫</span> Support
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-smooth">
                <span>⚙️</span> Paramètres
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

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto bg-gray-50">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>