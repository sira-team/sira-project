<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Academy\Http\Controllers\AcademyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('academies', AcademyController::class)->names('academy');
});
