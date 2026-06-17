@extends('layouts.user')
@section('title', 'Historique des transactions - LinPay')
@section('content')
<div class="p-6 md:p-8 max-w-7xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">📜 Historique complet</h1>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr><th class="px-4 py-2 text-left">Date</th><th class="px-4 py-2 text-left">Type</th><th class="px-4 py-2 text-left">Montant</th><th class="px-4 py-2 text-left">Devise</th><th class="px-4 py-2 text-left">Statut</th></tr></thead>
                <tbody>
                    @foreach($transactions as $tx)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $tx->created_at ? $tx->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td class="px-4 py-2">{{ ucfirst($tx->type) }}</td>
                        <td class="px-4 py-2">{{ number_format($tx->montant, 2) }}</td>
                        <td class="px-4 py-2">{{ $tx->devise }}</td>
                        <td class="px-4 py-2"><span class="px-2 py-1 rounded-full text-xs {{ $tx->statut == 'REUSSIE' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $tx->statut }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection