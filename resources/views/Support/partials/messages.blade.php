@foreach($ticket->messages as $msg)
    <div class="flex {{ $msg->expediteur_id == auth()->id() ? 'justify-end' : 'justify-start' }} message-item" data-id="{{ $msg->id }}">
        <div class="max-w-[70%] rounded-lg px-4 py-2 {{ $msg->expediteur_id == auth()->id() ? 'bg-emerald-500 text-white' : 'bg-gray-100' }}">
            <div class="text-xs {{ $msg->expediteur_id == auth()->id() ? 'text-emerald-100' : 'text-gray-500' }}">
                {{ $msg->expediteur->prenom }} {{ $msg->expediteur->nom }} - {{ $msg->created_at->format('H:i') }}
            </div>
            <p class="mt-1">{{ $msg->message }}</p>
        </div>
    </div>
@endforeach