<x-filament-panels::page>
    <div>
        <div class="space-y-6">
            <!-- Section ticket -->
            <x-filament::section>
                <x-slot name="heading">Ticket {{ $this->ticket->reference }}</x-slot>
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>Client :</strong> {{ $this->ticket->user->email }}</div>
                    <div><strong>Sujet :</strong> {{ $this->ticket->sujet }}</div>
                    <div><strong>Catégorie :</strong> {{ $this->ticket->categorie }}</div>
                    <div><strong>Priorité :</strong>
                        <span class="px-2 py-1 rounded text-xs font-semibold 
                            @if($this->ticket->priorite == 'URGENTE') bg-red-100 text-red-700
                            @elseif($this->ticket->priorite == 'ELEVEE') bg-orange-100 text-orange-700
                            @elseif($this->ticket->priorite == 'NORMALE') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $this->ticket->priorite }}
                        </span>
                    </div>
                    <div><strong>Statut :</strong>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($this->ticket->statut == 'OUVERT') bg-green-100 text-green-700
                            @elseif($this->ticket->statut == 'EN_COURS') bg-yellow-100 text-yellow-700
                            @elseif($this->ticket->statut == 'RESOLU') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $this->ticket->statut }}
                        </span>
                    </div>
                    <div><strong>Créé le :</strong> {{ $this->ticket->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mt-4">
                    <strong>Description :</strong>
                    <p class="mt-1 bg-gray-50 p-3 rounded">{{ $this->ticket->description }}</p>
                </div>
            </x-filament::section>

            <!-- Messages -->
            <x-filament::section>
                <x-slot name="heading">Conversation</x-slot>
                <div id="messagesContainer" class="space-y-4 max-h-96 overflow-y-auto">
                    @foreach($this->ticket->messages as $msg)
                        <div class="flex {{ $msg->expediteur_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] rounded-lg px-4 py-2 {{ $msg->expediteur_id == auth()->id() ? 'bg-primary-500 text-white' : 'bg-gray-100' }}">
                                <div class="text-xs {{ $msg->expediteur_id == auth()->id() ? 'text-primary-100' : 'text-gray-500' }}">
                                    {{ $msg->expediteur->email }} - {{ $msg->created_at->format('d/m/Y H:i') }}
                                </div>
                                <p class="mt-1">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>

            <!-- Formulaire -->
            <x-filament::section>
                <x-slot name="heading">Répondre</x-slot>
                <form wire:submit.prevent="sendMessage" class="space-y-4">
                    <textarea wire:model="newMessage" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" placeholder="Votre message..."></textarea>
                    @error('newMessage') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
                    <div class="flex justify-end gap-2">
                        @if($this->ticket->statut != 'RESOLU' && $this->ticket->statut != 'FERME')
                            <button type="button" wire:click="markAsResolved" class="filament-button-secondary">Marquer comme résolu</button>
                        @endif
                        @if($this->ticket->statut != 'FERME')
                            <button type="button" wire:click="closeTicket" class="filament-button-danger">Fermer le ticket</button>
                        @endif
                        <button type="submit" class="filament-button-primary">Envoyer</button>
                    </div>
                </form>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>

@push('scripts')
<script>
    (function() {
        let lastMessageId = {{ $this->ticket->messages->last()->id ?? 0 }};
        const ticketId = {{ $this->ticket->id }};

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function refreshMessages() {
            fetch('/support/' + ticketId + '/messages-json')
                .then(response => response.json())
                .then(data => {
                    let newMessages = data.messages.filter(m => m.id > lastMessageId);
                    if (newMessages.length > 0) {
                        lastMessageId = data.last_id;
                        const container = document.getElementById('messagesContainer');
                        if (container) {
                            let html = '';
                            data.messages.forEach(msg => {
                                const align = msg.is_mine ? 'justify-end' : 'justify-start';
                                const bg = msg.is_mine ? 'bg-primary-500 text-white' : 'bg-gray-100';
                                const metaColor = msg.is_mine ? 'text-primary-100' : 'text-gray-500';
                                html += `<div class="flex ${align}">
                                            <div class="max-w-[70%] rounded-lg px-4 py-2 ${bg}">
                                                <div class="text-xs ${metaColor}">${escapeHtml(msg.user_name)} - ${msg.created_at}</div>
                                                <p class="mt-1">${escapeHtml(msg.message)}</p>
                                            </div>
                                        </div>`;
                            });
                            container.innerHTML = html;
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                })
                .catch(error => console.error('Polling error:', error));
        }

        setInterval(refreshMessages, 3000);
        refreshMessages();
    })();
</script>
@endpush