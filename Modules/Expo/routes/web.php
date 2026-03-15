<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Expo\Http\Controllers\ExpoRequestController;

// Public form for expo requests
Route::prefix('{tenant:slug}/expo')->group(function () {
    Route::get('/request', [ExpoRequestController::class, 'form'])->name('expo.request.form');
    Route::post('/request', [ExpoRequestController::class, 'store'])->name('expo.request.store');
});

// Authenticated routes
use Modules\Expo\Http\Controllers\DigitalMaterialController;

Route::middleware('auth')->group(function () {
    Route::get('{tenant:slug}/expo/stations/{station}/materials/{material}/download', [DigitalMaterialController::class, 'download'])
        ->name('expo.material.download');
});
