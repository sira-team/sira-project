<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Feature;
use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature as PennantFeature;

class RequireTeamFeature
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        $featureEnum = Feature::from($feature);
        $team = app(Team::class);

        abort_unless(
            PennantFeature::for($team)->active($featureEnum->value),
            403,
            'Your team does not have access to this module.'
        );

        return $next($request);
    }
}
