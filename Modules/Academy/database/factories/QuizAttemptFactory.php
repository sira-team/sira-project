<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Models\QuizAttempt;

/**
 * @extends Factory<QuizAttempt>
 */
final class QuizAttemptFactory extends Factory
{
    protected $model = QuizAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'attempt_number' => 1,
            'started_at' => now(),
        ];
    }

    public function completed(bool $passed = true, int $score = 80): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => now(),
            'score_percent' => $score,
            'passed' => $passed,
        ]);
    }

    public function failed(int $score = 40): static
    {
        return $this->completed(false, $score);
    }
}
