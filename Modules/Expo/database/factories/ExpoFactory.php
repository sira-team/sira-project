<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expo\Enums\ExpoStatus;
use Modules\Expo\Models\Expo;
use Modules\Expo\Models\ExpoRequest;

/**
 * @extends Factory<Expo>
 */
final class ExpoFactory extends Factory
{
    protected $model = Expo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'expo_request_id' => null,
            'name' => fake()->words(3, true).' Expo',
            'location_name' => fake()->building(),
            'location_address' => fake()->address(),
            'date' => fake()->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
            'status' => ExpoStatus::Planned,
            'notes' => fake()->sentence(),
        ];
    }

    public function fromRequest(ExpoRequest $request): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $request->tenant_id,
            'expo_request_id' => $request->id,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExpoStatus::Completed,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExpoStatus::Cancelled,
        ]);
    }
}
