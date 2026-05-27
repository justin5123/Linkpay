@extends('layouts.app')

@section('title', 'Fonctionnalités - Linpay, la finance sans frais')

@section('content')
<div class="bg-gradient-to-r from-emerald-700 to-teal-700 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold">Des fonctionnalités pensées pour vous</h1>
        <p class="mt-4 text-xl text-emerald-100 max-w-2xl mx-auto">Découvrez comment Linpay simplifie vos transferts d'argent, réduit vos frais et sécurise vos transactions.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Pourquoi Linpay est différent</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Tout ce dont vous avez besoin, rien de superflu</p>
        </div>
        <div class="grid md:grid-cols-2 gap-10">
            <div class="bg-gray-50 rounded-2xl p-8 shadow-md hover:shadow-xl transition duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mb-6"><svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg></div>
                <h3 class="text-xl font-bold text-gray-900">Compensation financière locale</h3>
                <p class="mt-2 text-gray-600">Notre algorithme met en relation les personnes qui souhaitent envoyer et recevoir de l'argent dans une même zone géographique. Les flux s'annulent : plus besoin de transfert international réel, donc <strong>0% de frais de change et commission tres reduite</strong>.</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 shadow-md hover:shadow-xl transition duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mb-6"><svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></div>
                <h3 class="text-xl font-bold text-gray-900">Matching partiel automatique</h3>
                <p class="mt-2 text-gray-600">Pas besoin que les montants correspondent parfaitement. Notre système découpe les annonces et les apparie partiellement, maximisant ainsi le taux de compensation. Vous n'attendez jamais plus de 48h.</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 shadow-md hover:shadow-xl transition duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mb-6"><svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
                <h3 class="text-xl font-bold text-gray-900">Séquestre intégré</h3>
                <p class="mt-2 text-gray-600">Les fonds sont bloqués sur un compte séquestre (escrow) dès qu'un match est trouvé. L'argent n'est libéré qu'après confirmation des deux parties. <strong>Zéro risque de fraude ou d'impayé</strong>.</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 shadow-md hover:shadow-xl transition duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mb-6"><svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                <h3 class="text-xl font-bold text-gray-900">Tableau de bord & historique</h3>
                <p class="mt-2 text-gray-600">Visualisez en temps réel vos annonces, les matchs en cours, le montant économisé en frais, et l'historique complet de toutes vos transactions. Des graphiques simples pour tout comprendre.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Et encore plus</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Des outils pour les utilisateurs exigeants</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-6 text-center shadow-sm"><div class="text-4xl mb-3">🔔</div><h3 class="text-lg font-bold">Notifications en temps réel</h3><p class="text-gray-600 text-sm mt-2">Alertes par email et sur le site dès qu'un match est trouvé ou qu'une transaction évolue.</p></div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm"><div class="text-4xl mb-3">📊</div><h3 class="text-lg font-bold">Statistiques personnelles</h3><p class="text-gray-600 text-sm mt-2">Suivez votre impact : montant total compensé, frais évités, vitesse moyenne de matching.</p></div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm"><div class="text-4xl mb-3">🌍</div><h3 class="text-lg font-bold">Multi-devises</h3><p class="text-gray-600 text-sm mt-2">Support des principales devises (EUR, USD, XAF, XOF, GBP, CAD...) avec conversion intégrée.</p></div>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Sécurité & conformité</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Vos fonds sont protégés</p>
        </div>
        <div class="flex flex-wrap justify-center gap-8">
            <div class="flex items-center space-x-3 bg-gray-50 px-5 py-3 rounded-full"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg><span>Authentification à deux facteurs</span></div>
            <div class="flex items-center space-x-3 bg-gray-50 px-5 py-3 rounded-full"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Chiffrement de bout en bout</span></div>
            <div class="flex items-center space-x-3 bg-gray-50 px-5 py-3 rounded-full"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg><span>Conformité RGPD</span></div>
        </div>
    </div>
</div>

<div class="bg-gradient-to-r from-emerald-600 to-teal-600 py-16">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-white">Prêt à passer à Linpay ?</h2>
        <p class="mt-4 text-xl text-emerald-100">Rejoignez des milliers d'utilisateurs qui économisent sur leurs transferts.</p>
        <div class="mt-8"><a href="{{ route('register') }}" class="bg-white text-emerald-700 px-8 py-3 rounded-full font-bold shadow-lg hover:shadow-xl transition transform hover:scale-105 inline-block">Créer un compte gratuitement</a></div>
    </div>
</div>
@endsection