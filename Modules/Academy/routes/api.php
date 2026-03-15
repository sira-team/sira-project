<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Academy\Http\Controllers\AcademyController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('academies', AcademyController::class)->names('academy');
});
