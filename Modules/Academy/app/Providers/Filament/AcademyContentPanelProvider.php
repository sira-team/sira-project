<?php

declare(strict_types=1);

namespace Modules\Academy\Providers\Filament;

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
use Modules\Academy\Http\Middleware\CheckAcademyContentManagement;

final class AcademyContentPanelProvider extends PanelProvider
{
    public const string ID = 'academy-content';

    public function panel(Panel $panel): Panel
    {
        $separator = DIRECTORY_SEPARATOR;
        $modulePath = base_path("Modules{$separator}Academy{$separator}app");
        $moduleNamespace = 'Modules\\Academy\\';

        return $panel
            ->id(self::ID)
            ->path('academy-content')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->discoverResources(
                in: "{$modulePath}{$separator}Filament{$separator}AcademyContent{$separator}Resources",
                for: "{$moduleNamespace}Filament\AcademyContent\Resources",
            )
            ->discoverPages(
                in: "{$modulePath}{$separator}Filament{$separator}AcademyContent{$separator}Pages",
                for: "{$moduleNamespace}Filament\AcademyContent\Pages",
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: "{$modulePath}{$separator}Filament{$separator}AcademyContent{$separator}Widgets",
                for: "{$moduleNamespace}Filament\AcademyContent\Widgets",
            )
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->discoverClusters(
                in: "{$modulePath}{$separator}Filament{$separator}AcademyContent{$separator}Clusters",
                for: "{$moduleNamespace}Filament\AcademyContent\Clusters",
            )
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
                CheckAcademyContentManagement::class,
                'user.feature:'.self::ID,
            ], isPersistent: true)
            ->globalSearch(false)
            ->plugins([
                FilamentLanguageSwitcherPlugin::make()->locales(['ar', 'de', 'en']),
            ]);
    }
}
