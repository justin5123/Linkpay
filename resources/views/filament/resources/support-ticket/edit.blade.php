<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6">
        {{-- Informations du ticket --}}
        <x-filament::card>
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold">{{ $ticket->sujet }}</h2>
                    <p class="text-sm text-gray-500">Réf: {{ $ticket->reference }}</p>
                    <p class="text-sm">Statut: <span class="font-semibold">{{ $ticket->statut }}</span></p>
                    <p class="text-sm">Priorité: <span class="font-semibold">{{ $ticket->priorite }}</span></p>
                    <p class="text-sm text-gray-400">Créé le {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    {{-- Actions supplémentaires (changer statut, etc.) --}}
                </div>
            </div>
            <div class="mt-3">
                <p class="text-gray-700">{{ $ticket->description }}</p>
            </div>
        </x-filament::card>

        {{-- Conversation --}}
        @livewire('filament.resources.support-ticket.partials.conversation', ['ticket' => $ticket])
    </div>
</x-filament-panels::page>