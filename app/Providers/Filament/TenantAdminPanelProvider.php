<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Facade\SiraApp;
use App\Filament\Admin\Pages\EditTenant;
use App\Models\Tenant;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\FontProviders\GoogleFontProvider;
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
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class TenantAdminPanelProvider extends PanelProvider
{
    public const string ID = 'admin';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id(self::ID)
            ->path('admin')
            ->login()
            ->colors(['primary' => Color::Pink])
            ->font('Readex Pro', provider: GoogleFontProvider::class)
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->tenant(Tenant::class, slugAttribute: 'slug')
            ->tenantSwitcher(false)
            ->tenantMenu(false)
            ->tenantProfile(EditTenant::class)
            ->brandName(fn () => SiraApp::getTenant()?->name)
            ->navigationItems([
                NavigationItem::make('tenant')
                    ->label(__('Tenant'))
                    ->icon(Heroicon::OutlinedCog8Tooth)
                    ->url(fn () => EditTenant::getUrl()),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->pages([
                Dashboard::class,
            ])
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
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup(__('Management'))
                    ->parentResource(null)
                    ->scopeToTenant(true)
                    ->tenantRelationshipName('tenant')
                    ->navigationParentItem(null),
            ])
            ->tenantMiddleware([
                SyncShieldTenant::class,
                'tenant.feature:'.self::ID,
            ], isPersistent: true)
            ->authMiddleware([
                Authenticate::class,
            ])
            ->globalSearch(false)
            ->plugins([
                FilamentLanguageSwitcherPlugin::make()->locales(['ar', 'de', 'en']),
            ]);
    }
}
