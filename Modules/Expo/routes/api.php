<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Expo\Http\Controllers\ExpoController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('expos', ExpoController::class)->names('expo');
});
