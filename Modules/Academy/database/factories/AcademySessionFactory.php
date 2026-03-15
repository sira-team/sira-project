<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademyLevel;
use Modules\Academy\Models\AcademySession;

/**
 * @extends Factory<AcademySession>
 */
final class AcademySessionFactory extends Factory
{
    protected $model = AcademySession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'academy_level_id' => AcademyLevel::factory(),
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 50),
        ];
    }
}
