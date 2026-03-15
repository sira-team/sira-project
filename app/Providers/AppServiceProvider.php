<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\Feature;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature as PennantFeature;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
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
    }

    private function registerPennantFeatures(): void
    {
        // Team-scoped features — default false for all tenants
        PennantFeature::define(Feature::ExpoPanel->value, fn (Team $team) => false);
        PennantFeature::define(Feature::AcademyPanel->value, fn (Team $team) => false);

        // User-scoped features — default false for all users
        PennantFeature::define(Feature::AcademyContentManagement->value, fn (User $user) => false);
    }
}
