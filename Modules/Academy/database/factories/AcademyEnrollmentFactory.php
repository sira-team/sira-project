<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademyEnrollment;
use Modules\Academy\Models\AcademyLevel;

/**
 * @extends Factory<AcademyEnrollment>
 */
final class AcademyEnrollmentFactory extends Factory
{
    protected $model = AcademyEnrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'academy_level_id' => AcademyLevel::factory(),
            'started_at' => now()->toDateString(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => now()->toDateString(),
        ]);
    }
}
