@extends('layouts.user')

@section('title', 'Abonnements')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">Abonnements de {{ $user->prenom }}</h1>
    @forelse($following as $followed)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-2 flex justify-between items-center">
            <a href="{{ route('social.profile', $followed) }}" class="font-semibold text-emerald-700">
                {{ $followed->prenom }} {{ $followed->nom }}
            </a>
        </div>
    @empty
        <p class="text-gray-500">Aucun abonnement.</p>
    @endforelse
    {{ $following->links() }}
</div>
@endsection