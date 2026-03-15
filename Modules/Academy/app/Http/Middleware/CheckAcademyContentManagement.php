<?php

declare(strict_types=1);

namespace Modules\Academy\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use Symfony\Component\HttpFoundation\Response;

class CheckAcademyContentManagement
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(Feature::for($request->user())->active('academy-content-management'), 403);

        return $next($request);
    }
}
