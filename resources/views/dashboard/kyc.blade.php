@extends('dashboard-layout')

@section('title', 'Vérification KYC')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Vérification d'identité (KYC)</h1>
    <p class="text-gray-600 mb-6">Pour augmenter vos limites de transaction, veuillez fournir les documents suivants :</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4">{{ session('error') }}</div>
    @endif

    <!-- Formulaire d'upload -->
    <div class="bg-gray-50 p-5 rounded-2xl mb-8">
        <h2 class="text-xl font-semibold mb-4">Ajouter un document</h2>
        <form action="{{ route('dashboard.kyc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                    <select name="type_document" class="w-full border rounded-xl p-2">
                        <option value="carte_identite">Carte d'identité nationale</option>
                        <option value="passeport">Passeport</option>
                        <option value="justificatif_domicile">Justificatif de domicile</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fichier (JPEG, PNG, PDF, max 5 Mo)</label>
                    <input type="file" name="fichier" class="w-full border rounded-xl p-2" required>
                </div>
            </div>
            <button class="mt-4 bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700">Envoyer</button>
        </form>
    </div>

    <!-- Liste des documents déjà envoyés -->
    <h2 class="text-xl font-semibold mb-3">Mes documents</h2>
    @if($documents->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border rounded-xl">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Fichier</th>
                        <th class="p-3 text-left">Statut</th>
                        <th class="p-3 text-left">Date d'envoi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr class="border-t">
                        <td class="p-3">
                            @if($doc->type_document == 'carte_identite') Carte d'identité
                            @elseif($doc->type_document == 'passeport') Passeport
                            @else Justificatif de domicile @endif
                        </td>
                        <td class="p-3">
                            <a href="{{ Storage::url($doc->chemin_fichier) }}" target="_blank" class="text-emerald-600 hover:underline">Voir le fichier</a>
                        </td>
                        <td class="p-3">
                            @if($doc->statut == 'en_attente')
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">En attente</span>
                            @elseif($doc->statut == 'verifie')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Vérifié</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Rejeté</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-400">Aucun document envoyé pour le moment.</p>
    @endif

    <div class="mt-6 text-sm text-gray-500">
        <p>📌 Une fois vos documents vérifiés, votre statut KYC passera à "vérifié" et vous pourrez effectuer des transactions sans limitation.</p>
    </div>
</div>
@endsection