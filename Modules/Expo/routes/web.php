<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Expo\Http\Controllers\ExpoController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('expos', ExpoController::class)->names('expo');
});
