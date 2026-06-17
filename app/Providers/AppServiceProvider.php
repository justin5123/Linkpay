<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\NewAppariement;
use App\Events\AppariementAccepte;
use App\Events\TransactionCompenseeCreated;
use App\Events\PreuveDeposee;
use App\Events\PaiementConfirme;
use App\Events\TransactionTerminee;
use App\Events\LitigeSignale;
use App\Listeners\SendNewAppariementNotification;
use App\Listeners\SendAppariementAccepteNotification;
use App\Listeners\SendTransactionCreatedNotification;
use App\Listeners\SendPreuveDeposeeNotification;
use App\Listeners\SendPaiementConfirmeNotification;
use App\Listeners\SendTransactionTermineeNotification;
use App\Listeners\SendLitigeSignaleNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewAppariement::class => [
            SendNewAppariementNotification::class,
        ],
        AppariementAccepte::class => [
            SendAppariementAccepteNotification::class,
        ],
        TransactionCompenseeCreated::class => [
            SendTransactionCreatedNotification::class,
        ],
        PreuveDeposee::class => [
            SendPreuveDeposeeNotification::class,
        ],
        PaiementConfirme::class => [
            SendPaiementConfirmeNotification::class,
        ],
        TransactionTerminee::class => [
            SendTransactionTermineeNotification::class,
        ],
        LitigeSignale::class => [
            SendLitigeSignaleNotification::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}