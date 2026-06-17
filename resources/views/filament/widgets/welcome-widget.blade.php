<x-filament::widget>
    <x-filament::card>
        <div class="text-center">
            <h2 class="text-xl font-bold">Bienvenue, {{ auth()->user()->name }} !</h2>
            <p class="text-gray-600">Voici le résumé des activités sur LinPay.</p>
        </div>
    </x-filament::card>
</x-filament::widget>