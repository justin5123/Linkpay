<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Share;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function timeline()
    {
        $user = Auth::user();
        $friendIds = $user->friends()->pluck('users.id')->toArray();
        $friendIds[] = $user->id;

        $posts = Post::with(['user', 'comments.user', 'likes', 'shares'])
            ->whereIn('users_id', $friendIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $suggestions = User::whereNotIn('id', $friendIds)
            ->where('id', '!=', $user->id)
            ->limit(5)
            ->get();

        return view('social.timeline', compact('posts', 'suggestions'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $users = User::where('prenom', 'LIKE', "%{$query}%")
            ->orWhere('nom', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->limit(10)
            ->get(['id', 'prenom', 'nom']);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string|max:2000',
            'media' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        $post = new Post();
        $post->users_id = Auth::id();
        $post->contenu = $request->contenu;
        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('posts', 'public');
            $post->media = $path;
        }
        $post->save();

        // Notifier les amis
        $amis = Auth::user()->friends()->get();
        foreach ($amis as $ami) {
            Notification::create([
                'users_id' => $ami->id,
                'type' => 'SOCIAL',
                'titre' => 'Nouvelle publication',
                'canal' => 'APP',
                'message' => Auth::user()->prenom . ' ' . Auth::user()->nom . ' a publié quelque chose.',
                'est_lu' => false,
                'priorite' => 'NORMALE',
                'lien_action' => route('social.post.show', $post->id), // Lien vers le post
            ]);
        }

        return redirect()->route('social.timeline')->with('success', 'Publication créée.');
    }

    public function showPost(Post $post)
    {
        $post->load(['user', 'comments.user', 'likes', 'shares']);
        return view('social.post', compact('post'));
    }

    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->users_id) abort(403);
        if ($post->media) Storage::disk('public')->delete($post->media);
        $post->delete();
        return back()->with('success', 'Publication supprimée.');
    }

    public function like(Post $post)
    {
        $like = $post->likes()->where('users_id', Auth::id())->first();
        if ($like) {
            $like->delete();
        } else {
            Like::create(['post_id' => $post->id, 'users_id' => Auth::id()]);
            if ($post->users_id !== Auth::id()) {
                Notification::create([
                    'users_id' => $post->users_id,
                    'type' => 'SOCIAL',
                    'titre' => 'Nouveau like',
                    'canal' => 'APP',
                    'message' => Auth::user()->prenom . ' ' . Auth::user()->nom . ' a aimé votre publication.',
                    'est_lu' => false,
                    'priorite' => 'NORMALE',
                    'lien_action' => route('social.post.show', $post->id), // Lien vers le post
                ]);
            }
        }
        return back();
    }

    public function share(Post $post, Request $request)
    {
        $request->validate(['commentaire' => 'nullable|string|max:500']);

        $existing = Share::where('user_id', Auth::id())->where('post_id', $post->id)->first();
        if ($existing) {
            $existing->delete();
        } else {
            Share::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'commentaire' => $request->commentaire
            ]);
            if ($post->users_id !== Auth::id()) {
                Notification::create([
                    'users_id' => $post->users_id,
                    'type' => 'SOCIAL',
                    'titre' => 'Nouveau partage',
                    'canal' => 'APP',
                    'message' => Auth::user()->prenom . ' ' . Auth::user()->nom . ' a partagé votre publication.',
                    'est_lu' => false,
                    'priorite' => 'NORMALE',
                    'lien_action' => route('social.post.show', $post->id), // Lien vers le post
                ]);
            }
        }
        return back();
    }

    public function comment(Post $post, Request $request)
    {
        $request->validate(['contenu' => 'required|string|max:1000']);
        Comment::create([
            'post_id' => $post->id,
            'users_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);
        if ($post->users_id !== Auth::id()) {
            Notification::create([
                'users_id' => $post->users_id,
                'type' => 'SOCIAL',
                'titre' => 'Nouveau commentaire',
                'canal' => 'APP',
                'message' => Auth::user()->prenom . ' ' . Auth::user()->nom . ' a commenté votre publication.',
                'est_lu' => false,
                'priorite' => 'NORMALE',
                'lien_action' => route('social.post.show', $post->id), // Lien vers le post
            ]);
        }
        return back()->with('success', 'Commentaire ajouté.');
    }

    public function profile(User $user)
    {
        $posts = Post::where('users_id', $user->id)->with(['user', 'comments.user', 'likes'])->orderBy('created_at', 'desc')->paginate(15);
        $isFollowing = Auth::user()->isFollowing($user);
        return view('social.profile', compact('user', 'posts', 'isFollowing'));
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(30);
        return view('social.followers', compact('user', 'followers'));
    }

    public function following(User $user)
    {
        $following = $user->following()->paginate(30);
        return view('social.following', compact('user', 'following'));
    }

    // ==================== AMITIÉ ====================

    public function friendRequest(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Vous ne pouvez pas vous ajouter vous-même.');

        $existing = Friendship::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
        })->first();

        if ($existing) return back()->with('error', 'Une demande existe déjà.');

        Friendship::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'status' => 'pending',
        ]);

        Notification::create([
            'users_id' => $user->id,
            'type' => 'SOCIAL',
            'titre' => 'Nouvelle demande d’amitié',
            'canal' => 'APP',
            'message' => Auth::user()->prenom . ' ' . Auth::user()->nom . ' vous a envoyé une demande d’amitié.',
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('social.profile', Auth::user()->id), // Profil de l'expéditeur
        ]);

        return back()->with('success', 'Demande envoyée.');
    }

    public function acceptFriendRequest(User $user)
    {
        $friendship = Friendship::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->update(['status' => 'accepted']);

        Notification::create([
            'users_id' => $user->id,
            'type' => 'SOCIAL',
            'titre' => 'Demande d’amitié acceptée',
            'canal' => 'APP',
            'message' => Auth::user()->prenom . ' ' . Auth::user()->nom . ' a accepté votre demande d’amitié.',
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('social.profile', Auth::user()->id), // Profil de l'accepteur
        ]);

        return back()->with('success', 'Vous êtes maintenant amis.');
    }

    public function rejectFriendRequest(User $user)
    {
        $friendship = Friendship::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->update(['status' => 'rejected']);
        return back()->with('success', 'Demande refusée.');
    }

    public function cancelFriendRequest(User $user)
    {
        $friendship = Friendship::where('sender_id', auth()->id())
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->delete();
        return back()->with('success', 'Demande annulée.');
    }

    public function unfriend(User $user)
    {
        $friendship = Friendship::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
        })->where('status', 'accepted')->firstOrFail();

        $friendship->delete();
        return back()->with('success', 'Vous n’êtes plus amis.');
    }
}