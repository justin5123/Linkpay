
<div class="space-y-4">
    <h3 class="text-lg font-medium">Conversation</h3>
    <div class="border rounded-lg p-4 bg-gray-50 max-h-96 overflow-y-auto" id="messages-container">
        @foreach($ticket->messages as $msg)
            <div class="flex {{ $msg->expediteur_id == auth()->id() ? 'justify-end' : 'justify-start' }} mb-3">
                <div class="max-w-md rounded-lg px-4 py-2 {{ $msg->expediteur_id == auth()->id() ? 'bg-emerald-100 text-gray-800' : 'bg-white text-gray-800 border' }}">
                    <p class="text-xs font-semibold">
                        {{ $msg->expediteur->prenom ?? 'Admin' }}
                    </p>
                    <p class="text-sm">{{ $msg->message }}</p>
                    <p class="text-xs text-gray-400 text-right mt-1">
                        {{ $msg->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendReply">
        <div class="mt-4">
            <label class="block text-sm font-medium mb-1">Votre réponse</label>
            <textarea wire:model="replyMessage" rows="3" class="w-full border rounded p-2" required></textarea>
        </div>
        <div class="mt-2 flex justify-end">
            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded">
                Envoyer la réponse
            </button>
        </div>
    </form>
</div>