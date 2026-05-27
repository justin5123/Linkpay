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
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #eef2ff;
        }
        .linpay-card {
            background-color: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .welcome-col {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            border-radius: 2rem 0 0 2rem;
        }
        .form-col {
            background-color: #ffffff;
            padding: 2rem;
        }
        .input-linpay {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            padding: 0.85rem 1rem;
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
            padding: 0.85rem 1.5rem;
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
            font-size: 1.75rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .form-subtitle {
            color: #6b7280;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        @media (min-width: 768px) {
            .form-col {
                padding: 2.5rem;
            }
            .welcome-col {
                flex: 0.8;
            }
            .form-col {
                flex: 1.2;
            }
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-6xl w-full linpay-card flex flex-col md:flex-row">
            <!-- Colonne gauche : bienvenue -->
            <div class="w-full md:w-2/5 welcome-col p-8 md:p-10 flex flex-col justify-center text-white">
                <div class="mb-6">
                    <div class="text-4xl font-extrabold tracking-tight">Linpay</div>
                    <div class="h-1 w-12 bg-white/40 mt-2 rounded-full"></div>
                </div>
                <h2 class="text-2xl font-bold mb-4">Bienvenue</h2>
                <p class="text-emerald-100 mb-6">Le réseau social financier sans frais, instantané et sécurisé.</p>
                <ul class="space-y-3 text-sm text-emerald-100">
                    <li class="flex items-center">✓ Compensation locale 0%</li>
                    <li class="flex items-center">✓ Transactions instantanées</li>
                    <li class="flex items-center">✓ Séquestre intégré</li>
                    <li class="flex items-center">✓ Réputation & KYC</li>
                </ul>
            </div>

            <!-- Colonne droite : formulaire -->
            <div class="w-full md:w-3/5 form-col">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>