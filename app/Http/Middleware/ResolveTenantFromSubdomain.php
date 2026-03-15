<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Team;
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

        $team = Team::where('slug', $slug)->firstOrFail();

        app()->instance(Team::class, $team);
        app()->instance('current_team', $team);

        setPermissionsTeamId($team->id);

        return $next($request);
    }
}
