<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Notification;
use App\Filament\Pages\ListNotifications;
use App\Helpers\LitigeHelper;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                ListNotifications::class,
                \App\Filament\Pages\SupportTicketConversation::class,
            ])
            ->navigationItems([
                NavigationItem::make('Notifications')
                    ->url('/admin/list-notifications')
                    ->icon('heroicon-o-bell')
                    ->badge(Notification::where('users_id', auth()->id())->where('est_lu', false)->count()),
                NavigationItem::make('Litiges non traités')
                    ->url('/admin/resources/transaction-compensees')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->badge(LitigeHelper::countLitigesNonTraites()),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\LitigesOverview::class,
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\LitigesStats::class,
                \App\Filament\Widgets\LatestAdminLogs::class,
                 \App\Filament\Widgets\TransactionStats::class,

                \App\Filament\Widgets\MonthlyTransactionsChart::class,
                \App\Filament\Widgets\TransactionStatusPieChart::class,
                \App\Filament\Pages\SupportTicketConversation::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}