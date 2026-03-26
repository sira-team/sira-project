<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Camp\Http\Controllers\CampVisitorController;

Route::get('/{tenant:slug}/camps/{camp}/register', [CampVisitorController::class, 'show'])
    ->name('camp.register.show');

Route::post('/{tenant:slug}/camps/{camp}/register', [CampVisitorController::class, 'store'])
    ->name('camp.register.store');
