<?php

declare(strict_types=1);

namespace Modules\Camp\Providers\Filament;

use App\Models\Tenant;
use App\Providers\Filament\TenantAdminPanelProvider;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
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
    public const ID = 'camp';

    public function panel(Panel $panel): Panel
    {
        $separator = DIRECTORY_SEPARATOR;

        return $panel
            ->id(self::ID)
            ->path('camp')
            ->brandName($this->getNavigationLabel())
            ->colors([
                'primary' => Color::Amber,
            ])
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->tenant(Tenant::class, slugAttribute: 'slug')
            ->discoverResources(in: module('Camp', true)->appPath("Filament{$separator}Resources"), for: module('Camp', true)->appNamespace('Filament\Resources'))
            ->discoverPages(in: module('Camp', true)->appPath("Filament{$separator}Pages"), for: module('Camp', true)->appNamespace('Filament\Pages'))
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: module('Camp', true)->appPath("Filament{$separator}Widgets"), for: module('Camp', true)->appNamespace('Filament\Widgets'))
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->discoverClusters(in: module('Camp', true)->appPath("Filament{$separator}Clusters"), for: module('Camp', true)->appNamespace('Filament\Clusters'))
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->navigationItems([
                // Add a backlink to the tenant admin panel
                NavigationItem::make()
                    ->label(__('Back'))
                    ->sort(-1000)
                    ->icon('heroicon-o-home-modern')
                    ->url(fn () => Filament::getPanel(TenantAdminPanelProvider::ID)->getUrl()),
            ]);
    }

    public function getNavigationLabel(): string
    {
        return __('Camp');
    }
}
