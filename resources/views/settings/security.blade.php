@extends('layouts.user')

@section('title', 'Paramètres - Sécurité')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">⚙️ Paramètres</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar (identique) -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <ul class="space-y-1">
                    <li><a href="{{ route('settings.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">👤 Profil</a></li>
                    <li><a href="{{ route('settings.security') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700">🔒 Sécurité</a></li>
                    <li><a href="{{ route('settings.preferences') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">⚙️ Préférences</a></li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="md:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">🔒 Changer mon mot de passe</h2>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
                @endif

                <form action="{{ route('settings.security.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel *</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe *</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700 transition font-semibold">Changer le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection