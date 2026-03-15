<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Enums\QuizQuestionType;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Models\QuizQuestion;

class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'question_text' => $this->faker->sentence().'?',
            'type' => $this->faker->randomElement(QuizQuestionType::cases()),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
