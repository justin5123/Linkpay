@extends('layouts.user')

@section('title', 'Profil de ' . $user->prenom)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- En-tête du profil -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->prenom ?? $user->nom, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</h1>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <p class="text-sm text-gray-400">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(auth()->id() !== $user->id)
                    @php
                        $isFriend = Auth::user()->isFriendWith($user->id);
                        $pendingFromMe = Auth::user()->hasPendingFriendRequestTo($user->id);
                        $pendingFromHim = Auth::user()->hasPendingFriendRequestFrom($user->id);
                    @endphp
                    @if($isFriend)
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm">Amis</span>
                        <form action="{{ route('social.friend.unfriend', $user) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">Se désabonner</button>
                        </form>
                    @elseif($pendingFromMe)
                        <span class="text-gray-500 text-sm">Demande envoyée</span>
                        <form action="{{ route('social.friend.cancel', $user) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">Annuler</button>
                        </form>
                    @elseif($pendingFromHim)
                        <form action="{{ route('social.friend.accept', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-full text-sm">Accepter</button>
                        </form>
                        <form action="{{ route('social.friend.reject', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm">Refuser</button>
                        </form>
                    @else
                        <form action="{{ route('social.friend.request', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-full text-sm">Ajouter en ami</button>
                        </form>
                    @endif
                @endif
                <a href="{{ route('social.followers', $user) }}" class="text-sm text-gray-500 hover:underline">{{ $user->followers->count() }} abonnés</a>
                <a href="{{ route('social.following', $user) }}" class="text-sm text-gray-500 hover:underline">{{ $user->following->count() }} abonnements</a>
            </div>
        </div>
    </div>

    <!-- Publications de l'utilisateur -->
    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p>{{ $post->contenu }}</p>
                <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                Aucune publication.
            </div>
        @endforelse
        {{ $posts->links() }}
    </div>
</div>
@endsection