<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Academy\Livewire\TakeQuiz;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/quiz/{quiz}/take', TakeQuiz::class)->name('academy.quiz.take');
});
