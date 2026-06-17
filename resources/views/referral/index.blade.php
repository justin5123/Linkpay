@extends('layouts.user')

@section('title', 'Mon parrainage')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Carte récapitulative -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <p class="text-gray-500">Code de parrainage</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $user->referral_code }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <p class="text-gray-500">Bonus total</p>
            <p class="text-2xl font-bold">{{ number_format($totalBonus, 2) }} XAF</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <p class="text-gray-500">Parrainages</p>
            <p class="text-2xl font-bold">{{ $totalReferrals }}</p>
        </div>
    </div>

    <!-- Lien de parrainage -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-semibold text-gray-800 mb-3">🔗 Partager votre lien</h3>
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="referralLink" value="{{ $referralLink }}" readonly
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 text-sm">
            <button onclick="copyLink()" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                📋 Copier
            </button>
        </div>
        <p class="text-xs text-gray-500 mt-2">Chaque ami qui s’inscrit avec ce lien vous rapporte un bonus !</p>
    </div>

    <!-- Liste des parrainages -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">👥 Vos parrainages</h3>
        @if($referrals->count())
            <ul class="divide-y divide-gray-100">
                @foreach($referrals as $ref)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $ref->prenom }} {{ $ref->nom }}</p>
                            <p class="text-xs text-gray-400">{{ $ref->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-emerald-600 font-semibold">+ {{ number_format($ref->referral_bonus, 2) }} XAF</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-sm">Aucun parrainage pour le moment. Partagez votre lien !</p>
        @endif
    </div>
</div>

<script>
    function copyLink() {
        const input = document.getElementById('referralLink');
        input.select();
        document.execCommand('copy');
        alert('Lien copié dans le presse-papiers !');
    }
</script>
@endsection