<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Infos ticket --}}
        <x-filament::card>
            <div>
                <h2 class="text-xl font-bold">{{ $ticket->sujet }}</h2>
                <p class="text-sm text-gray-500">Réf: {{ $ticket->reference }}</p>
                <p>Statut: <strong>{{ $ticket->statut }}</strong> | Priorité: <strong>{{ $ticket->priorite }}</strong></p>
                <p class="text-sm text-gray-400">Créé le {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="mt-3">
                <p class="text-gray-700">{{ $ticket->description }}</p>
            </div>
        </x-filament::card>

        {{-- Conversation --}}
        <x-filament::card>
            <h3 class="text-lg font-medium mb-4">Conversation</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto p-2 bg-gray-50 rounded">
                @forelse($ticket->messages as $msg)
                    <div class="flex {{ $msg->expediteur_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-md rounded-lg px-4 py-2 {{ $msg->expediteur_id == auth()->id() ? 'bg-emerald-100' : 'bg-white border' }}">
                            <p class="text-xs font-semibold">{{ $msg->expediteur->prenom ?? 'Admin' }}</p>
                            <p>{{ $msg->message }}</p>
                            <p class="text-xs text-gray-400 text-right mt-1">{{ $msg->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Aucun message pour l'instant.</p>
                @endforelse
            </div>

            {{-- Formulaire avec champ caché pour l'ID du ticket --}}
            <form method="POST" action="{{ route('support.conversation.send') }}" class="mt-4">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                <div>
                    <label class="block text-sm font-medium mb-1">Votre réponse</label>
                    <textarea name="reply" rows="3" class="w-full border rounded p-2" required></textarea>
                </div>
                <div class="mt-2 flex justify-end">
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded">
                        Envoyer la réponse
                    </button>
                </div>
            </form>
        </x-filament::card>
    </div>
</x-filament-panels::page>