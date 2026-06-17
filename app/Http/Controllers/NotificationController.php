<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Récupérer les notifications pour le dropdown (JSON)
     */
    public function index()
    {
        $notifications = Notification::where('users_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get()
                            ->map(function($n) {
                                return [
                                    'id' => $n->id,
                                    'titre' => $n->titre,
                                    'message' => $n->message,
                                    'type' => $n->type,
                                    'est_lu' => $n->est_lu,
                                    'created_at' => $n->created_at->toDateTimeString(),
                                    'lien_action' => $n->lien_action,
                                ];
                            });
        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Page complète des notifications (HTML)
     */
    public function viewAll()
    {
        $notifications = Notification::where('users_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Compter les notifications non lues (JSON)
     */
    public function unreadCount()
    {
        $count = Notification::where('users_id', Auth::id())
                    ->where('est_lu', false)
                    ->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Marquer une notification comme lue (AJAX)
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('users_id', Auth::id())
                        ->where('id', $id)
                        ->first();
        if ($notification && !$notification->est_lu) {
            $notification->update(['est_lu' => true, 'date_lecture' => now()]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Notification::where('users_id', Auth::id())
            ->where('est_lu', false)
            ->update(['est_lu' => true, 'date_lecture' => now()]);
        return response()->json(['success' => true]);
    }
}