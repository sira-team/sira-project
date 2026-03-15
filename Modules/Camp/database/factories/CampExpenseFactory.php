<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Enums\CampExpenseCategory;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampExpense;

/**
 * @extends Factory<CampExpense>
 */
final class CampExpenseFactory extends Factory
{
    protected $model = CampExpense::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'camp_id' => Camp::factory(),
            'category' => fake()->randomElement(CampExpenseCategory::cases()),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'amount' => fake()->randomFloat(2, 50, 1000),
        ];
    }
}
