<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademySession;
use Modules\Academy\Models\Quiz;

/**
 * @extends Factory<Quiz>
 */
final class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academy_session_id' => AcademySession::factory(),
            'title' => fake()->words(3, true).' Quiz',
            'max_attempts' => 3,
            'min_days_between_attempts' => 7,
            'passing_score_percent' => 70,
        ];
    }
}
