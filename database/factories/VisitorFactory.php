<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Visitor>
 */
final class VisitorFactory extends Factory
{
    protected $model = Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(Gender::cases()),
        ];
    }

    public function child(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
        ]);
    }

    public function withParent(?Visitor $parent = null): static
    {
        return $this->afterCreating(function (Visitor $child) use ($parent): void {
            $parent ??= Visitor::factory()->create();

            $child->guardians()->attach($parent, [
                'relationship' => $child->gender === Gender::Male ? 'father' : 'mother',
            ]);
        });
    }
}
