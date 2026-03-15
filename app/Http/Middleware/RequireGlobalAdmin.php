<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FeatureFlag;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;

final class RequireGlobalAdmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->guard('web')->user();

        abort_unless(
            $user && $this->isGlobalAdmin($user),
            403,
            'You do not have permission to access this panel.'
        );

        return $next($request);
    }

    private function isGlobalAdmin(User $user): bool
    {
        return Feature::for($user)->active(FeatureFlag::GlobalAdmin->value);
    }
}
