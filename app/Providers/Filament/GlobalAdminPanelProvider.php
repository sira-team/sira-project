<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\FontProviders\GoogleFontProvider;
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

final class GlobalAdminPanelProvider extends PanelProvider
{
    public const string ID = 'global-admin';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id(self::ID)
            ->path('/global-admin')
            ->login()
            ->colors(['primary' => Color::Rose])
            ->font('Readex Pro', provider: GoogleFontProvider::class)
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->discoverResources(in: app_path('Filament/GlobalAdmin/Resources'), for: 'App\\Filament\\GlobalAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/GlobalAdmin/Pages'), for: 'App\\Filament\\GlobalAdmin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/GlobalAdmin/Widgets'), for: 'App\\Filament\\GlobalAdmin\\Widgets')
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
                'user.feature:'.self::ID,
            ])
            ->globalSearch(false)
            ->plugins([
                FilamentLanguageSwitcherPlugin::make()->locales(['ar', 'de', 'en']),
            ]);
    }
}
