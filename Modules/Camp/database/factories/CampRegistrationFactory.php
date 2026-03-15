<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use App\Models\Participant;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Enums\CampPaymentStatus;
use Modules\Camp\Enums\CampRegistrationStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampRegistration;

/**
 * @extends Factory<CampRegistration>
 */
final class CampRegistrationFactory extends Factory
{
    protected $model = CampRegistration::class;

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
            'participant_id' => Participant::factory(),
            'status' => CampRegistrationStatus::Pending,
            'payment_status' => CampPaymentStatus::Pending,
            'registered_at' => now(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampRegistrationStatus::Confirmed,
            'confirmed_at' => now(),
        ]);
    }

    public function waitlisted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampRegistrationStatus::Waitlisted,
            'waitlist_position' => 1,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampRegistrationStatus::Cancelled,
            'cancelled_at' => now(),
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => CampPaymentStatus::Paid,
        ]);
    }
}
