<?php

declare(strict_types=1);

use App\Models\Tenant;
use Laravel\Pennant\Feature;

describe('pennant commands', function () {
    it('grants a tenant feature to a tenant', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:grant', ['--tenant' => $tenant->id, '--feature' => 'expo-panel'])
            ->assertSuccessful();
        expect(Feature::for($tenant)->active('expo-panel'))->toBeTrue();
    });

    it('grants a user feature to a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        $this->artisan('pennant:grant', ['--user' => $user->id, '--feature' => 'academy-content-management'])
            ->assertSuccessful();
        expect(Feature::for($user)->active('academy-content-management'))->toBeTrue();
    });

    it('fails when granting a team feature to a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        $this->artisan('pennant:grant', ['--user' => $user->id, '--feature' => 'expo-panel'])
            ->assertFailed();
    });

    it('fails when granting a user feature to a tenant', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:grant', ['--tenant' => $tenant->id, '--feature' => 'academy-content-management'])
            ->assertFailed();
    });

    it('fails with an invalid feature name', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:grant', ['--tenant' => $tenant->id, '--feature' => 'not-a-real-feature'])
            ->assertFailed();
    });
});
