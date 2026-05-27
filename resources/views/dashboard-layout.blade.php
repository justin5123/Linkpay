<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Linpay - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    @include('dashboard.header')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Colonne gauche (menu) -->
            <div class="lg:w-1/4">
                @include('dashboard.left-sidebar')
            </div>

            <!-- Colonne centrale (contenu dynamique) -->
            <div class="lg:w-2/4">
                @yield('content')
            </div>

            <!-- Colonne droite (suggestions, stats) -->
            <div class="lg:w-1/4">
                @include('dashboard.right-sidebar')
            </div>
        </div>
    </div>
</body>
</html>