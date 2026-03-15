<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Feature;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature as PennantFeature;

class RequireTenantFeature
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        $featureEnum = Feature::from($feature);
        $tenant = app(Tenant::class);

        abort_unless(
            PennantFeature::for($tenant)->active($featureEnum->value),
            403,
            'Your tenant does not have access to this module.'
        );

        return $next($request);
    }
}
