<?php

namespace App\Filament\Widgets;

use App\Models\TransactionCompensee;
use Filament\Widgets\Widget;

class LitigesCounter extends Widget
{
    protected static string $view = 'filament.widgets.litiges-counter';
    public $count = 0;

    public function getListeners()
    {
        return [
            'refreshLitigesCount' => '$refresh',
        ];
    }

    public function mount()
    {
        $this->count = $this->getCount();
    }

    public function getCount()
    {
        return TransactionCompensee::where('statut', 'LITIGE')->count();
    }

    public function refresh()
    {
        $this->count = $this->getCount();
    }

    protected function getViewData(): array
    {
        return [
            'count' => $this->count,
        ];
    }
}