<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\MessageSupport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\NewSupportMessage;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('users_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie' => 'required|in:KYC,TRANSACTION,WALLET,ANNONCE,REMBOURSEMENT,SECURITE,COMPTE,AUTRE',
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
            'priorite' => 'required|in:FAIBLE,NORMALE,ELEVEE,URGENTE',
        ]);

        $ticket = SupportTicket::create([
            'users_id' => Auth::id(),
            'categorie' => $validated['categorie'],
            'sujet' => $validated['sujet'],
            'description' => $validated['description'],
            'priorite' => $validated['priorite'],
            'statut' => 'OUVERT',
            'reference' => 'TICK-' . strtoupper(Str::random(8)),
        ]);

        return redirect()->route('support.show', $ticket->id)
                         ->with('success', 'Votre ticket a été créé.');
    }

    public function show($id)
    {
        $ticket = SupportTicket::with('messages.expediteur', 'user')
                    ->where('users_id', Auth::id())
                    ->findOrFail($id);
        return view('support.show', compact('ticket'));
    }

    public function message(Request $request, $id)
    {
        $ticket = SupportTicket::where('users_id', Auth::id())->findOrFail($id);

        $request->validate([
            'message' => 'required|string',
        ]);

        $message = MessageSupport::create([
            'support_ticket_id' => $ticket->id,
            'expediteur_id' => Auth::id(),
            'destinataire_id' => null,
            'message' => $request->message,
            'est_lu' => false,
        ]);

        if ($ticket->statut === 'FERME') {
            $ticket->update(['statut' => 'OUVERT']);
        }

        // 🔔 Notification aux administrateurs et support
        $adminRoles = ['ADMIN', 'SUPPORT'];
        $admins = User::whereIn('role', $adminRoles)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'users_id' => $admin->id,
                'type' => 'SUPPORT',
                'titre' => 'Nouveau message client',
                'canal' => 'APP',
                'message' => Auth::user()->prenom . ' ' . Auth::user()->nom . ' a répondu au ticket #' . $ticket->reference,
                'est_lu' => false,
                'priorite' => 'NORMALE',
                'lien_action' => route('filament.admin.resources.support-tickets.reply', $ticket->id),
            ]);
        }

        broadcast(new NewSupportMessage($ticket, $message));

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Message envoyé.');
    }

    public function close($id)
    {
        $ticket = SupportTicket::where('users_id', Auth::id())->findOrFail($id);
        $ticket->update([
            'statut' => 'FERME',
            'date_fermeture' => now(),
        ]);
        return redirect()->route('support.index')->with('success', 'Ticket fermé.');
    }

    // ================== MÉTHODES POUR LE POLLING ==================

    public function getMessagesJson($id)
    {
        $ticket = SupportTicket::with('messages.expediteur')
                    ->where('users_id', Auth::id())
                    ->findOrFail($id);

        $messages = $ticket->messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'user_name' => $msg->expediteur->prenom . ' ' . $msg->expediteur->nom,
                'created_at' => $msg->created_at->format('H:i'),
                'is_mine' => $msg->expediteur_id == Auth::id(),
                'is_read' => $msg->est_lu,
            ];
        });

        return response()->json([
            'messages' => $messages,
            'last_id' => $ticket->messages->last()->id ?? null
        ]);
    }

    public function markAsRead($id)
    {
        $message = MessageSupport::where('id', $id)
                    ->where('destinataire_id', Auth::id())
                    ->first();

        if ($message && !$message->est_lu) {
            $message->update(['est_lu' => true, 'date_lecture' => now()]);
        }

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        $count = MessageSupport::where('destinataire_id', Auth::id())
                    ->where('est_lu', false)
                    ->count();
        return response()->json(['count' => $count]);
    }
}