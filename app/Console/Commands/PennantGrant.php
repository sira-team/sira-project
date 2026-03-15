<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Feature;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Pennant\Feature as PennantFeature;

final class PennantGrant extends Command
{
    protected $signature = 'pennant:grant {--tenant=} {--user=} {--feature=}';

    protected $description = 'Grant a Pennant feature flag to a tenant or user';

    public function handle(): int
    {
        $featureValue = $this->option('feature');
        $tenantId = $this->option('tenant');
        $userId = $this->option('user');

        if (! $featureValue) {
            $this->error('The --feature option is required.');

            return self::FAILURE;
        }

        $feature = Feature::tryFrom($featureValue);

        if (! $feature) {
            $this->error('Invalid feature. Valid values: '.implode(', ', array_column(Feature::cases(), 'value')));

            return self::FAILURE;
        }

        if ($tenantId) {
            // Ensure the feature is a tenant feature
            if (! in_array($feature, Feature::tenantFeatures())) {
                $this->error("Feature [{$feature->value}] is not a tenant feature.");

                return self::FAILURE;
            }

            $tenant = Tenant::findOrFail($tenantId);
            PennantFeature::for($tenant)->activate($feature->value);
            $this->info("Feature [{$feature->value}] granted to tenant [{$tenantId}].");

            return self::SUCCESS;
        }

        if ($userId) {
            // Ensure the feature is a user feature
            if (! in_array($feature, Feature::userFeatures())) {
                $this->error("Feature [{$feature->value}] is not a user feature.");

                return self::FAILURE;
            }

            $user = User::findOrFail($userId);
            PennantFeature::for($user)->activate($feature->value);
            $this->info("Feature [{$feature->value}] granted to user [{$userId}].");

            return self::SUCCESS;
        }

        $this->error('Either --tenant or --user is required.');

        return self::FAILURE;
    }
}
