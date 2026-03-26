<?php

declare(strict_types=1);

namespace App\Jobs\Setup;

use App\Enums\FeatureFlag;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Pennant\Feature;
use Spatie\Permission\PermissionRegistrar;

final class GrantFeatureAccess
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function beforeCommit() {}

    public function afterCommit() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var array<string, mixed> $admin */
        $admin = config('setup.super_admin');

        $user = User::firstWhere('email', $admin['email']);
        $tenant = $user->tenant;

        setPermissionsTeamId($tenant->id);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user->syncRoles($tenant->roles);

        Feature::for($user)->activate(FeatureFlag::GlobalAdmin->value);
        Feature::for($user)->deactivate(FeatureFlag::AcademyManager->value);
        Feature::for($tenant)->activate(FeatureFlag::TenantAdmin->value);
        Feature::for($tenant)->deactivate(FeatureFlag::AcademyPanel->value);
        Feature::for($tenant)->active(FeatureFlag::CampPanel->value);
        Feature::for($tenant)->deactivate(FeatureFlag::ExpoPanel->value);
    }
}
