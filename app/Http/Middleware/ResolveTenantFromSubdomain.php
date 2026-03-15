<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantFromSubdomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);

        if (count($parts) < 3) {
            return $next($request);
        }

        $slug = $parts[0];

        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        app()->instance(Tenant::class, $tenant);
        app()->instance('current_tenant', $tenant);

        setPermissionsTenantId($tenant->id);

        return $next($request);
    }
}
