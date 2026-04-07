<?php

declare(strict_types=1);

use App\Models\Tenant;
use Spatie\Permission\Models\Role as SpatieRole;

describe('Tenant model', function () {
    it('auto-generates slug from name if not provided', function () {
        $tenant = Tenant::factory()->make(['name' => 'Sira Bonn', 'slug' => null]);
        $tenant->save();
        expect($tenant->slug)->toBe('sira-bonn');
    });

    it('uses provided slug if given', function () {
        $tenant = Tenant::factory()->create(['slug' => 'custom-slug']);
        expect($tenant->slug)->toBe('custom-slug');
    });

    it('seeds 5 roles when created', function () {
        $tenant = Tenant::factory()->create();
        setPermissionsTeamId($tenant->id);
        expect(SpatieRole::where('tenant_id', $tenant->id)->count())->toBe(5);
        foreach (['tenant_admin', 'academy_manager', 'camp_manager', 'expo_manager', 'member'] as $role) {
            expect(SpatieRole::where('name', trans('roles.'.$role))->where('tenant_id', $tenant->id)->exists())->toBeTrue();
        }
    });

    it('does not share roles between tenants', function () {
        $tenantA = Tenant::factory()->create();
        $tenantB = Tenant::factory()->create();
        setPermissionsTeamId($tenantA->id);
        expect(SpatieRole::where('tenant_id', $tenantA->id)->count())->toBe(5);
        setPermissionsTeamId($tenantB->id);
        expect(SpatieRole::where('tenant_id', $tenantB->id)->count())->toBe(5);
        $rolesA = SpatieRole::where('tenant_id', $tenantA->id)->pluck('id')->toArray();
        $rolesB = SpatieRole::where('tenant_id', $tenantB->id)->pluck('id')->toArray();
        expect(array_intersect($rolesA, $rolesB))->toBeEmpty();
    });
});
