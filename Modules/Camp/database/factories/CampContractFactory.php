<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampContract;
use Modules\Camp\Models\Hostel;

/**
 * @extends Factory<CampContract>
 */
final class CampContractFactory extends Factory
{
    protected $model = CampContract::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'camp_id' => Camp::factory(),
            'hostel_id' => Hostel::factory(),
            'price_per_person_per_night' => fake()->randomFloat(2, 30, 60),
            'catering_included' => fake()->boolean(),
            'contracted_participants' => fake()->numberBetween(50, 150),
            'contracted_supporters' => fake()->numberBetween(10, 30),
            'contract_date' => fake()->date(),
            'notes' => fake()->sentence(),
        ];
    }
}
