<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Models\Camp;

/**
 * @extends Factory<Camp>
 */
final class CampFactory extends Factory
{
    protected $model = Camp::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = Carbon::now()->next('Monday')->format('Y-m-d');

        return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->words(3, true).' Camp',
            'starts_at' => $startsAt,
            'ends_at' => Carbon::createFromFormat('Y-m-d', $startsAt)->addDays(5)->format('Y-m-d'),
            'target_group' => fake()->randomElement(CampTargetGroup::cases()),
            'gender_policy' => fake()->randomElement(CampGenderPolicy::cases()),
            'food_provided' => true,
            'participants_bring_food' => false,
            'registration_open' => true,
            'price_per_participant' => fake()->randomFloat(2, 80, 200),
        ];
    }

    public function closed(): CampFactory
    {
        return $this->state(fn (array $attributes) => [
            'registration_open' => false,
        ]);
    }
}
