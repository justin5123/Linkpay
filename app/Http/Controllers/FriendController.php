<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $friends = $user->friends()->paginate(20);
        $pendingRequests = $user->pendingFriendRequests()->with('sender')->get();
        $sentRequests = $user->sentFriendRequests()->where('status', 'pending')->with('receiver')->get();

        return view('social.friends', compact('friends', 'pendingRequests', 'sentRequests'));
    }

    public function sendRequest($userId)
    {
        $receiver = User::findOrFail($userId);

        if (Auth::id() == $userId) {
            return back()->with('error', 'Vous ne pouvez pas vous envoyer une demande à vous-même.');
        }

        if (Friendship::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)->where('receiver_id', Auth::id());
        })->exists()) {
            return back()->with('error', 'Une demande existe déjà.');
        }

        Friendship::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'status' => 'pending',
        ]);

        Notification::create([
            'users_id' => $userId,
            'type' => 'SOCIAL',
            'titre' => 'Demande d’amitié',
            'canal' => 'APP',
            'message' => Auth::user()->prenom . ' vous a envoyé une demande d’amitié.',
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('friends.index'),
        ]);

        return back()->with('success', 'Demande envoyée.');
    }

    public function acceptRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        if ($friendship->receiver_id != Auth::id()) {
            abort(403);
        }

        $friendship->update(['status' => 'accepted']);

        Notification::create([
            'users_id' => $friendship->sender_id,
            'type' => 'SOCIAL',
            'titre' => 'Demande d’amitié acceptée',
            'canal' => 'APP',
            'message' => Auth::user()->prenom . ' a accepté votre demande d’amitié.',
            'est_lu' => false,
            'priorite' => 'NORMALE',
            'lien_action' => route('friends.index'),
        ]);

        return back()->with('success', 'Amitié acceptée.');
    }

    public function rejectRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        if ($friendship->receiver_id != Auth::id()) {
            abort(403);
        }

        $friendship->update(['status' => 'rejected']);
        return back()->with('success', 'Demande refusée.');
    }

    public function unfriend($userId)
    {
        $user = Auth::user();
        Friendship::where(function ($query) use ($userId, $user) {
            $query->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId, $user) {
            $query->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->where('status', 'accepted')->delete();

        return back()->with('success', 'Amitié supprimée.');
    }
}
