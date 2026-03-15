<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Camp\Http\Controllers\CampController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('camps', CampController::class)->names('camp');
});
