<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expo\Enums\PhysicalMaterialType;
use Modules\Expo\Models\Station;
use Modules\Expo\Models\StationPhysicalMaterial;

/**
 * @extends Factory<StationPhysicalMaterial>
 */
final class StationPhysicalMaterialFactory extends Factory
{
    protected $model = StationPhysicalMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'station_id' => Station::factory(),
            'type' => fake()->randomElement(PhysicalMaterialType::cases()),
            'name' => fake()->words(3, true),
            'notes' => fake()->sentence(),
        ];
    }
}
