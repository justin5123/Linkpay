@extends('layouts.user')

@section('title', 'Mes tickets - Support LinPay')

@section('content')
<div class="p-6 md:p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Support client</h1>
            <p class="text-gray-500">Consultez et gérez vos demandes d'assistance</p>
        </div>
        <a href="{{ route('support.create') }}" class="bg-emerald-600 text-white px-5 py-2 rounded-xl shadow hover:bg-emerald-700 transition">
            + Nouveau ticket
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if($tickets->count())
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sujet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priorité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $ticket->reference }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $ticket->sujet }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $ticket->categorie }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                @if($ticket->priorite == 'URGENTE') bg-red-100 text-red-700
                                @elseif($ticket->priorite == 'ELEVEE') bg-orange-100 text-orange-700
                                @elseif($ticket->priorite == 'NORMALE') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $ticket->priorite }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($ticket->statut == 'OUVERT') bg-green-100 text-green-700
                                @elseif($ticket->statut == 'EN_COURS') bg-yellow-100 text-yellow-700
                                @elseif($ticket->statut == 'RESOLU') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $ticket->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('support.show', $ticket->id) }}" class="text-emerald-600 hover:underline text-sm">Voir</a>
                            @if($ticket->statut != 'FERME')
                                <form action="{{ route('support.close', $ticket->id) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-500 hover:underline text-sm" onclick="return confirm('Fermer ce ticket ?')">Fermer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow p-8 text-center">
            <p class="text-gray-500">Aucun ticket pour le moment.</p>
            <a href="{{ route('support.create') }}" class="inline-block mt-4 text-emerald-600 hover:underline">Créer votre premier ticket</a>
        </div>
    @endif
</div>
@endsection