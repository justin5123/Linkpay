@extends('layouts.app')

@section('title', 'À propos - Linpay')

@section('content')
<div class="bg-gradient-to-r from-emerald-700 to-teal-700 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold">À propos de Linpay</h1>
        <p class="mt-4 text-xl text-emerald-100 max-w-2xl mx-auto">Notre mission : rendre les transferts d'argent internationaux accessibles à tous, sans frais et sans délai.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div><h2 class="text-3xl font-extrabold text-gray-900">Une idée simple, un impact énorme</h2><p class="mt-4 text-gray-600 leading-relaxed">Linpay est né d’un constat : les transferts d’argent entre pays coûtent jusqu’à 12% du montant et prennent plusieurs jours. Les personnes qui en ont le plus besoin – travailleurs migrants, familles – paient le plus cher.</p><p class="mt-3 text-gray-600 leading-relaxed">Notre solution : un réseau social financier qui met en relation les émetteurs et les récepteurs localement. Les flux s’annulent, plus besoin de faire transiter l’argent à l’international. <strong>Résultat : 0% de frais, instantané et sécurisé</strong>.</p><div class="mt-6 flex items-center space-x-2 text-emerald-600"><span class="font-semibold">Depuis 2024</span><span>•</span><span class="font-semibold">100% camerounais</span></div></div>
            <div class="bg-emerald-50 rounded-2xl p-8 text-center"><div class="text-6xl mb-4">🌍</div><p class="text-gray-700 italic">"La finance doit être au service des personnes, pas des intermédiaires."</p><p class="mt-2 text-emerald-600 font-semibold">— Équipe Linpay</p></div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12"><h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Nos valeurs</h2><p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Ce qui nous anime au quotidien</p></div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-6 text-center shadow-sm hover:shadow-md transition"><div class="text-4xl mb-3">🤝</div><h3 class="text-xl font-bold text-gray-900">Transparence</h3><p class="mt-2 text-gray-600">Aucun frais caché, des règles claires, une communauté ouverte.</p></div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm hover:shadow-md transition"><div class="text-4xl mb-3">⚡</div><h3 class="text-xl font-bold text-gray-900">Rapidité</h3><p class="mt-2 text-gray-600">Les transactions sont compensées en temps réel, attente bancaire limitée.</p></div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm hover:shadow-md transition"><div class="text-4xl mb-3">🔒</div><h3 class="text-xl font-bold text-gray-900">Sécurité</h3><p class="mt-2 text-gray-600">Un protocole de séquestre numérique garantit chaque transaction.</p></div>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12"><h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">L'équipe</h2><p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Des passionnés de la fintech inclusive</p></div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center"><div class="w-32 h-32 bg-emerald-100 rounded-full mx-auto flex items-center justify-center text-4xl">👨‍💻</div><h3 class="mt-4 text-xl font-bold">Arnaule F.</h3><p class="text-emerald-600">Fondateur & Lead Dev</p><p class="text-gray-500 text-sm mt-2">Ancien ingénieur fintech, il a conçu l’algorithme de compensation.</p></div>
            <div class="text-center"><div class="w-32 h-32 bg-emerald-100 rounded-full mx-auto flex items-center justify-center text-4xl">👩‍💼</div><h3 class="mt-4 text-xl font-bold">Sarah M.</h3><p class="text-emerald-600">Responsable Opérations</p><p class="text-gray-500 text-sm mt-2">Experte en conformité et relation utilisateurs.</p></div>
            <div class="text-center"><div class="w-32 h-32 bg-emerald-100 rounded-full mx-auto flex items-center justify-center text-4xl">📱</div><h3 class="mt-4 text-xl font-bold">David K.</h3><p class="text-emerald-600">Designer UX/UI</p><p class="text-gray-500 text-sm mt-2">A imaginé une interface simple et intuitive.</p></div>
        </div>
    </div>
</div>

<div class="bg-gradient-to-r from-emerald-600 to-teal-600 py-16">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-white">Rejoignez l'aventure Linpay</h2>
        <p class="mt-4 text-xl text-emerald-100">Faites partie de la révolution des transferts d'argent sans frais.</p>
        <div class="mt-8"><a href="{{ route('register') }}" class="bg-white text-emerald-700 px-8 py-3 rounded-full font-bold shadow-lg hover:shadow-xl transition transform hover:scale-105 inline-block">Créer mon compte</a></div>
    </div>
</div>
@endsection