@extends('dashboard-layout')

@section('title', 'Fil d’actualité')

@section('content')
<div class="space-y-6">
    @forelse($annonces as $annonce)
    <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-start space-x-3">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 font-bold">
                {{ strtoupper(substr($annonce->utilisateur->nom, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex justify-between">
                    <div>
                        <p class="font-bold text-gray-800">{{ $annonce->utilisateur->nom }}</p>
                        <p class="text-xs text-gray-400">{{ $annonce->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                        {{ $annonce->type === 'envoi' ? 'Envoi' : 'Réception' }}
                    </span>
                </div>
                <p class="mt-2 text-gray-700">
                    @if($annonce->type == 'envoi')
                        💰 Envoi de <strong>{{ number_format($annonce->montant, 2) }} {{ $annonce->devise_source }}</strong>
                    @else
                        💵 Réception de <strong>{{ number_format($annonce->montant, 2) }} {{ $annonce->devise_source }}</strong>
                    @endif
                    @if($annonce->pays_destination)
                        vers <strong>{{ $annonce->pays_destination }}</strong>
                        @if($annonce->montant_converti)
                            (≈ {{ number_format($annonce->montant_converti, 2) }} {{ $annonce->devise_destination }})
                        @endif
                    @endif
                </p>
                @if($annonce->description)
                    <div class="mt-2 bg-gray-50 p-3 rounded-xl text-sm text-gray-600">
                        {{ $annonce->description }}
                    </div>
                @endif
                <div class="flex gap-4 mt-3 text-sm text-gray-500">
                    <button class="hover:text-emerald-600">👍 J'aime (0)</button>
                    <button class="hover:text-emerald-600">💬 Commenter (0)</button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow p-8 text-center text-gray-400">
        <p>Aucune annonce active pour le moment.</p>
        <a href="{{ route('dashboard.create-ad') }}" class="text-emerald-600 hover:underline mt-2 inline-block">Publier une annonce</a>
    </div>
    @endforelse
</div>
@endsection