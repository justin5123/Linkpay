@component('mail::message')
# Nouvel appariement trouvé

Bonjour {{ $appariement->annonceEnvoi->user->prenom ?? $appariement->annonceEnvoi->user->nom }},

Un appariement a été trouvé pour votre annonce. Veuillez vous connecter pour l'accepter.

**Détails :**
- Montant à payer : {{ number_format($appariement->montant_compense,2) }} {{ $appariement->annonceEnvoi->devise_source }}
- Bénéficiaire : {{ $appariement->annonceReception->beneficiaire_nom ?? $appariement->annonceReception->user->prenom }}

@component('mail::button', ['url' => route('dashboard')])
Voir mon dashboard
@endcomponent

Merci d'utiliser LinPay.
@endcomponent