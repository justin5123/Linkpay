<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function about()
    {
        return view('public.about');
    }

    public function features()
    {
        return view('public.features');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        // Ici tu peux envoyer un email ou stocker en base
        // Pour l'instant, simple redirection avec message flash
        return redirect()->route('contact')->with('success', 'Message envoyé ! Nous vous répondrons rapidement.');
    }
}