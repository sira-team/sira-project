<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\FeatureFlag;
use Illuminate\Foundation\Auth\User as AuthUser;
use Laravel\Pennant\Feature;

trait CheckGlobalAdminFeature
{
    public function before(AuthUser $authUser, string $ability): ?bool
    {
        if (Feature::for($authUser)->active(FeatureFlag::GlobalAdmin->value)) {
            return true;
        }

        return null;
    }
}
