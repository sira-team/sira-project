<?php

declare(strict_types=1);

use Modules\Academy\Models\AcademySession;
use Modules\Academy\Models\Quiz;

describe('Quiz model', function () {
    it('belongs to a session', function () {
        $session = AcademySession::factory()->create();
        $quiz = Quiz::factory()->create(['academy_session_id' => $session->id]);
        expect($quiz->session->id)->toBe($session->id);
    });

    it('has default values', function () {
        $quiz = Quiz::factory()->create();
        expect($quiz->max_attempts)->toBe(3);
        expect($quiz->min_days_between_attempts)->toBe(7);
        expect($quiz->passing_score_percent)->toBe(70);
    });

    it('can have custom attempt and passing score settings', function () {
        $quiz = Quiz::factory()->create([
            'max_attempts' => 5,
            'min_days_between_attempts' => 14,
            'passing_score_percent' => 80,
        ]);
        expect($quiz->max_attempts)->toBe(5);
        expect($quiz->min_days_between_attempts)->toBe(14);
        expect($quiz->passing_score_percent)->toBe(80);
    });
});
