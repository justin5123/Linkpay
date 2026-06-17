@extends('layouts.user')

@section('title', 'KYC - Étape 2 : Justificatif de domicile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-emerald-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Étape 2 / 4 - Justificatif de domicile</h1>
                    <p class="text-gray-600">Nous avons besoin d’une preuve de votre adresse récente (facture, relevé bancaire, etc.).</p>
                </div>
                <div class="text-sm text-emerald-600 font-semibold bg-white px-3 py-1 rounded-full shadow-sm">Étape 1 validée ✓</div>
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

        <form action="{{ route('kyc.post.step2') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse *</label>
                    <input type="text" name="street" value="{{ old('street', $user->address_street) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200" placeholder="Numéro et rue">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ville *</label>
                    <input type="text" name="city" value="{{ old('city', $user->address_city) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Code postal *</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $user->address_postal_code) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pays *</label>
                    <input type="text" name="country" value="{{ old('country', $user->address_country) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Fichier justificatif * (PDF, JPG, PNG - max 5 Mo)</label>
                <input type="file" name="proof_of_address" accept=".pdf,.jpg,.jpeg,.png" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200">
                @error('proof_of_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Document de moins de 3 mois, comportant votre nom et adresse.</p>
            </div>

            <div class="flex justify-between pt-4">
                <a href="{{ route('kyc.step', 1) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Retour</a>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl hover:bg-emerald-700 transition font-semibold">Soumettre & continuer</button>
            </div>
        </form>
    </div>
</div>
@endsection