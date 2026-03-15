<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademySession;
use Modules\Academy\Models\Quiz;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'academy_session_id' => AcademySession::factory(),
            'title' => $this->faker->sentence(4),
            'max_attempts' => 3,
            'min_days_between_attempts' => 7,
            'passing_score_percent' => 70,
        ];
    }
}
