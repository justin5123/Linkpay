<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous suivre vous-même');
        }

        Auth::user()->follow($user);
        $isFollowing = Auth::user()->isFollowing($user);
        $message = $isFollowing ? 'Vous suivez maintenant ' . $user->prenom : 'Vous ne suivez plus ' . $user->prenom;

        return back()->with('success', $message);
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
}