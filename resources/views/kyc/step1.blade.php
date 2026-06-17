@extends('layouts.user')

@section('title', 'KYC - Étape 1 : Identité')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-emerald-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Étape 1 / 4 - Votre identité</h1>
                    <p class="text-gray-600">Veuillez renseigner vos informations personnelles exactes.</p>
                </div>
                <div class="text-sm text-emerald-600 font-semibold bg-white px-3 py-1 rounded-full shadow-sm">En attente de validation</div>
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

        <form action="{{ route('kyc.post.step1') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->identity_first_name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->identity_last_name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date de naissance *</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->identity_birth_date?->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    @error('birth_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lieu de naissance *</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $user->identity_birth_place) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    @error('birth_place') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nationalité *</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $user->identity_nationality) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    @error('nationality') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl hover:bg-emerald-700 transition font-semibold">Soumettre & continuer</button>
            </div>
        </form>
    </div>
</div>
@endsection