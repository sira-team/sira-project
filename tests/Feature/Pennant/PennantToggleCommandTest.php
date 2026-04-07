<?php

declare(strict_types=1);

use App\Models\Tenant;
use Laravel\Pennant\Feature;

describe('pennant toggle command', function () {
    it('grants a tenant feature to a tenant', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'expo', '--grant' => 'true'])
            ->assertSuccessful();
        expect(Feature::for($tenant)->active('expo'))->toBeTrue();
    });

    it('grants a user feature to a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        $this->artisan('pennant:toggle', ['--user' => $user->id, '--feature' => 'academy-content', '--grant' => 'true'])->assertSuccessful();
        expect(Feature::for($user)->active('academy-content'))->toBeTrue();
    });

    it('revokes a tenant feature from a tenant', function () {
        $tenant = Tenant::factory()->create();
        Feature::for($tenant)->activate('expo');
        expect(Feature::for($tenant)->active('expo'))->toBeTrue();

        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'expo', '--grant' => 'false'])
            ->assertSuccessful();
        expect(Feature::for($tenant)->active('expo'))->toBeFalse();
    });

    it('revokes a user feature from a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        Feature::for($user)->activate('academy-content');
        expect(Feature::for($user)->active('academy-content'))->toBeTrue();

        $this->artisan('pennant:toggle', ['--user' => $user->id, '--feature' => 'academy-content', '--grant' => 'false'])
            ->assertSuccessful();
        expect(Feature::for($user)->active('academy-content'))->toBeFalse();
    });

    it('fails when granting a tenant feature to a user', function () {
        $tenant = Tenant::factory()->create();
        $user = createUserForTenant($tenant);
        $this->artisan('pennant:toggle', ['--user' => $user->id, '--feature' => 'expo', '--grant' => 'true'])
            ->assertFailed();
    });

    it('fails when granting a user feature to a tenant', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'academy-content', '--grant' => 'true'])
            ->assertFailed();
    });

    it('fails with an invalid feature name', function () {
        $tenant = Tenant::factory()->create();
        $this->artisan('pennant:toggle', ['--tenant' => $tenant->id, '--feature' => 'not-a-real-feature', '--grant' => 'true'])
            ->assertFailed();
    });
});
