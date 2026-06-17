<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Linpay - Authentification')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #eef2ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .linpay-card {
            background-color: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 72rem;
            display: flex;
            flex-direction: column;
        }
        .welcome-col {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            padding: 2rem 1.5rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .form-col {
            background-color: #ffffff;
            padding: 1.5rem;
        }
        .input-linpay {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            background-color: #f9fafb;
        }
        .input-linpay:focus {
            outline: none;
            border-color: #10b981;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .btn-linpay {
            background-color: #10b981;
            border-radius: 1rem;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            border: none;
            cursor: pointer;
        }
        .btn-linpay:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        .form-subtitle {
            color: #6b7280;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
        }
        /* Responsive */
        @media (min-width: 640px) {
            body { padding: 1.5rem; }
            .linpay-card { flex-direction: row; }
            .welcome-col {
                width: 40%;
                padding: 2rem;
                text-align: left;
                border-radius: 2rem 0 0 2rem;
            }
            .form-col {
                width: 60%;
                padding: 2rem 2.5rem;
            }
            .form-title { font-size: 1.75rem; }
            .form-subtitle { font-size: 0.9rem; }
            .input-linpay { padding: 0.85rem 1rem; }
            .btn-linpay { padding: 0.85rem 1.5rem; }
        }
        @media (min-width: 1024px) {
            body { padding: 2rem; }
            .welcome-col { padding: 3rem; }
            .form-col { padding: 2.5rem 3rem; }
            .form-title { font-size: 2rem; }
        }
        @media (max-width: 639px) {
            .welcome-col {
                border-radius: 2rem 2rem 0 0;
                padding: 1.5rem 1rem;
            }
            .welcome-col .text-4xl { font-size: 2rem; }
            .welcome-col h2 { font-size: 1.5rem; }
            .welcome-col ul { display: none; } /* Masquer la liste sur très petit écran */
            .form-col { padding: 1.25rem; }
            .form-title { font-size: 1.25rem; }
            .input-linpay { font-size: 0.9rem; padding: 0.7rem 0.9rem; }
            .btn-linpay { font-size: 0.9rem; padding: 0.7rem; }
        }
    </style>
</head>
<body>
    <div class="linpay-card">
        <!-- Colonne gauche : bienvenue -->
        <div class="welcome-col">
            <div class="mb-4 sm:mb-6">
                <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">Linpay</div>
                <div class="h-1 w-12 bg-white/40 mt-2 rounded-full mx-auto sm:mx-0"></div>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-4">Bienvenue</h2>
            <p class="text-emerald-100 text-sm sm:text-base mb-4 sm:mb-6">Le réseau social financier sans frais, instantané et sécurisé.</p>
            <ul class="space-y-2 text-sm text-emerald-100 hidden sm:block">
                <li class="flex items-center">✓ Compensation locale 0%</li>
                <li class="flex items-center">✓ Transactions instantanées</li>
                <li class="flex items-center">✓ Séquestre intégré</li>
                <li class="flex items-center">✓ Réputation &amp; KYC</li>
            </ul>
        </div>

        <!-- Colonne droite : formulaire -->
        <div class="form-col">
            {{ $slot }}
        </div>
    </div>
</body>
</html>