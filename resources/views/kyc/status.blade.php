@extends('layouts.user')

@section('title', 'Statut KYC - LinPay')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-emerald-50 border-b border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800">Statut de votre vérification KYC</h1>
            <p class="text-gray-600">Suivez l’avancement de votre demande.</p>
        </div>

        @if(session('success'))
            <div class="m-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="m-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="m-6 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded">{{ session('info') }}</div>
        @endif

        @if(in_array($user->kyc_status, ['STEP1_REJECTED', 'STEP2_REJECTED', 'STEP3_REJECTED', 'STEP4_REJECTED']) && $user->kyc_rejection_reason)
            <div class="mx-6 my-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700 font-semibold">Votre demande KYC a été rejetée :</p>
                <p class="text-red-600">{{ $user->kyc_rejection_reason }}</p>
                <p class="text-sm text-red-500 mt-2">Veuillez corriger les informations et soumettre à nouveau l’étape concernée.</p>
            </div>
        @endif

        <div class="p-6 space-y-6">
            @php
                $steps = [
                    1 => ['label' => 'Identité', 'description' => 'Informations personnelles'],
                    2 => ['label' => 'Justificatif de domicile', 'description' => 'Adresse et justificatif'],
                    3 => ['label' => 'Pièce d’identité', 'description' => 'CNI, passeport ou permis'],
                    4 => ['label' => 'Selfie et finalisation', 'description' => 'Photo avec la pièce'],
                ];
            @endphp

            @foreach($steps as $i => $step)
                @php
                    $status = $user->kyc_status;
                    $isValidated = ($status === "STEP{$i}_VALIDATED") || ($i == 4 && $status === 'COMPLETED');
                    $isPending = ($status === "STEP{$i}_PENDING");
                    $isRejected = ($status === "STEP{$i}_REJECTED");
                    $isPreviousValidated = ($i == 1) ? true : (str_contains($status, "STEP".($i-1)."_VALIDATED"));
                    $canStart = ($i == 1 && in_array($status, ['NOT_STARTED', 'STEP1_REJECTED'])) ||
                                ($i > 1 && $isPreviousValidated && !$isValidated && !$isPending);
                @endphp
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm
                        @if($isValidated) bg-green-500 text-white
                        @elseif($isPending) bg-yellow-500 text-white
                        @elseif($isRejected) bg-red-500 text-white
                        @else bg-gray-200 text-gray-500 @endif">
                        {{ $i }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $step['label'] }}</p>
                        <p class="text-sm text-gray-500">{{ $step['description'] }}</p>
                        @if($isPending)
                            <p class="text-xs text-yellow-600 mt-1">⏳ En attente de validation.</p>
                        @elseif($isRejected)
                            <p class="text-xs text-red-600 mt-1">❌ Rejeté. @if($user->kyc_rejection_reason) Motif : {{ $user->kyc_rejection_reason }} @endif</p>
                        @elseif($isValidated)
                            <p class="text-xs text-green-600 mt-1">✅ Validé.</p>
                        @elseif($canStart)
                            <a href="{{ route('kyc.step', $i) }}" class="inline-block mt-2 text-emerald-600 text-sm hover:underline">Commencer / Modifier</a>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($user->kyc_status === 'COMPLETED')
                <div class="bg-green-50 p-4 rounded-xl text-green-700 mt-4">
                    ✅ Félicitations ! Votre vérification KYC est complète.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection