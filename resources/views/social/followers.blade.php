@extends('layouts.user')

@section('title', 'Abonnés')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">Abonnés de {{ $user->prenom }}</h1>
    @forelse($followers as $follower)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-2 flex justify-between items-center">
            <a href="{{ route('social.profile', $follower) }}" class="font-semibold text-emerald-700">
                {{ $follower->prenom }} {{ $follower->nom }}
            </a>
        </div>
    @empty
        <p class="text-gray-500">Aucun abonné.</p>
    @endforelse
    {{ $followers->links() }}
</div>
@endsection