<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Setup\AssignRolePermissions;
use App\Jobs\Setup\SeedEmailTemplates;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;

final class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        $teamKey = config('permission.column_names.tenant_foreign_key');

        setPermissionsTeamId($tenant->id);

        foreach (array_keys(AssignRolePermissions::roleResourceMap()) as $roleName) {
            Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                $teamKey => $tenant->id,
            ]);
        }

        dispatch_sync(new AssignRolePermissions($tenant));
        dispatch_sync(new SeedEmailTemplates($tenant));
    }
}
