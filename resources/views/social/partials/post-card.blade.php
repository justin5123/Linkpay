<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('social.profile', $post->user) }}" class="flex items-center gap-3 hover:underline">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($post->user->prenom ?? $post->user->nom, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $post->user->prenom }} {{ $post->user->nom }}</p>
                <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
            </div>
        </a>
        @if(auth()->id() === $post->users_id)
            <form action="{{ route('social.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">🗑️</button>
            </form>
        @endif
    </div>

    <p class="text-gray-700 mb-3">{{ $post->contenu }}</p>
    @if($post->media)
        <img src="{{ asset('storage/' . $post->media) }}" class="rounded-xl max-h-96 w-full object-cover mb-3">
    @endif

    @if($post->type === 'ANNONCE' && $post->annonce)
        <div class="mt-3 p-4 bg-emerald-50 rounded-xl border border-emerald-200 flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-200 px-2 py-0.5 rounded-full">📢 Annonce</span>
                <p class="text-sm text-gray-600 mt-1">
                    <strong>{{ $post->annonce->type }}</strong> : {{ $post->annonce->montant_source }} {{ $post->annonce->devise_source }}
                    → {{ $post->annonce->montant_cible }} {{ $post->annonce->devise_cible }}
                </p>
            </div>
            <a href="{{ route('annonce.show', $post->annonce_id) }}" class="text-emerald-600 hover:underline text-sm font-medium">Voir →</a>
        </div>
    @endif

    <div class="flex items-center gap-6 pt-3 border-t border-gray-100 mt-3">
        <form action="{{ route('social.posts.like', $post) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-1 text-gray-500 hover:text-red-500 transition">
                <span class="text-xl">❤️</span>
                <span>{{ $post->likes->count() }}</span>
            </button>
        </form>

        <button onclick="toggleComments({{ $post->id }})" class="flex items-center gap-1 text-gray-500 hover:text-emerald-500 transition">
            <span class="text-xl">💬</span>
            <span>{{ $post->comments->count() }}</span>
        </button>

        <form action="{{ route('social.posts.share', $post) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-1 text-gray-500 hover:text-emerald-500 transition">
                <span class="text-xl">🔁</span>
                <span>{{ $post->shares->count() }}</span>
            </button>
        </form>
    </div>

    <div id="comments-{{ $post->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
        @foreach($post->comments as $comment)
            <div class="bg-gray-50 rounded-lg p-3 mb-2">
                <div class="flex justify-between">
                    <span class="font-medium text-sm">{{ $comment->user->prenom }}</span>
                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-gray-700 text-sm mt-1">{{ $comment->contenu }}</p>
            </div>
        @endforeach
        <form action="{{ route('social.posts.comment', $post) }}" method="POST" class="mt-3">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="contenu" class="flex-1 border-gray-200 rounded-lg text-sm" placeholder="Écrire un commentaire...">
                <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleComments(postId) {
        document.getElementById(`comments-${postId}`).classList.toggle('hidden');
    }
</script>