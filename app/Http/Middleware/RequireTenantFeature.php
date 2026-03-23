<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FeatureFlag;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature as PennantFeature;

final class RequireTenantFeature
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        $featureEnum = FeatureFlag::from($feature);
        $tenant = Filament::getTenant();

        abort_unless(
            $tenant && PennantFeature::for($tenant)->active($featureEnum->value),
            403,
            'Your tenant does not have access to this module.'
        );

        return $next($request);
    }
}
