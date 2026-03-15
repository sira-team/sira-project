<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Academy\Models\AcademyEnrollment;
use Modules\Academy\Models\AcademySession;
use Modules\Academy\Models\AcademySessionTicket;

class AcademySessionTicketFactory extends Factory
{
    protected $model = AcademySessionTicket::class;

    public function definition(): array
    {
        return [
            'academy_enrollment_id' => AcademyEnrollment::factory(),
            'academy_session_id' => AcademySession::factory(),
            'code' => Str::random(12),
            'issued_at' => now(),
        ];
    }
}
