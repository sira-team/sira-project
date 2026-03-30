<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademyLevel;

/**
 * @extends Factory<AcademyLevel>
 */
final class AcademyLevelFactory extends Factory
{
    protected $model = AcademyLevel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Level '.fake()->numberBetween(1, 10),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
