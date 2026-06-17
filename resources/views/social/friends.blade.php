@extends('layouts.user')

@section('title', 'Mes amis')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6">Mes amis</h1>

    <!-- Demandes reçues -->
    @if($pendingRequests->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <h2 class="font-semibold text-gray-700 mb-3">Demandes reçues</h2>
            @foreach($pendingRequests as $request)
                <div class="flex justify-between items-center py-2 border-b">
                    <span>{{ $request->sender->prenom }} {{ $request->sender->nom }}</span>
                    <div class="flex gap-2">
                        <form action="{{ route('friends.accept', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-emerald-600 text-white px-3 py-1 rounded-full text-sm">Accepter</button>
                        </form>
                        <form action="{{ route('friends.reject', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-gray-300 text-gray-700 px-3 py-1 rounded-full text-sm">Refuser</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Liste des amis -->
    @if($friends->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($friends as $friend)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex justify-between items-center">
                    <div>
                        <span class="font-semibold">{{ $friend->prenom }} {{ $friend->nom }}</span>
                        <p class="text-xs text-gray-400">{{ $friend->email }}</p>
                    </div>
                    <a href="{{ route('social.profile', $friend->id) }}" class="text-emerald-600 text-sm hover:underline">Profil</a>
                </div>
            @endforeach
        </div>
        {{ $friends->links() }}
    @else
        <p class="text-gray-500">Vous n’avez pas encore d’amis.</p>
    @endif

    <!-- Demande envoyées en attente -->
    @if($sentRequests->count())
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h2 class="font-semibold text-gray-700 mb-3">Demandes envoyées en attente</h2>
            @foreach($sentRequests as $request)
                <div class="py-2 border-b">
                    <span>{{ $request->receiver->prenom }} {{ $request->receiver->nom }}</span>
                    <span class="text-xs text-gray-400 ml-2">En attente...</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection