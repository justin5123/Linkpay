@component('mail::message')
# Preuve de paiement déposée

Bonjour,

{{ $payeur->prenom }} a déposé une preuve de paiement pour la transaction **{{ $transaction->reference }}**.

Veuillez vous connecter pour confirmer la réception.

@component('mail::button', ['url' => route('dashboard')])
Confirmer la réception
@endcomponent

Cordialement,<br>
L'équipe LinPay
@endcomponent