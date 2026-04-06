<?php

declare(strict_types=1);

namespace App\Actions;

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

        $user = User::create([
            'name' => $oauthUser->getName(),
            'email' => $oauthUser->getEmail(),
            'email_verified_at' => now(),
            'tenant_id' => $tenantId,
        ]);

        // event(new UserRegisteredEvent());

        return $user;
    }
}
