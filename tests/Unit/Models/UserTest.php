<?php

declare(strict_types=1);

use App\Models\Tenant;
use App\Models\User;

describe('User model', function () {
    it('belongs to a tenant', function () {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        expect($user->tenant->id)->toBe($tenant->id);
    });

    it('can be assigned a role scoped to tenant', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant, 'camp_manager');
        setPermissionsTeamId($tenant->id);
        expect($user->hasRole('camp_manager'))->toBeTrue();
    });

    it('role from one tenant does not bleed into another', function () {
        $tenantA = Tenant::factory()->create();
        $tenantB = Tenant::factory()->create();
        $user = createUserForTenant($tenantA, 'camp_manager');

        // Verify the role is in the database for tenantA
        $roleARecord = Illuminate\Support\Facades\DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('tenant_id', $tenantA->id)
            ->exists();
        expect($roleARecord)->toBeTrue();

        // Verify no role exists for tenantB
        $roleBRecord = Illuminate\Support\Facades\DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('tenant_id', $tenantB->id)
            ->exists();
        expect($roleBRecord)->toBeFalse();
    });
});
