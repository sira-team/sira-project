<?php

declare(strict_types=1);

use App\Models\Tenant;
use App\Models\User;

function createTenant(array $attributes = []): Tenant
{
    return Tenant::factory()->create($attributes);
}

function createUserForTenant(Tenant $tenant, string $role = 'member'): User
{
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $roleModel = App\Models\Role::where('tenant_id', $tenant->id)
        ->where('name', trans('roles.'.$role))
        ->firstOrFail();

    DB::table('model_has_roles')->insert([
        'role_id' => $roleModel->id,
        'model_id' => $user->id,
        'model_type' => User::class,
        'tenant_id' => $tenant->id,
    ]);

    return $user;
}

function actingAsTenantUser(Tenant $tenant, string $role = 'tenant_admin'): User
{
    $user = createUserForTenant($tenant, $role);
    test()->actingAs($user);

    return $user;
}
