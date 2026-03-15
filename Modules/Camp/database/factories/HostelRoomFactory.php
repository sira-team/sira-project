<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Enums\RoomGender;
use Modules\Camp\Models\Hostel;
use Modules\Camp\Models\HostelRoom;

/**
 * @extends Factory<HostelRoom>
 */
final class HostelRoomFactory extends Factory
{
    protected $model = HostelRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hostel_id' => Hostel::factory(),
            'name' => 'Zimmer '.fake()->numberBetween(1, 50),
            'capacity' => fake()->numberBetween(2, 10),
            'gender' => fake()->randomElement(RoomGender::cases()),
        ];
    }
}
