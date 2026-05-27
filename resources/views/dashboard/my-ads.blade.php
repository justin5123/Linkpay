@extends('dashboard-layout')

@section('title', 'Mes annonces')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mes annonces</h1>
        <a href="{{ route('dashboard.create-ad') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700">+ Nouvelle annonce</a>
    </div>

    @if($annonces->count())
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">Type</th>
                        <th class="text-left py-3">Montant</th>
                        <th class="text-left py-3">De</th>
                        <th class="text-left py-3">Vers</th>
                        <th class="text-left py-3">Pays dest.</th>
                        <th class="text-left py-3">Statut</th>
                        <th class="text-left py-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($annonces as $a)
                    <tr class="border-b">
                        <td class="py-3">{{ $a->type === 'envoi' ? '📤 Envoi' : '📥 Réception' }}</td>
                        <td class="py-3">{{ number_format($a->montant, 2) }} {{ $a->devise_source }}</td>
                        <td class="py-3">{{ $a->devise_source }}</td>
                        <td class="py-3">{{ $a->devise_destination ?? '—' }}</td>
                        <td class="py-3">{{ $a->pays_destination }}</td>
                        <td class="py-3"><span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">{{ $a->statut }}</span></td>
                        <td class="py-3">{{ $a->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 text-gray-400">
            <p>Aucune annonce pour le moment.</p>
            <a href="{{ route('dashboard.create-ad') }}" class="text-emerald-600 hover:underline mt-2 inline-block">Publier une annonce</a>
        </div>
    @endif
</div>
@endsection