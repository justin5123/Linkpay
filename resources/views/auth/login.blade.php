<x-guest-layout>

    <div class="text-center mb-6">
        <div class="form-title">
            Connexion
        </div>

        <div class="form-subtitle">
            Connectez-vous à votre compte LinPay
        </div>
    </div>

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <form method="POST"
          action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email"
                class="block text-sm font-medium text-gray-700 mb-1">
                Adresse email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="input-linpay"
                placeholder="jean@exemple.com">

            @error('email')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password"
                class="block text-sm font-medium text-gray-700 mb-1">
                Mot de passe
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="input-linpay"
                placeholder="••••••••">

            @error('password')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember -->
        <div class="flex items-center mb-4">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">

            <label for="remember_me"
                class="ml-2 text-sm text-gray-600">
                Se souvenir de moi
            </label>
        </div>

        <!-- Forgot password -->
        <div class="text-right mb-6">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-emerald-600 hover:underline">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit"
                class="btn-linpay">
            Se connecter
        </button>

        <!-- Register -->
        <div class="mt-6 text-center text-sm text-gray-600">
            Pas encore de compte ?

            <a href="{{ route('register') }}"
               class="text-emerald-600 font-medium hover:underline">
                Créer un compte
            </a>
        </div>

    </form>

</x-guest-layout>