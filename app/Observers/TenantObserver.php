<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tenant;
use Spatie\Permission\Models\Role;

final class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        setPermissionsTeamId($tenant->id);

        $roles = [
            'tenant_admin',
            'academy_manager',
            'camp_manager',
            'expo_manager',
            'member',
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);
        }
    }
}
