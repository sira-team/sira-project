<?php

declare(strict_types=1);

namespace Modules\Academy\Traits;

use App\Enums\FeatureFlag;
use Illuminate\Foundation\Auth\User as AuthUser;
use Laravel\Pennant\Feature;

trait CheckAcademyContentManagementFeature
{
    public function before(AuthUser $authUser, string $ability): ?bool
    {
        if (Feature::for($authUser)->active(FeatureFlag::AcademyContentManagement->value)) {
            return true;
        }

        return null;
    }
}
