<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FeatureFlag;
use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature as PennantFeature;

final class RequireUserFeature
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        $featureEnum = FeatureFlag::from($feature);

        abort_unless(
            PennantFeature::for($request->user())->active($featureEnum->value),
            403,
            'You do not have access to this panel.'
        );

        return $next($request);
    }
}
