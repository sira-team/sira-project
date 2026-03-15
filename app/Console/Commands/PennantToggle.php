<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\FeatureFlag;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Pennant\Feature as PennantFeature;

final class PennantToggle extends Command
{
    protected $signature = 'pennant:toggle {--tenant=} {--user=} {--feature=} {--grant=}';

    protected $description = 'Toggle a Pennant feature flag for a tenant or user (activate/deactivate)';

    public function handle(): int
    {
        $featureValue = $this->option('feature') ?? $this->selectFeature();
        $tenantId = $this->option('tenant');
        $userId = $this->option('user');
        $shouldGrant = $this->option('grant') ?? $this->selectAction();

        $feature = FeatureFlag::tryFrom($featureValue);

        if (! $feature) {
            $this->error('Invalid feature: '.$featureValue);

            return self::FAILURE;
        }

        $shouldGrant = filter_var($shouldGrant, FILTER_VALIDATE_BOOLEAN);
        $modelClass = $feature->for();

        // Check for mismatches between provided ID type and feature scope
        if ($modelClass === Tenant::class && $userId) {
            $this->error("Feature [{$feature->value}] is not a user feature.");

            return self::FAILURE;
        }

        if ($modelClass === User::class && $tenantId) {
            $this->error("Feature [{$feature->value}] is not a tenant feature.");

            return self::FAILURE;
        }

        // Auto-determine if tenant or user based on feature definition
        if ($modelClass === Tenant::class) {
            $tenantId ??= $this->ask('Enter tenant ID');

            $tenant = Tenant::findOrFail($tenantId);

            if ($shouldGrant) {
                PennantFeature::for($tenant)->activate($feature->value);
                $this->info("Feature [{$feature->value}] granted to tenant [{$tenantId}].");
            } else {
                PennantFeature::for($tenant)->deactivate($feature->value);
                $this->info("Feature [{$feature->value}] revoked from tenant [{$tenantId}].");
            }

            return self::SUCCESS;
        }

        if ($modelClass === User::class) {
            $userId ??= $this->ask('Enter user ID');

            $user = User::findOrFail($userId);

            if ($shouldGrant) {
                PennantFeature::for($user)->activate($feature->value);
                $this->info("Feature [{$feature->value}] granted to user [{$userId}].");
            } else {
                PennantFeature::for($user)->deactivate($feature->value);
                $this->info("Feature [{$feature->value}] revoked from user [{$userId}].");
            }

            return self::SUCCESS;
        }

        $this->error('Unknown feature scope.');

        return self::FAILURE;
    }

    private function selectFeature(): string
    {
        $features = array_column(FeatureFlag::cases(), 'value');

        return $this->choice(
            'Select a feature flag:',
            $features
        );
    }

    private function selectAction(): string
    {
        return $this->choice(
            'Grant or revoke?',
            ['true' => 'Grant', 'false' => 'Revoke'],
            'true'
        );
    }
}
