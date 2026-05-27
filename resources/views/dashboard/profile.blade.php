@extends('dashboard-layout')

@section('title', 'Mon profil')

@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Mon profil</h1>
    <form method="POST" action="#">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" value="{{ Auth::user()->nom }}" class="w-full border rounded-xl p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full border rounded-xl p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="tel" name="telephone" value="{{ Auth::user()->telephone }}" class="w-full border rounded-xl p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pays</label>
                <input type="text" name="pays" value="{{ Auth::user()->pays }}" class="w-full border rounded-xl p-2">
            </div>
            <button class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection