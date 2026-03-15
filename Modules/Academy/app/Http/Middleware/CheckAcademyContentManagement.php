<?php

declare(strict_types=1);

namespace Modules\Academy\Http\Middleware;

use App\Enums\FeatureFlag;
use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use Symfony\Component\HttpFoundation\Response;

final class CheckAcademyContentManagement
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(Feature::for($request->user())->active(FeatureFlag::AcademyContentManagement->value), 403);

        return $next($request);
    }
}
