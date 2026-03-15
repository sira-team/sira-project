<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireSuperAdmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        abort_unless(
            auth()->user()?->hasRole('super_admin'),
            403,
            'You do not have permission to access this panel.'
        );

        return $next($request);
    }
}
