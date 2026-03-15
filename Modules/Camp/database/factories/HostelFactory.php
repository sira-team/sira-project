<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Models\Hostel;

/**
 * @extends Factory<Hostel>
 */
final class HostelFactory extends Factory
{
    protected $model = Hostel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Hostel',
            'address' => fake()->address(),
            'city' => fake()->city(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'website' => fake()->url(),
            'notes' => fake()->sentence(),
        ];
    }
}
