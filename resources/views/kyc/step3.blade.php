@extends('layouts.user')

@section('title', 'KYC - Étape 3 : Pièce d’identité')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-emerald-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Étape 3 / 4 - Pièce d’identité</h1>
                    <p class="text-gray-600">Veuillez télécharger une photo de votre pièce d’identité officielle (recto/verso).</p>
                </div>
                <div class="text-sm text-emerald-600 font-semibold bg-white px-3 py-1 rounded-full shadow-sm">Étapes 1 et 2 validées ✓</div>
            </div>
        </div>

        @if(session('error'))
            <div class="m-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">{{ session('error') }}</div>
        @endif

        @if(session('rejection_reason'))
            <div class="m-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700 font-semibold">Votre demande a été rejetée :</p>
                <p class="text-red-600">{{ session('rejection_reason') }}</p>
                <p class="text-sm text-red-500 mt-2">Veuillez corriger les informations ci-dessous et soumettre à nouveau.</p>
            </div>
        @endif

        <form action="{{ route('kyc.post.step3') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type de document *</label>
                    <select name="document_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200">
                        <option value="">Sélectionnez</option>
                        <option value="CNI" {{ old('document_type') == 'CNI' ? 'selected' : '' }}>Carte nationale d’identité (CNI)</option>
                        <option value="PASSEPORT" {{ old('document_type') == 'PASSEPORT' ? 'selected' : '' }}>Passeport</option>
                        <option value="PERMIS_CONDUIRE" {{ old('document_type') == 'PERMIS_CONDUIRE' ? 'selected' : '' }}>Permis de conduire</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Numéro du document *</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Photo recto *</label>
                    <input type="file" name="front_image" accept="image/*" required class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Photo verso (optionnel)</label>
                    <input type="file" name="back_image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
            </div>

            <div class="flex justify-between pt-4">
                <a href="{{ route('kyc.step', 2) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Retour</a>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl hover:bg-emerald-700 transition font-semibold">Soumettre & continuer</button>
            </div>
        </form>
    </div>
</div>
@endsection