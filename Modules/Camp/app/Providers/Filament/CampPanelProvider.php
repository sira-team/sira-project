<?php

declare(strict_types=1);

namespace Modules\Camp\Providers\Filament;

use App\Facade\SiraApp;
use App\Models\Tenant;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class CampPanelProvider extends PanelProvider
{
    public const string ID = 'camp';

    public function panel(Panel $panel): Panel
    {
        $separator = DIRECTORY_SEPARATOR;
        $modulePath = base_path("Modules{$separator}Camp{$separator}app");
        $moduleNamespace = 'Modules\\Camp\\';

        return $panel
            ->id(self::ID)
            ->path('camp')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->topNavigation()
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->tenant(Tenant::class, slugAttribute: 'slug')
            ->tenantMenu(false)
            ->brandName(fn () => SiraApp::getTenant()->name ?? config('app.name'))
            ->discoverResources(in: "{$modulePath}{$separator}Filament{$separator}Resources", for: "{$moduleNamespace}Filament\\Resources")
            ->discoverPages(in: "{$modulePath}{$separator}Filament{$separator}Pages", for: "{$moduleNamespace}Filament\\Pages")
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: "{$modulePath}{$separator}Filament{$separator}Widgets", for: "{$moduleNamespace}Filament\\Widgets")
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ])
            ->tenantMiddleware([
                SyncShieldTenant::class,
                'tenant.feature:'.self::ID,
            ], isPersistent: true)
            ->resourceEditPageRedirect('view')
            ->globalSearch(false)
            ->plugins([
                FilamentLanguageSwitcherPlugin::make()->locales(['ar', 'de', 'en']),
            ]);
    }

    public function getNavigationLabel(): string
    {
        return __('Camp');
    }
}
