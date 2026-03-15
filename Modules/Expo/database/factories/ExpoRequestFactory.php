<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expo\Enums\ExpoRequestStatus;
use Modules\Expo\Models\ExpoRequest;

/**
 * @extends Factory<ExpoRequest>
 */
final class ExpoRequestFactory extends Factory
{
    protected $model = ExpoRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'contact_name' => fake()->name(),
            'organisation' => fake()->company(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'preferred_date_from' => fake()->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d'),
            'preferred_date_to' => fake()->dateTimeBetween('+4 months', '+6 months')->format('Y-m-d'),
            'expected_visitors' => fake()->numberBetween(50, 500),
            'message' => fake()->sentence(),
            'status' => ExpoRequestStatus::New,
        ];
    }

    public function inReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExpoRequestStatus::InReview,
        ]);
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExpoRequestStatus::Accepted,
        ]);
    }

    public function declined(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExpoRequestStatus::Declined,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExpoRequestStatus::Completed,
        ]);
    }
}
