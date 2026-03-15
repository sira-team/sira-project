<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Camp\Http\Controllers\CampController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('camps', CampController::class)->names('camp');
});
