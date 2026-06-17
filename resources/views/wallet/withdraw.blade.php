@extends('layouts.user')

@section('title', 'Retrait - LinPay')

@section('content')
<div class="p-6 md:p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Retirer de l'argent</h1>
            <p class="text-emerald-100 text-sm">Transférez vos fonds vers un compte externe</p>
        </div>

        @if(session('success'))
            <div class="m-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="m-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('wallet.withdraw.post') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Montant ({{ Auth::user()->wallets->first()->devise ?? 'XAF' }}) *</label>
                <input type="number" name="amount" step="0.01" value="{{ old('amount') }}" class="w-full px-4 py-2 rounded-xl border @error('amount') border-red-500 @else border-gray-200 @enderror" required>
                @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Moyen de retrait *</label>
                <select name="payment_method_id" class="w-full px-4 py-2 rounded-xl border @error('payment_method_id') border-red-500 @else border-gray-200 @enderror" required>
                    <option value="">Sélectionnez</option>
                    @foreach(Auth::user()->moyensPaiements as $mp)
                        <option value="{{ $mp->id }}" {{ old('payment_method_id') == $mp->id ? 'selected' : '' }}>{{ $mp->type }} - {{ $mp->fournisseur }} ({{ $mp->identifiant_compte }})</option>
                    @endforeach
                </select>
                @error('payment_method_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ route('wallet.index') }}" class="px-6 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">Retirer</button>
            </div>
        </form>
    </div>
</div>
@endsection