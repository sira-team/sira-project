<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Participant;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
final class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'visitor_id' => Visitor::factory(),
            'name' => fake()->firstName(),
            'date_of_birth' => fake()->dateTimeBetween('-18 years', '-5 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(Gender::cases()),
            'is_self' => false,
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
        ];
    }

    public function self(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_self' => true,
        ]);
    }

    public function withMedicalInfo(): static
    {
        return $this->state(fn (array $attributes) => [
            'allergies' => fake()->sentence(),
            'medications' => fake()->sentence(),
            'medical_notes' => fake()->sentence(),
        ]);
    }
}
