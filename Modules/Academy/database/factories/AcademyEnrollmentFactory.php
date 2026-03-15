<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademyEnrollment;
use Modules\Academy\Models\AcademyLevel;

final class AcademyEnrollmentFactory extends Factory
{
    protected $model = AcademyEnrollment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tenant_id' => 1,
            'academy_level_id' => AcademyLevel::factory(),
            'started_at' => now(),
        ];
    }
}
