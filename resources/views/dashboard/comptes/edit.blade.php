@extends('dashboard-layout')

@section('title', 'Modifier le compte bancaire')

@section('content')
<div class="bg-white rounded-2xl shadow p-6 max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier le compte bancaire</h1>

    <form action="{{ route('dashboard.comptes.update', $compte) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium">Pays (ISO 2 lettres)</label>
            <input type="text" name="pays" value="{{ old('pays', $compte->pays) }}" class="w-full border rounded-xl p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Nom de la banque</label>
            <input type="text" name="nom_banque" value="{{ old('nom_banque', $compte->nom_banque) }}" class="w-full border rounded-xl p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Titulaire du compte</label>
            <input type="text" name="titulaire" value="{{ old('titulaire', $compte->titulaire) }}" class="w-full border rounded-xl p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Numéro de compte</label>
            <input type="text" name="numero_compte" value="{{ old('numero_compte', $compte->numero_compte) }}" class="w-full border rounded-xl p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Code SWIFT/BIC (optionnel)</label>
            <input type="text" name="code_swift" value="{{ old('code_swift', $compte->code_swift) }}" class="w-full border rounded-xl p-2">
        </div>
        <div class="mb-6 flex items-center">
            <input type="checkbox" name="est_par_defaut" value="1" id="defaut" {{ $compte->est_par_defaut ? 'checked' : '' }} class="mr-2">
            <label for="defaut" class="text-sm">Définir comme compte par défaut</label>
        </div>

        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700">Mettre à jour</button>
        <a href="{{ route('dashboard.comptes.index') }}" class="ml-2 text-gray-500 hover:underline">Annuler</a>
    </form>
</div>
@endsection