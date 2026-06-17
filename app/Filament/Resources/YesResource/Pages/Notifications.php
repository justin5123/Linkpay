<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Notification;

class Notifications extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notifications';
    protected static ?string $title = 'Mes notifications';
    protected static string $view = 'filament.pages.notifications';

    public function getNotifications()
    {
        return auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }
}