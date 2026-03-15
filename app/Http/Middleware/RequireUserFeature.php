<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Feature;
use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature as PennantFeature;

class RequireUserFeature
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        $featureEnum = Feature::from($feature);

        abort_unless(
            PennantFeature::for($request->user())->active($featureEnum->value),
            403,
            'You do not have access to this panel.'
        );

        return $next($request);
    }
}
