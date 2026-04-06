<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Camp\Http\Controllers\CampExpenseController;
use Modules\Camp\Http\Controllers\CampVisitorController;

Route::get('/{tenant:slug}/camps/{camp}/register', [CampVisitorController::class, 'show'])
    ->name('camp.register.show');

Route::post('/{tenant:slug}/camps/{camp}/register', [CampVisitorController::class, 'store'])
    ->name('camp.register.store');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/camp-expenses/{expense}/receipt/download', [CampExpenseController::class, 'downloadReceipt'])
        ->name('camp-expense.download-receipt');
});
