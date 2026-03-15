<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expo\Models\Station;

/**
 * @extends Factory<Station>
 */
final class StationFactory extends Factory
{
    protected $model = Station::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => 'Station '.fake()->numberBetween(1, 20),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }
}
