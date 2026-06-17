@extends('layouts.user')

@section('title', 'Mes notifications - LinPay')

@section('content')
<div class="p-6 md:p-8 max-w-7xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Mes notifications</h1>
                <p class="text-gray-500">Retrouvez toutes vos alertes et messages</p>
            </div>
            @if($notifications->count())
                <button id="markAllBtn" class="text-emerald-600 text-sm hover:underline">Tout marquer comme lu</button>
            @endif
        </div>

        @if($notifications->count())
            <ul class="divide-y divide-gray-100" id="notificationsList">
                @foreach($notifications as $notif)
                    <li class="p-4 hover:bg-gray-50 transition {{ !$notif->est_lu ? 'bg-emerald-50' : '' }}" data-id="{{ $notif->id }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-800">{{ $notif->titre }}</span>
                                    @if(!$notif->est_lu)
                                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Nouveau</span>
                                    @endif
                                </div>
                                <p class="text-gray-600 mt-1">{{ $notif->message }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $notif->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if(!$notif->est_lu)
                                <button class="mark-read-btn text-emerald-600 text-sm hover:underline" data-id="{{ $notif->id }}">Marquer comme lu</button>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="p-4 border-t border-gray-100">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-8 text-center text-gray-500">Aucune notification pour le moment.</div>
        @endif
    </div>
</div>

<script>
    // Fonction pour rafraîchir la page ou supprimer l'élément
    function markAsRead(id, buttonElement) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Option 1 : recharger la page pour refléter le changement
                location.reload();
                // Option 2 : mettre à jour visuellement (supprimer le badge et le bouton)
                // const li = buttonElement.closest('li');
                // li.classList.remove('bg-emerald-50');
                // buttonElement.remove();
                // const badge = li.querySelector('.bg-emerald-100');
                // if (badge) badge.remove();
            } else {
                alert('Erreur lors du marquage.');
            }
        })
        .catch(err => console.error('Erreur:', err));
    }

    // Marquer une notification comme lue
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            markAsRead(id, this);
        });
    });

    // Marquer toutes comme lues
    const markAllBtn = document.getElementById('markAllBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors du marquage.');
                }
            })
            .catch(err => console.error('Erreur:', err));
        });
    }
</script>
@endsection