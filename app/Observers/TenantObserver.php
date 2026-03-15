<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tenant;
use Spatie\Permission\Models\Role;

class TenantObserver
{
    public function created(Tenant $tenant): void
    {
        setPermissionsTenantId($tenant->id);

        $roles = [
            'tenant_admin',
            'academy_manager',
            'camp_manager',
            'expo_manager',
            'member',
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);
        }
    }
}
