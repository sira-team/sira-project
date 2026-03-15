<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademyEnrollment;
use Modules\Academy\Models\AcademySessionTicket;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Models\QuizAttempt;

final class QuizAttemptFactory extends Factory
{
    protected $model = QuizAttempt::class;

    public function definition(): array
    {
        return [
            'academy_enrollment_id' => AcademyEnrollment::factory(),
            'quiz_id' => Quiz::factory(),
            'academy_session_ticket_id' => AcademySessionTicket::factory(),
            'attempted_at' => now(),
            'score_percent' => $this->faker->numberBetween(0, 100),
            'passed' => $this->faker->boolean(),
        ];
    }
}
