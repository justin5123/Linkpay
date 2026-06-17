<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\MessageSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('users_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        if ($ticket->users_id !== auth()->id()) {
            abort(403);
        }
        $messages = $ticket->messages()->with('expediteur')->orderBy('created_at')->get();
        return view('support.show', compact('ticket', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'users_id' => auth()->id(),
            'sujet' => $request->sujet,
            'description' => $request->description,
            'statut' => 'OUVERT',
            'priorite' => 'NORMALE',
            'reference' => 'TICK-' . strtoupper(Str::random(8)),
        ]);

        return redirect()->route('support.show', $ticket)->with('success', 'Ticket créé avec succès.');
    }

    public function storeMessage(Request $request, SupportTicket $ticket)
    {
        if ($ticket->users_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        MessageSupport::create([
            'support_ticket_id' => $ticket->id,
            'expediteur_id' => auth()->id(),
            'message' => $request->message,
            'est_lu' => false,
        ]);

        // Remettre le ticket en statut "OUVERT" ou "EN_COURS"
        if ($ticket->statut === 'FERME') {
            $ticket->update(['statut' => 'OUVERT']);
        } elseif ($ticket->statut === 'RESOLU') {
            $ticket->update(['statut' => 'EN_COURS']);
        }

        return back()->with('success', 'Message envoyé.');
    }

    public function close(SupportTicket $ticket)
    {
        if ($ticket->users_id !== auth()->id()) {
            abort(403);
        }
        $ticket->update(['statut' => 'FERME']);
        return redirect()->route('support.index')->with('success', 'Ticket fermé.');
    }
}