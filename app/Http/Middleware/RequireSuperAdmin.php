<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class RequireSuperAdmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user();

        abort_unless(
            $user && $this->isSuperAdmin($user),
            403,
            'You do not have permission to access this panel.'
        );

        return $next($request);
    }

    private function isSuperAdmin($user): bool
    {
        // Check if user has super_admin role in any tenant context
        return $user->roles()
            ->where('name', 'super_admin')
            ->exists();
    }
}
