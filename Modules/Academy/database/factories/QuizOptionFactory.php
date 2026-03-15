<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\QuizOption;
use Modules\Academy\Models\QuizQuestion;

/**
 * @extends Factory<QuizOption>
 */
final class QuizOptionFactory extends Factory
{
    protected $model = QuizOption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_question_id' => QuizQuestion::factory(),
            'text' => fake()->words(3, true),
            'is_correct' => false,
        ];
    }

    public function correct(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_correct' => true,
        ]);
    }
}
