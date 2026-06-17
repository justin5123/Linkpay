@extends('layouts.user')

@section('title', 'Réseau social')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- FEED CENTRAL (passe en premier sur mobile) -->
        <div class="flex-1 order-1 lg:order-none max-w-3xl mx-auto lg:mx-0 w-full">
            <!-- Bouton Publier (ouvre la modale) -->
            <button id="openPublishModal" class="w-full bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-left hover:bg-gray-50 transition mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                    </div>
                    <span class="text-gray-500 text-sm sm:text-base truncate">Publier quelque chose...</span>
                </div>
            </button>

            <!-- Fil d'actualité -->
            @forelse($posts as $post)
                @include('social.partials.post-card', ['post' => $post])
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                    Aucune publication pour le moment. Ajoutez des amis ou publiez quelque chose !
                </div>
            @endforelse

            <div class="mt-6">{{ $posts->links() }}</div>
        </div>

        <!-- SIDEBAR GAUCHE (passe en bas sur mobile) -->
        <div class="lg:w-72 xl:w-80 shrink-0 order-2 lg:order-none w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sticky top-24">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm sm:text-base">👥 Suggestions</h3>
                
                <!-- Barre de recherche -->
                <div class="relative mb-4">
                    <input type="text" id="searchUsers" placeholder="Rechercher un utilisateur..." 
                           class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">🔍</span>
                </div>
                <div id="searchResults" class="hidden"></div>

                <!-- Liste des suggestions -->
                <div id="suggestionsList">
                    @forelse($suggestions as $suggestion)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <a href="{{ route('social.profile', $suggestion) }}" class="flex items-center gap-2 hover:underline min-w-0">
                                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($suggestion->prenom, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700 truncate">{{ $suggestion->prenom }}</span>
                            </a>
                            @if(!Auth::user()->isFriendWith($suggestion->id) && !Auth::user()->hasPendingFriendRequestTo($suggestion->id) && !Auth::user()->hasPendingFriendRequestFrom($suggestion->id))
                                <form action="{{ route('social.friend.request', $suggestion) }}" method="POST" class="inline shrink-0">
                                    @csrf
                                    <button type="submit" class="text-xs text-emerald-600 hover:underline whitespace-nowrap">Ajouter</button>
                                </form>
                            @elseif(Auth::user()->hasPendingFriendRequestTo($suggestion->id))
                                <span class="text-xs text-gray-400 whitespace-nowrap">En attente</span>
                            @elseif(Auth::user()->hasPendingFriendRequestFrom($suggestion->id))
                                <form action="{{ route('social.friend.accept', $suggestion) }}" method="POST" class="inline shrink-0">
                                    @csrf
                                    <button type="submit" class="text-xs text-emerald-600 hover:underline whitespace-nowrap">Accepter</button>
                                </form>
                            @elseif(Auth::user()->isFriendWith($suggestion->id))
                                <span class="text-xs text-emerald-600 whitespace-nowrap">Amis</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Aucune suggestion.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALE PUBLICATION (responsive) -->
<div id="publishModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-auto p-6 relative">
        <button id="closePublishModal" class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Publier quelque chose</h2>
        <form action="{{ route('social.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <textarea name="contenu" rows="4" class="w-full border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="Quoi de neuf ?"></textarea>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-4 gap-3">
                <input type="file" name="media" accept="image/*" class="text-sm text-gray-500 w-full sm:w-auto">
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-emerald-700 transition w-full sm:w-auto">Publier</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modale
    const openModal = document.getElementById('openPublishModal');
    const modal = document.getElementById('publishModal');
    const closeModal = document.getElementById('closePublishModal');

    openModal.addEventListener('click', () => modal.classList.remove('hidden'));
    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });

    // Recherche dynamique
    const searchInput = document.getElementById('searchUsers');
    const resultsDiv = document.getElementById('searchResults');
    const suggestionsList = document.getElementById('suggestionsList');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            suggestionsList.classList.remove('hidden');
            return;
        }
        fetch(`/social/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                if (users.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-sm text-gray-500 p-2">Aucun résultat.</p>';
                } else {
                    let html = '';
                    users.forEach(user => {
                        html += `
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <a href="/social/profile/${user.id}" class="flex items-center gap-2 hover:underline min-w-0">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-sm shrink-0">${user.prenom.charAt(0).toUpperCase()}</div>
                                    <span class="text-sm font-medium text-gray-700 truncate">${user.prenom} ${user.nom}</span>
                                </a>
                                <form action="/social/friend/request/${user.id}" method="POST" class="inline shrink-0">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="text-xs text-emerald-600 hover:underline whitespace-nowrap">Ajouter</button>
                                </form>
                            </div>
                        `;
                    });
                    resultsDiv.innerHTML = html;
                }
                resultsDiv.classList.remove('hidden');
                suggestionsList.classList.add('hidden');
            })
            .catch(console.error);
    });
</script>
@endsection