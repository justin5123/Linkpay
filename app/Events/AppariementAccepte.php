<?php

namespace App\Events;

use App\Models\Appariement;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class AppariementAccepte
{
    use Dispatchable;

    public $appariement;
    public $accepteur;

    public function __construct(Appariement $appariement, User $accepteur)
    {
        $this->appariement = $appariement;
        $this->accepteur = $accepteur;
    }
}