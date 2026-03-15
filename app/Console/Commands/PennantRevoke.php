<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Feature;
use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Pennant\Feature as PennantFeature;

class PennantRevoke extends Command
{
    protected $signature = 'pennant:revoke {--team=} {--user=} {--feature=}';

    protected $description = 'Revoke a Pennant feature flag from a team or user';

    public function handle(): int
    {
        $featureValue = $this->option('feature');
        $teamId = $this->option('team');
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

        if ($teamId) {
            // Ensure the feature is a team feature
            if (! in_array($feature, Feature::teamFeatures())) {
                $this->error("Feature [{$feature->value}] is not a team feature.");

                return self::FAILURE;
            }

            $team = Team::findOrFail($teamId);
            PennantFeature::for($team)->deactivate($feature->value);
            $this->info("Feature [{$feature->value}] revoked from team [{$teamId}].");

            return self::SUCCESS;
        }

        if ($userId) {
            // Ensure the feature is a user feature
            if (! in_array($feature, Feature::userFeatures())) {
                $this->error("Feature [{$feature->value}] is not a user feature.");

                return self::FAILURE;
            }

            $user = User::findOrFail($userId);
            PennantFeature::for($user)->deactivate($feature->value);
            $this->info("Feature [{$feature->value}] revoked from user [{$userId}].");

            return self::SUCCESS;
        }

        $this->error('Either --team or --user is required.');

        return self::FAILURE;
    }
}
