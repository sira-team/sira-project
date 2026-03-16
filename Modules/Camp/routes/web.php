<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Camp\Http\Controllers\CampRegistrationController;

Route::get('/{tenant:slug}/camps/{camp}/register', [CampRegistrationController::class, 'show'])
    ->name('camp.register.show');

Route::post('/{tenant:slug}/camps/{camp}/register', [CampRegistrationController::class, 'store'])
    ->name('camp.register.store');
