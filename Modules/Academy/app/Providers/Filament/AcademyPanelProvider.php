<?php

declare(strict_types=1);

namespace Modules\Academy\Providers\Filament;

use App\Models\Tenant;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AcademyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $separator = DIRECTORY_SEPARATOR;

        return $panel
            ->id('academy')
            ->path('academy')
            ->colors(['primary' => Color::Indigo])
            ->tenant(Tenant::class, slugAttribute: 'slug')
            ->discoverResources(
                in: module('Academy', true)->appPath("Filament{$separator}AcademyTenant{$separator}Resources"),
                for: module('Academy', true)->appNamespace('Filament\AcademyTenant\Resources'),
            )
            ->discoverPages(
                in: module('Academy', true)->appPath("Filament{$separator}AcademyTenant{$separator}Pages"),
                for: module('Academy', true)->appNamespace('Filament\AcademyTenant\Pages'),
            )
            ->pages([Dashboard::class])
            ->discoverWidgets(
                in: module('Academy', true)->appPath("Filament{$separator}AcademyTenant{$separator}Widgets"),
                for: module('Academy', true)->appNamespace('Filament\AcademyTenant\Widgets'),
            )
            ->widgets([AccountWidget::class, FilamentInfoWidget::class])
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
            ->plugins([FilamentShieldPlugin::make()->scopeToTenant(true)])
            ->tenantMiddleware([SyncShieldTenant::class], isPersistent: true)
            ->authMiddleware([Authenticate::class]);
    }
}
