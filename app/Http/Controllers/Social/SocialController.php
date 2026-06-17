<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    public function feed()
    {
        $user = Auth::user();
        // Récupérer les personnes suivies
        $followingIds = Follow::where('follower_id', $user->id)->pluck('following_id')->toArray();
        $followingIds[] = $user->id; // inclure ses propres posts

        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->whereIn('users_id', $followingIds)
            ->latest()
            ->paginate(10);

        return view('social.feed', compact('posts'));
    }

    public function storePost(Request $request)
    {
        $request->validate(['contenu' => 'required|string|max:1000']);
        Post::create([
            'users_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);
        return back()->with('success', 'Post publié.');
    }

    public function like($id)
    {
        $like = Like::where('post_id', $id)->where('users_id', Auth::id())->first();
        if ($like) {
            $like->delete();
        } else {
            Like::create(['post_id' => $id, 'users_id' => Auth::id()]);
        }
        return back();
    }

    public function comment(Request $request, $id)
    {
        $request->validate(['contenu' => 'required|string|max:500']);
        Comment::create([
            'post_id' => $id,
            'users_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);
        return back()->with('success', 'Commentaire ajouté.');
    }

    public function profile($id)
    {
        $user = User::findOrFail($id);
        $posts = Post::where('users_id', $id)->latest()->get();
        $isFollowing = Follow::where('follower_id', Auth::id())->where('following_id', $id)->exists();
        return view('social.profile', compact('user', 'posts', 'isFollowing'));
    }

    public function follow($id)
    {
        if (Auth::id() != $id) {
            Follow::firstOrCreate(['follower_id' => Auth::id(), 'following_id' => $id]);
        }
        return back()->with('success', 'Vous suivez maintenant cet utilisateur.');
    }

    public function unfollow($id)
    {
        Follow::where('follower_id', Auth::id())->where('following_id', $id)->delete();
        return back()->with('success', 'Vous ne suivez plus cet utilisateur.');
    }
}