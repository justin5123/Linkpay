@extends('layouts.user')

@section('title', 'Paramètres - Profil')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">⚙️ Paramètres</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar des paramètres -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('settings.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('settings.profile') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>👤</span> Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.security') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('settings.security') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>🔒</span> Sécurité
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.preferences') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('settings.preferences') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>⚙️</span> Préférences
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="md:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">👤 Modifier mon profil</h2>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('settings.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200">
                            @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}"
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200">
                            @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pays *</label>
                            <select name="pays" required class="w-full px-4 py-2 rounded-xl border border-gray-200">
                                <option value="Cameroun" {{ old('pays', $user->pays) == 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                                <option value="France" {{ old('pays', $user->pays) == 'France' ? 'selected' : '' }}>France</option>
                                <option value="USA" {{ old('pays', $user->pays) == 'USA' ? 'selected' : '' }}>États-Unis</option>
                                <option value="Canada" {{ old('pays', $user->pays) == 'Canada' ? 'selected' : '' }}>Canada</option>
                            </select>
                            @error('pays') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700 transition font-semibold">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection