@extends('layouts.user')

@section('title', 'Ticket #'.$ticket->reference)

@section('content')
<div class="p-6 md:p-8 max-w-5xl mx-auto">
    <!-- ... (en-tête et infos ticket, inchangés) ... -->

    <!-- CONTENEUR DES MESSAGES -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Conversation</h2>
        <div id="messagesContainer" class="space-y-4 max-h-96 overflow-y-auto">
            @include('support.partials.messages')
        </div>
    </div>

    <!-- Formulaire d'envoi (si ticket non fermé) -->
    @if($ticket->statut != 'FERME')
    <div class="bg-white rounded-2xl shadow p-6">
        <form id="replyForm" action="{{ route('support.message', $ticket->id) }}" method="POST">
            @csrf
            <textarea name="message" id="message" rows="4" required class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Votre message..."></textarea>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-xl hover:bg-emerald-700">Envoyer</button>
            </div>
        </form>
    </div>
    @else
        <div class="bg-gray-100 rounded-2xl p-4 text-center text-gray-500">Ce ticket est fermé.</div>
    @endif
</div>

<script>
    // Fonction pour recharger les messages
    function refreshMessages() {
        fetch('{{ route("support.messages.json", $ticket->id) }}')
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(data => {
                // data.messages est un tableau d'objets
                const container = document.getElementById('messagesContainer');
                if (!container) return;

                // Générer le HTML à partir des données JSON (évite de recharger la vue partielle)
                let html = '';
                data.messages.forEach(msg => {
                    const align = msg.is_mine ? 'justify-end' : 'justify-start';
                    const bg = msg.is_mine ? 'bg-emerald-500 text-white' : 'bg-gray-100';
                    const metaColor = msg.is_mine ? 'text-emerald-100' : 'text-gray-500';
                    html += `
                        <div class="flex ${align} message-item" data-id="${msg.id}">
                            <div class="max-w-[70%] rounded-lg px-4 py-2 ${bg}">
                                <div class="text-xs ${metaColor}">
                                    ${msg.user_name} - ${msg.created_at}
                                </div>
                                <p class="mt-1">${escapeHtml(msg.message)}</p>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
                // Scroller en bas
                container.scrollTop = container.scrollHeight;
            })
            .catch(error => console.error('Polling error:', error));
    }

    // Échapper le HTML pour éviter XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Lancer le polling toutes les 3 secondes
    let interval = setInterval(refreshMessages, 3000);

    // Envoi du formulaire sans rechargement (AJAX)
    const form = document.getElementById('replyForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(() => {
                document.getElementById('message').value = '';
                refreshMessages(); // rafraîchir immédiatement
            })
            .catch(console.error);
        });
    }

    // Démarrer le polling immédiatement
    refreshMessages();
</script>
@endsection