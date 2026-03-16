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

        $roleIds = $tenant->roles()->pluck('id')->toArray();
        $user->roles()->syncWithPivotValues($roleIds, ['tenant_id' => $tenant->id]);

        Feature::for($user)->activate(FeatureFlag::GlobalAdmin->value);
        Feature::for($user)->activate(FeatureFlag::AcademyContentManagement->value);
        Feature::for($tenant)->activate(FeatureFlag::TenantAdmin->value);
        Feature::for($tenant)->activate(FeatureFlag::AcademyPanel->value);
        Feature::for($tenant)->activate(FeatureFlag::CampPanel->value);
        Feature::for($tenant)->activate(FeatureFlag::ExpoPanel->value);
    }
}
