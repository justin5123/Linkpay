@extends('dashboard-layout')

@section('title', 'Mes comptes bancaires')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Comptes bancaires</h1>
        <a href="{{ route('dashboard.comptes.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700">+ Ajouter un compte</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    @if($comptes->count())
        <div class="space-y-4">
            @foreach($comptes as $compte)
            <div class="border rounded-xl p-4 flex justify-between items-start">
                <div>
                    <p class="font-bold">{{ $compte->nom_banque }}</p>
                    <p class="text-sm text-gray-600">Titulaire : {{ $compte->titulaire }}</p>
                    <p class="text-sm text-gray-600">Pays : {{ $compte->pays }}</p>
                    <p class="text-sm text-gray-600">Numéro de compte : {{ $compte->numero_compte }}</p>
                    @if($compte->code_swift)<p class="text-sm text-gray-600">SWIFT/BIC : {{ $compte->code_swift }}</p>@endif
                    <p class="text-xs text-gray-400 mt-1">Ajouté le {{ $compte->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    @if($compte->est_par_defaut)
                        <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full text-xs">Compte par défaut</span>
                    @endif
                    <div class="mt-2 space-x-2">
                        <a href="{{ route('dashboard.comptes.edit', $compte) }}" class="text-emerald-600 hover:underline text-sm">Modifier</a>
                        <form action="{{ route('dashboard.comptes.destroy', $compte) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Supprimer ce compte ?')">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-400">Vous n’avez encore ajouté aucun compte bancaire.</p>
    @endif
</div>
@endsection