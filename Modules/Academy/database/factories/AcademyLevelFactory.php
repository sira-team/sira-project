<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademyLevel;

class AcademyLevelFactory extends Factory
{
    protected $model = AcademyLevel::class;

    public function definition(): array
    {
        return [
            'title' => 'Level '.$this->faker->unique()->numberBetween(1, 10),
            'description' => $this->faker->optional()->paragraph(),
            'duration_months' => $this->faker->numberBetween(6, 24),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
