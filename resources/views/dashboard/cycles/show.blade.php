@extends('dashboard-layout')

@section('title', 'Détails du cycle de compensation')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Cycle #{{ $cycle->id }}</h1>
    <p class="text-gray-600 mb-6">Statut du cycle : <strong>{{ ucfirst($cycle->statut) }}</strong></p>

    @foreach($cycle->appariements as $appariement)
        <div class="border rounded-xl p-4 mb-6">
            <h2 class="text-lg font-semibold mb-2">Appariement #{{ $appariement->id }}</h2>
            <p>Ordre associé : #{{ $appariement->ordre_id }} - Montant apparié : {{ number_format($appariement->montant_apparie, 2) }} CFA (exemple)</p>
            <p>Statut appariement : {{ ucfirst($appariement->statut) }}</p>

            <h3 class="font-semibold mt-4 mb-2">Transactions associées :</h3>
            @foreach($appariement->transactions as $tx)
                <div class="bg-gray-50 p-3 rounded-lg mb-3">
                    <p><strong>Utilisateur :</strong> {{ $tx->utilisateur->nom }} ({{ $tx->utilisateur->email }})</p>
                    <p><strong>Type :</strong> {{ $tx->type }}</p>
                    <p><strong>Statut :</strong> {{ ucfirst($tx->statut) }}</p>

                    @if($tx->statut == 'en_attente_preuve' && $tx->utilisateur_id == auth()->id())
                        <form action="{{ route('dashboard.upload-preuve', $tx) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <div class="flex items-center gap-3">
                                <input type="file" name="preuve" required class="border rounded p-1">
                                <button type="submit" class="bg-emerald-600 text-white px-4 py-1 rounded">Envoyer la preuve</button>
                            </div>
                        </form>
                    @elseif($tx->statut == 'preuve_fournie' && $tx->utilisateur_id != auth()->id())
                        <form action="{{ route('dashboard.confirm-transaction', $tx) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Confirmer la transaction</button>
                        </form>
                    @elseif($tx->statut == 'confirmee')
                        <p class="text-green-600 mt-2">✓ Confirmée le {{ $tx->confirme_le }}</p>
                    @endif

                    @if($tx->chemin_preuve)
                        <p class="mt-2"><a href="{{ Storage::url($tx->chemin_preuve) }}" target="_blank" class="text-emerald-600 underline">Voir la preuve</a></p>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="mt-6 text-right">
        <a href="{{ route('dashboard.my-ads') }}" class="btn btn-secondary">Retour à mes annonces</a>
    </div>
</div>
@endsection