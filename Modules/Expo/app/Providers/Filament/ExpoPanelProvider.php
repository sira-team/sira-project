<?php

declare(strict_types=1);

namespace Modules\Expo\Providers\Filament;

use App\Models\Tenant;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Pennant\Middleware\EnsureFeatureIsActive;

class ExpoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $separator = DIRECTORY_SEPARATOR;

        return $panel
            ->id('expo')
            ->path('expo')
            ->brandName($this->getNavigationLabel())
            ->colors([
                'primary' => Color::Amber,
            ])
            ->tenant(Tenant::class)
            ->discoverResources(in: module('Expo', true)->appPath("Filament{$separator}Resources"), for: module('Expo', true)->appNamespace('Filament\Resources'))
            ->discoverPages(in: module('Expo', true)->appPath("Filament{$separator}Pages"), for: module('Expo', true)->appNamespace('Filament\Pages'))
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: module('Expo', true)->appPath("Filament{$separator}Widgets"), for: module('Expo', true)->appNamespace('Filament\Widgets'))
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->plugin(FilamentShieldPlugin::make()->scopeToTenant(true))
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
                EnsureFeatureIsActive::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->navigationItems([
                // Add a backlink to the default panel
                NavigationItem::make()
                    ->label(__('Back Home'))
                    ->sort(-1000)
                    ->icon(Heroicon::OutlinedHomeModern)
                    ->url(filament()->getDefaultPanel()->getUrl()),
            ]);
    }

    public function getNavigationLabel(): string
    {
        return __('Expo');
    }
}
