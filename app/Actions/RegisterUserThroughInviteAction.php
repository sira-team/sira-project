<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\UserRegisteredEvent;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Lorisleiva\Actions\Concerns\AsAction;

final class RegisterUserThroughInviteAction
{
    use AsAction;

    public function handle(string $provider, SocialiteUserContract $oauthUser)
    {
        session()->pull('join_token');
        $tenantId = session()->pull('join_tenant_id');
        $tenant = Tenant::find($tenantId);

        $user = User::create([
            'name' => $oauthUser->getName(),
            'email' => $oauthUser->getEmail(),
            'email_verified_at' => now(),
            'tenant_id' => $tenantId,
        ]);

        $this->assignDefaultRole($user, $tenant);

        event(new UserRegisteredEvent($user, $tenant));

        return $user;
    }

    private function assignDefaultRole(User $user, Tenant $tenant): void
    {
        $defaultRoleId = $tenant->settings->default_role_id;
        setPermissionsTeamId($tenant->id);
        $user->assignRole(Role::find($defaultRoleId));
    }
}
