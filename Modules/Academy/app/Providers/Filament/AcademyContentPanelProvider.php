<?php

declare(strict_types=1);

namespace Modules\Academy\Providers\Filament;

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
    public function panel(Panel $panel): Panel
    {
        $separator = DIRECTORY_SEPARATOR;

        return $panel
            ->id('academy-content')
            ->path('academy-content')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->maxContentWidth(Width::Full)
            ->discoverResources(
                in: module('Academy', true)->appPath("Filament{$separator}AcademyContent{$separator}Resources"),
                for: module('Academy', true)->appNamespace('Filament\AcademyContent\Resources'),
            )
            ->discoverPages(
                in: module('Academy', true)->appPath("Filament{$separator}AcademyContent{$separator}Pages"),
                for: module('Academy', true)->appNamespace('Filament\AcademyContent\Pages'),
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: module('Academy', true)->appPath("Filament{$separator}AcademyContent{$separator}Widgets"),
                for: module('Academy', true)->appNamespace('Filament\AcademyContent\Widgets'),
            )
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->discoverClusters(
                in: module('Academy', true)->appPath("Filament{$separator}AcademyContent{$separator}Clusters"),
                for: module('Academy', true)->appNamespace('Filament\AcademyContent\Clusters'),
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
            ], isPersistent: true);
    }
}
