<?php

declare(strict_types=1);

use App\Http\Controllers\AccountSetupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/setup-account/{user}', [AccountSetupController::class, 'show'])
    ->name('account.setup')
    ->middleware('signed');

Route::post('/setup-account/{user}', [AccountSetupController::class, 'store'])
    ->name('account.setup.store')
    ->middleware('signed');

Route::get('/app/join/{token}', function (string $token) {
    $invite = App\Models\TenantInviteLink::where('token', $token)
        ->valid()
        ->firstOrFail();

    session()->put('join_token', $token);
    session()->put('join_tenant_id', $invite->tenant_id);

    return redirect()->route('filament.app.auth.register');
})->name('app.join');
