<?php

declare(strict_types=1);

use App\Http\Middleware\RequireTeamFeature;
use App\Http\Middleware\RequireUserFeature;
use App\Http\Middleware\ResolveTenantFromSubdomain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(ResolveTenantFromSubdomain::class);

        $middleware->alias([
            'team.feature' => RequireTeamFeature::class,
            'user.feature' => RequireUserFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
