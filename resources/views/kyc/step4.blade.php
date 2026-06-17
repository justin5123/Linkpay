@extends('layouts.user')

@section('title', 'KYC - Étape 4 : Selfie et finalisation')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-emerald-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Étape 4 / 4 - Selfie et vérification finale</h1>
                    <p class="text-gray-600">Prenez un selfie avec votre pièce d’identité visible.</p>
                </div>
                <div class="text-sm text-emerald-600 font-semibold bg-white px-3 py-1 rounded-full shadow-sm">Étapes 1 à 3 validées ✓</div>
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

        <form action="{{ route('kyc.post.step4') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Selfie avec la pièce d’identité *</label>
                <input type="file" name="selfie" accept="image/*" required class="w-full px-4 py-3 rounded-xl border border-gray-200">
                <p class="text-xs text-gray-500 mt-1">Vous devez apparaître clairement, la pièce d’identité lisible à côté de votre visage.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Document supplémentaire (optionnel)</label>
                <input type="file" name="additional_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 rounded-xl border border-gray-200">
                <p class="text-xs text-gray-500 mt-1">Si vous avez un justificatif complémentaire (attestation, etc.)</p>
            </div>

            <div class="flex justify-between pt-4">
                <a href="{{ route('kyc.step', 3) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Retour</a>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl hover:bg-emerald-700 transition font-semibold">Soumettre ma demande KYC</button>
            </div>
        </form>
    </div>
</div>
@endsection