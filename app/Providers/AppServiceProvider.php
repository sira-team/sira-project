<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\FeatureFlag;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\Filament\GlobalAdminPanelProvider;
use App\Providers\Filament\TenantAdminPanelProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature as PennantFeature;
use Modules\Academy\Providers\Filament\AcademyContentPanelProvider;
use Modules\Academy\Providers\Filament\AcademyPanelProvider;
use Modules\Camp\Providers\Filament\CampPanelProvider;
use Modules\Expo\Providers\Filament\ExpoPanelProvider;
use Spatie\Permission\PermissionRegistrar;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);

        $this->registerPennantFeatures();
        $this->registerPanelSwitch();
    }

    private function registerPennantFeatures(): void
    {
        // Tenant-scoped features
        PennantFeature::define(FeatureFlag::TenantAdmin->value, fn (Tenant $tenant) => true);
        PennantFeature::define(FeatureFlag::CampPanel->value, fn (Tenant $tenant) => true);
        PennantFeature::define(FeatureFlag::ExpoPanel->value, fn (Tenant $tenant) => false);
        PennantFeature::define(FeatureFlag::AcademyPanel->value, fn (Tenant $tenant) => false);

        // User-scoped features — default false for all users
        PennantFeature::define(FeatureFlag::AcademyManager->value, fn (User $user) => false);
        PennantFeature::define(FeatureFlag::GlobalAdmin->value, fn (User $user) => false);
    }

    private function registerPanelSwitch(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $activePanelIds = collect([
                CampPanelProvider::ID,
                AcademyPanelProvider::ID,
                ExpoPanelProvider::ID,
                TenantAdminPanelProvider::ID,
                GlobalAdminPanelProvider::ID,
                AcademyContentPanelProvider::ID,
            ])
                ->filter(fn (string $panelId) => auth()->user()?->canAccessFeature($panelId))
                ->values()
                ->all();

            $panelSwitch
                ->iconSize(64)
                ->modalHeading(__('Switch Panels'))
                ->panels($activePanelIds)
                ->labels([
                    CampPanelProvider::ID => __('Camps'),
                    AcademyPanelProvider::ID => __('Academy'),
                    ExpoPanelProvider::ID => __('Expo'),
                    TenantAdminPanelProvider::ID => __('Admin'),
                    GlobalAdminPanelProvider::ID => __('Global Admin'),
                    AcademyContentPanelProvider::ID => __('Academy Manager'),
                ])
                ->icons([
                    CampPanelProvider::ID => asset('images/navigation/map.svg'),
                    AcademyPanelProvider::ID => asset('images/navigation/trophy.svg'),
                    ExpoPanelProvider::ID => asset('images/navigation/microphone.svg'),
                    TenantAdminPanelProvider::ID => asset('images/navigation/key.svg'),
                    GlobalAdminPanelProvider::ID => asset('images/navigation/chart-line.svg'),
                    AcademyContentPanelProvider::ID => asset('images/navigation/book-opened.svg'),
                ], asImage: true);
        });
    }
}
