<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
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
            $role = Role::query()->firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
                'tenant_id' => $tenant->id,
            ]);

            if ($role->name === 'tenant_admin') {
                $role->permissions()->sync(DB::table('permissions')->pluck('id'));
            }
        }
    }
}
