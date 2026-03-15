<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Academy\Models\AcademyEnrollment;
use Modules\Academy\Models\AcademySession;
use Modules\Academy\Models\AcademySessionTicket;

/**
 * @extends Factory<AcademySessionTicket>
 */
final class AcademySessionTicketFactory extends Factory
{
    protected $model = AcademySessionTicket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academy_enrollment_id' => AcademyEnrollment::factory(),
            'academy_session_id' => AcademySession::factory(),
            'issued_by' => User::factory(),
            'issued_at' => now(),
            'code' => Str::uuid()->toString(),
        ];
    }
}
