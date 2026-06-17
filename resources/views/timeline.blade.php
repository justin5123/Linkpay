@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Suggestions de personnes à suivre -->
        @if($suggestions->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap items-center gap-4">
            <span class="text-sm font-semibold text-gray-600">Suggestions :</span>
            @foreach($suggestions as $suggestion)
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1 rounded-full">
                    <span class="text-sm">{{ $suggestion->prenom }}</span>
                    <form action="{{ route('user.follow', $suggestion) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-emerald-600 hover:underline">Suivre</button>
                    </form>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Formulaire de publication -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Publier quelque chose</h2>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea name="contenu" rows="3" class="w-full border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500" placeholder="Quoi de neuf ?"></textarea>
                <div class="flex items-center justify-between mt-4">
                    <input type="file" name="media" accept="image/*" class="text-sm text-gray-500">
                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-emerald-700 transition">Publier</button>
                </div>
            </form>
        </div>

        <!-- Fil d'actualité -->
        @forelse($posts as $post)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('user.profile', $post->user) }}" class="flex items-center gap-3 hover:underline">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($post->user->prenom ?? $post->user->nom, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $post->user->prenom }} {{ $post->user->nom }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    @if(auth()->id() === $post->users_id)
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Supprimer cette publication ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">🗑️</button>
                        </form>
                    @endif
                </div>

                <p class="text-gray-700 mb-3">{{ $post->contenu }}</p>
                @if($post->media)
                    <img src="{{ asset('storage/' . $post->media) }}" class="rounded-xl max-h-96 w-full object-cover mb-3">
                @endif

                <div class="flex items-center gap-6 pt-3 border-t border-gray-100">
                    <!-- Like -->
                    <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1 text-gray-500 hover:text-red-500 transition">
                            <span class="text-xl">❤️</span>
                            <span>{{ $post->likes_count }}</span>
                        </button>
                    </form>
                    <!-- Commentaire (toggle) -->
                    <button onclick="toggleComments({{ $post->id }})" class="flex items-center gap-1 text-gray-500 hover:text-emerald-500 transition">
                        <span class="text-xl">💬</span>
                        <span>{{ $post->comments_count }}</span>
                    </button>
                    <!-- Partage -->
                    <form action="{{ route('posts.share', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1 text-gray-500 hover:text-emerald-500 transition">
                            <span class="text-xl">🔁</span>
                            <span>{{ $post->shares_count }}</span>
                        </button>
                    </form>
                </div>

                <!-- Zone commentaires (cachée par défaut) -->
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
                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="contenu" class="flex-1 border-gray-200 rounded-lg text-sm" placeholder="Écrire un commentaire...">
                            <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                Aucune publication pour le moment. Suivez d'autres utilisateurs ou publiez quelque chose !
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
</div>

<script>
    function toggleComments(postId) {
        const div = document.getElementById(`comments-${postId}`);
        if (div) div.classList.toggle('hidden');
    }
</script>
@endsection