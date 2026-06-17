@extends('layouts.user')

@section('title', 'Paramètres - Préférences')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">⚙️ Paramètres</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar (identique) -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <ul class="space-y-1">
                    <li><a href="{{ route('settings.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">👤 Profil</a></li>
                    <li><a href="{{ route('settings.security') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">🔒 Sécurité</a></li>
                    <li><a href="{{ route('settings.preferences') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700">⚙️ Préférences</a></li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="md:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">⚙️ Mes préférences</h2>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
                @endif

                <form action="{{ route('settings.preferences.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Devise préférée</label>
                            <select name="devise" class="w-full px-4 py-2 rounded-xl border border-gray-200">
                                <option value="XAF" {{ old('devise', $user->devise) == 'XAF' ? 'selected' : '' }}>XAF - Franc CFA</option>
                                <option value="EUR" {{ old('devise', $user->devise) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="USD" {{ old('devise', $user->devise) == 'USD' ? 'selected' : '' }}>USD - Dollar américain</option>
                                <option value="GBP" {{ old('devise', $user->devise) == 'GBP' ? 'selected' : '' }}>GBP - Livre sterling</option>
                            </select>
                            @error('devise') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notifications par email</label>
                            <div class="flex items-center gap-3 mt-1">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="notifications_email" value="1" checked class="text-emerald-600">
                                    <span>Oui</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="notifications_email" value="0" class="text-emerald-600">
                                    <span>Non</span>
                                </label>
                            </div>
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