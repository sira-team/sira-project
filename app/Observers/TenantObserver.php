<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Setup\CreateRolesForTenant;
use App\Jobs\Setup\SeedEmailTemplates;
use App\Models\Tenant;

final class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        dispatch_sync(new CreateRolesForTenant($tenant));
        dispatch_sync(new SeedEmailTemplates($tenant));
    }
}
