<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Enums\QuizQuestionType;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Models\QuizQuestion;

/**
 * @extends Factory<QuizQuestion>
 */
final class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'question' => fake()->sentence().'?',
            'type' => QuizQuestionType::MultipleChoice,
            'sort_order' => fake()->numberBetween(1, 50),
        ];
    }
}
