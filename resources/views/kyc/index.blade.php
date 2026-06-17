@extends('layouts.user')

@section('title', 'Vérification KYC - LinPay')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-emerald-50">
            <h1 class="text-2xl font-bold text-gray-800">Vérification d'identité (KYC)</h1>
            <p class="text-gray-600">Pour débloquer toutes les fonctionnalités, veuillez vérifier votre identité.</p>
        </div>

        @if(session('success'))
            <div class="m-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($kycDocuments)
            <div class="p-6">
                <div class="mb-4">
                    <h2 class="font-semibold text-lg">Statut actuel :</h2>
                    <div class="mt-2">
                        @if($kycDocuments->statut == 'VALIDE')
                            <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">✅ Vérifié</span>
                        @elseif($kycDocuments->statut == 'REJETE')
                            <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-700">❌ Rejeté</span>
                            @if($kycDocuments->motif_rejet)
                                <p class="text-red-600 mt-2">Motif : {{ $kycDocuments->motif_rejet }}</p>
                            @endif
                        @else
                            <span class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-700">⏳ En attente</span>
                        @endif
                    </div>
                </div>

                @if($kycDocuments->statut == 'VALIDE')
                    <div class="bg-emerald-50 p-4 rounded-xl">
                        <p class="text-emerald-700">Votre identité a été vérifiée. Vous bénéficiez de toutes les fonctionnalités.</p>
                    </div>
                @elseif($kycDocuments->statut == 'EN_ATTENTE')
                    <div class="bg-yellow-50 p-4 rounded-xl">
                        <p class="text-yellow-700">Votre demande est en cours d'examen. Nous vous tiendrons informé.</p>
                    </div>
                @else
                    <div class="mt-6">
                        <a href="{{ route('kyc.submit') }}" class="bg-emerald-600 text-white px-5 py-2 rounded-xl hover:bg-emerald-700">Soumettre une nouvelle demande</a>
                    </div>
                @endif
            </div>
        @else
            <form action="{{ route('kyc.submit') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="type_document" class="block text-sm font-semibold text-gray-700 mb-2">Type de document *</label>
                    <select name="type_document" id="type_document" required class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Sélectionnez</option>
                        <option value="CNI">Carte nationale d'identité (CNI)</option>
                        <option value="PASSEPORT">Passeport</option>
                        <option value="PERMIS_SEJOUR">Permis de séjour</option>
                        <option value="PERMIS_CONDUIRE">Permis de conduire</option>
                    </select>
                </div>

                <div>
                    <label for="numero_document" class="block text-sm font-semibold text-gray-700 mb-2">Numéro du document *</label>
                    <input type="text" name="numero_document" id="numero_document" required class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Photo recto *</label>
                    <input type="file" name="image_recto" accept="image/*" required class="w-full px-4 py-2 rounded-xl border border-gray-300">
                    <p class="text-xs text-gray-500 mt-1">Format JPG ou PNG, max 2 Mo</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Photo verso (optionnel)</label>
                    <input type="file" name="image_verso" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Selfie avec le document (optionnel)</label>
                    <input type="file" name="image_selfie" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-300">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700 transition">Envoyer ma demande</button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection