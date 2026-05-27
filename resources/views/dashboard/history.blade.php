@extends('dashboard-layout')

@section('title', 'Historique des transactions')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Historique</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Date</th>
                    <th class="text-left py-2">Type</th>
                    <th class="text-left py-2">Montant</th>
                    <th class="text-left py-2">Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-400">Aucune transaction pour le moment</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection