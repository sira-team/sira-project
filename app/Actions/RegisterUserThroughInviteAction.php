<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\TenantInviteLink;
use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Lorisleiva\Actions\Concerns\AsAction;

final class RegisterUserThroughInviteAction
{
    use AsAction;

    public function handle(string $provider, SocialiteUserContract $oauthUser)
    {
        $token = session()->pull('join_token');
        $invite = TenantInviteLink::where('token', $token)->valid()->firstOrFail();

        $user = User::create([
            'name' => $oauthUser->getName(),
            'email' => $oauthUser->getEmail(),
            'email_verified_at' => now(),
            'tenant_id' => $invite->tenant_id,
        ]);

        //event(new UserRegisteredEvent());

        return $user;
    }
}
