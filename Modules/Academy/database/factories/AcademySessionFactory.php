<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Academy\Models\AcademyLevel;
use Modules\Academy\Models\AcademySession;

final class AcademySessionFactory extends Factory
{
    protected $model = AcademySession::class;

    public function definition(): array
    {
        return [
            'academy_level_id' => AcademyLevel::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
