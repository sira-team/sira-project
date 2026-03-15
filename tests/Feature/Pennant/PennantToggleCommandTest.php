<?php

declare(strict_types=1);

use App\Models\Tenant;
use Laravel\Pennant\Feature;

describe('pennant toggle command', function () {
    it('grants a tenant feature to a tenant', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'expo-panel', '--grant' => 'true'])
            ->assertSuccessful();
        expect(Feature::for($tenant)->active('expo-panel'))->toBeTrue();
    });

    it('grants a user feature to a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        $this->artisan('pennant:toggle', ['--user' => $user->id, '--feature' => 'academy-content-management', '--grant' => 'true'])
            ->assertSuccessful();
        expect(Feature::for($user)->active('academy-content-management'))->toBeTrue();
    });

    it('revokes a tenant feature from a tenant', function () {
        $tenant = Tenant::factory()->create();
        Feature::for($tenant)->activate('expo-panel');
        expect(Feature::for($tenant)->active('expo-panel'))->toBeTrue();

        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'expo-panel', '--grant' => 'false'])
            ->assertSuccessful();
        expect(Feature::for($tenant)->active('expo-panel'))->toBeFalse();
    });

    it('revokes a user feature from a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        Feature::for($user)->activate('academy-content-management');
        expect(Feature::for($user)->active('academy-content-management'))->toBeTrue();

        $this->artisan('pennant:toggle', ['--user' => $user->id, '--feature' => 'academy-content-management', '--grant' => 'false'])
            ->assertSuccessful();
        expect(Feature::for($user)->active('academy-content-management'))->toBeFalse();
    });

    it('fails when granting a tenant feature to a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        $this->artisan('pennant:toggle', ['--user' => $user->id, '--feature' => 'expo-panel', '--grant' => 'true'])
            ->assertFailed();
    });

    it('fails when granting a user feature to a tenant', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'academy-content-management', '--grant' => 'true'])
            ->assertFailed();
    });

    it('fails with an invalid feature name', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'not-a-real-feature', '--grant' => 'true'])
            ->assertFailed();
    });
});
