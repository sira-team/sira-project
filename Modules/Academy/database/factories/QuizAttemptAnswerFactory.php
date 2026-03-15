<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\QuizAttempt;
use Modules\Academy\Models\QuizAttemptAnswer;
use Modules\Academy\Models\QuizOption;
use Modules\Academy\Models\QuizQuestion;

class QuizAttemptAnswerFactory extends Factory
{
    protected $model = QuizAttemptAnswer::class;

    public function definition(): array
    {
        return [
            'quiz_attempt_id' => QuizAttempt::factory(),
            'quiz_question_id' => QuizQuestion::factory(),
            'quiz_option_id' => QuizOption::factory(),
        ];
    }
}
