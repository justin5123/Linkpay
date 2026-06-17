<?php

namespace App\Events;

use App\Models\Appariement;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAppariement
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appariement;

    public function __construct(Appariement $appariement)
    {
        $this->appariement = $appariement;
    }
}