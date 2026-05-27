@extends('layouts.app')

@section('title', 'Contact - Linpay')

@section('content')
<div class="bg-gradient-to-r from-emerald-700 to-teal-700 text-white">
    <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold">Contactez-nous</h1>
        <p class="mt-4 text-xl text-emerald-100 max-w-2xl mx-auto">Une question, une suggestion ou un problème ? Notre équipe vous répond dans les meilleurs délais.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-8 shadow-sm">{{ session('success') }}</div>
    @endif
    <div class="grid md:grid-cols-2 gap-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="mb-5"><label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label><input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('name') border-red-500 @enderror">@error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                <div class="mb-5"><label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label><input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('email') border-red-500 @enderror">@error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                <div class="mb-5"><label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label><textarea name="message" id="message" rows="5" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>@error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition transform hover:scale-[1.02]">Envoyer le message</button>
            </form>
        </div>
        <div class="space-y-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Nos coordonnées</h2>
                <div class="space-y-5">
                    <div class="flex items-start space-x-4"><div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div><div><p class="font-medium text-gray-900">Email</p><a href="mailto:support@linpay.com" class="text-emerald-600 hover:underline">support@linpay.com</a></div></div>
                    <div class="flex items-start space-x-4"><div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></div><div><p class="font-medium text-gray-900">Téléphone</p><a href="tel:+237658428239" class="text-emerald-600 hover:underline">+237 658 428 239</a></div></div>
                    <div class="flex items-start space-x-4"><div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div><div><p class="font-medium text-gray-900">Localisation</p><p class="text-gray-700">Bafoussam, Cameroun</p></div></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-8 pb-2"><h2 class="text-2xl font-bold text-gray-900">Retrouvez-nous</h2></div>
                <div class="w-full h-64 bg-gray-200"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126982.72189229664!2d10.416666!3d5.466667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x105fb0d30f771a39%3A0x7d2f47d3d41b5b2c!2sBafoussam%2C%20Cameroon!5e0!3m2!1sen!2sfr!4v1641234567890!5m2!1sen!2sfr" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
                <div class="p-4 text-center text-sm text-gray-500"><p>📍 Bafoussam, région de l'Ouest, Cameroun</p></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Questions fréquentes</h2>
        <div class="mt-8 grid md:grid-cols-2 gap-6 text-left">
            <div><h3 class="font-semibold text-emerald-600">Quels sont vos horaires de réponse ?</h3><p class="text-gray-600 text-sm mt-1">Notre équipe répond généralement sous 24h ouvrées.</p></div>
            <div><h3 class="font-semibold text-emerald-600">Puis-je vous appeler directement ?</h3><p class="text-gray-600 text-sm mt-1">Oui, du lundi au vendredi de 9h à 17h (heure du Cameroun).</p></div>
        </div>
    </div>
</div>
@endsection