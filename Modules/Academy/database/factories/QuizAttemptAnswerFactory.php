<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\QuizAttempt;
use Modules\Academy\Models\QuizAttemptAnswer;
use Modules\Academy\Models\QuizQuestion;

final class QuizAttemptAnswerFactory extends Factory
{
    protected $model = QuizAttemptAnswer::class;

    public function definition(): array
    {
        return [
            'quiz_attempt_id' => QuizAttempt::factory(),
            'quiz_question_id' => QuizQuestion::factory(),
            'selected_options' => [],
        ];
    }
}
