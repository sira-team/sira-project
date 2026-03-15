<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tenant>
 */
final class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->city().' e.V.';

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'city' => fake()->city(),
            'country' => 'DE',
            'email' => fake()->companyEmail(),
        ];
    }
}
