<?php

declare(strict_types=1);

namespace Modules\Camp\Providers\Filament;

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

final class CampPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $separator = DIRECTORY_SEPARATOR;

        return $panel
            ->id('camp-camp')
            ->path('camp/camp')
            ->brandName($this->getNavigationLabel())
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: module('Camp', true)->appPath("Filament{$separator}CampCamp{$separator}Resources"), for: module('Camp', true)->appNamespace('Filament\CampCamp\Resources'))
            ->discoverPages(in: module('Camp', true)->appPath("Filament{$separator}CampCamp{$separator}Pages"), for: module('Camp', true)->appNamespace('Filament\CampCamp\Pages'))
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: module('Camp', true)->appPath("Filament{$separator}CampCamp{$separator}Widgets"), for: module('Camp', true)->appNamespace('Filament\CampCamp\Widgets'))
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->discoverClusters(in: module('Camp', true)->appPath("Filament{$separator}CampCamp{$separator}Clusters"), for: module('Camp', true)->appNamespace('Filament\CampCamp\Clusters'))
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
        return __('Camp');
    }
}
