<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;

/**
 * @extends Factory<CampVisitor>
 */
final class CampVisitorFactory extends Factory
{
    protected $model = CampVisitor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'camp_id' => Camp::factory(),
            'visitor_id' => Visitor::factory(),
            'status' => VisitorStatus::Pending,
            'price' => fake()->randomFloat(2, 80, 200),
            'registered_at' => now(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VisitorStatus::Confirmed,
        ]);
    }

    public function waitlisted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VisitorStatus::Waitlisted,
            'waitlist_position' => 1,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VisitorStatus::Cancelled,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VisitorStatus::Paid,
        ]);
    }
}
