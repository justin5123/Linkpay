@extends('layouts.app')

@section('title', 'Linpay - Transferts d\'argent sans frais, instantanés et sécurisés')

@section('content')
<!-- Hero avec dégradé vert -->
<div class="relative overflow-hidden bg-gradient-to-br from-emerald-800 via-teal-700 to-emerald-800 text-white">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 py-24 sm:py-32 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight">
            Linpay
            <span class="block text-emerald-200 text-3xl md:text-4xl mt-2">La finance sans frontières</span>
        </h1>
        <p class="mt-6 text-xl max-w-2xl mx-auto text-emerald-100">
            Envoyez et recevez de l'argent à l'international <strong class="text-white">sans frais bancaires</strong>, <strong class="text-white">sans délai</strong>, grâce à notre réseau social de compensation financière.
        </p>
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-emerald-700 px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-100 transition transform hover:scale-105">
                Commencer gratuitement
            </a>
            <a href="{{ route('features') }}" class="border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-emerald-700 transition">
                En savoir plus
            </a>
        </div>
        <div class="mt-16 flex justify-center space-x-8 text-sm text-emerald-200">
            <span>🔒 100% sécurisé</span>
            <span>⚡ Transactions instantanées</span>
            <span>💸 Zéro frais cachés</span>
        </div>
    </div>
    <!-- Vague décorative -->
    <div class="absolute bottom-0 w-full">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-12">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="white" opacity="0.2"></path>
        </svg>
    </div>
</div>

<!-- Section Problème & Solution -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Le problème</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Les transferts bancaires classiques sont trop chers
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                Frais de change, commissions, délais de 3 à 5 jours... Jusqu'à 7% du montant part en frais.
            </p>
        </div>
        <div class="mt-16 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">La solution Linpay</h3>
                <p class="mt-4 text-gray-600 text-lg">
                    Nous mettons en relation des personnes qui ont des besoins inverses : envoyer vs recevoir. 
                    Les flux s'annulent localement, <strong>plus besoin de faire transiter l'argent à l'international</strong>.
                    Résultat : <span class="font-bold text-emerald-600">0% de frais, instantané, et sécurisé</span>.
                </p>
                <div class="mt-8 flex space-x-4">
                    <div class="flex items-center"><span class="text-green-500 text-2xl mr-2">✓</span> Compensation P2P</div>
                    <div class="flex items-center"><span class="text-green-500 text-2xl mr-2">✓</span> Séquestre intégré</div>
                </div>
            </div>
            <div class="bg-emerald-50 p-8 rounded-2xl shadow-lg text-center">
                <div class="text-6xl mb-4">💰 → 🔄 → 💰</div>
                <p class="text-gray-700">Votre argent reste local. Seule la dette est compensée.</p>
            </div>
        </div>
    </div>
</div>

<!-- Section Fonctionnalités (3 cartes) -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Pourquoi nous choisir</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Une expérience financière repensée</p>
        </div>
        <div class="mt-12 grid gap-8 md:grid-cols-3">
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Zéro frais de transaction</h3>
                    <p class="mt-2 text-gray-600">Pas de commission, pas de frais de change. Notre modèle de compensation élimine les intermédiaires.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Transactions instantanées</h3>
                    <p class="mt-2 text-gray-600">Finis les 3 à 5 jours d'attente. Dès qu'un matching est trouvé, la compensation est immédiate.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Séquestre sécurisé</h3>
                    <p class="mt-2 text-gray-600">L'argent est bloqué sur un compte tiers jusqu'à validation des deux parties. Aucun risque de fraude.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Comment ça marche -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Fonctionnement</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">En 4 étapes simples</p>
        </div>
        <div class="mt-12">
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div class="flex-1 text-center"><div class="w-20 h-20 bg-emerald-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto">1</div><h3 class="mt-4 text-xl font-bold">Publiez une annonce</h3><p class="mt-2 text-gray-500">Indiquez le montant, la devise, et si vous voulez envoyer ou recevoir.</p></div>
                <div class="hidden md:block text-emerald-300 text-3xl">→</div>
                <div class="flex-1 text-center"><div class="w-20 h-20 bg-emerald-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto">2</div><h3 class="mt-4 text-xl font-bold">Matching intelligent</h3><p class="mt-2 text-gray-500">Notre algorithme trouve la meilleure compensation locale.</p></div>
                <div class="hidden md:block text-emerald-300 text-3xl">→</div>
                <div class="flex-1 text-center"><div class="w-20 h-20 bg-emerald-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto">3</div><h3 class="mt-4 text-xl font-bold">Séquestre des fonds</h3><p class="mt-2 text-gray-500">L'argent est bloqué, la transaction est sécurisée.</p></div>
                <div class="hidden md:block text-emerald-300 text-3xl">→</div>
                <div class="flex-1 text-center"><div class="w-20 h-20 bg-emerald-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto">4</div><h3 class="mt-4 text-xl font-bold">Validation & déblocage</h3><p class="mt-2 text-gray-500">Les deux parties confirment, les fonds sont libérés.</p></div>
            </div>
        </div>
    </div>
</div>

<!-- Section Statistiques -->
<div class="bg-emerald-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div><div class="text-4xl font-extrabold">0%</div><div class="mt-2 text-emerald-200">de frais de transaction</div></div>
            <div><div class="text-4xl font-extrabold"><span id="counter-users">0</span>+</div><div class="mt-2 text-emerald-200">utilisateurs actifs</div></div>
            <div><div class="text-4xl font-extrabold"><span id="counter-transactions">0</span>k€</div><div class="mt-2 text-emerald-200">économisés en frais bancaires</div></div>
        </div>
    </div>
</div>

<!-- Section Témoignage -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Ils nous font confiance</h2>
        <div class="mt-12 max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">
            <div class="text-5xl text-emerald-300">“</div>
            <p class="mt-4 text-gray-700 text-lg italic">J'utilise Linpay pour envoyer de l'argent à ma famille au cameroun. Fini les 10% de frais Western Union ! La compensation locale est une révolution.</p>
            <div class="mt-6 font-semibold">— Amadou D., utilisateur depuis 3 mois</div>
        </div>
    </div>
</div>

<!-- Appel à l'action -->
<div class="bg-white py-16">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900">Prêt à rejoindre la révolution financière ?</h2>
        <p class="mt-4 text-xl text-gray-500">Inscrivez-vous gratuitement et commencez à économiser dès aujourd'hui.</p>
        <div class="mt-8"><a href="{{ route('register') }}" class="bg-emerald-600 text-white px-8 py-4 rounded-full text-lg font-bold shadow-lg hover:bg-emerald-700 transition transform hover:scale-105 inline-block">Créer mon compte gratuitement</a></div>
    </div>
</div>

@push('scripts')
<script>
    const counterUsers = document.getElementById('counter-users');
    const counterTransactions = document.getElementById('counter-transactions');
    let animated = false;
    function animateNumbers() {
        if (animated) return;
        animated = true;
        let users = 0, transactions = 0;
        const targetUsers = 12500, targetTransactions = 3420;
        const interval = setInterval(() => {
            if (users < targetUsers) { users += Math.ceil(targetUsers / 50); if (users > targetUsers) users = targetUsers; counterUsers.innerText = users.toLocaleString(); }
            if (transactions < targetTransactions) { transactions += Math.ceil(targetTransactions / 50); if (transactions > targetTransactions) transactions = targetTransactions; counterTransactions.innerText = transactions.toLocaleString(); }
            if (users >= targetUsers && transactions >= targetTransactions) clearInterval(interval);
        }, 30);
    }
    const statsSection = document.querySelector('.bg-emerald-700');
    const observer = new IntersectionObserver((entries) => { entries.forEach(entry => { if (entry.isIntersecting) animateNumbers(); }); }, { threshold: 0.5 });
    observer.observe(statsSection);
</script>
@endpush
@endsection
