<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Expo\Enums\DigitalMaterialType;
use Modules\Expo\Models\Station;
use Modules\Expo\Models\StationDigitalMaterial;

/**
 * @extends Factory<StationDigitalMaterial>
 */
final class StationDigitalMaterialFactory extends Factory
{
    protected $model = StationDigitalMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'station_id' => Station::factory(),
            'title' => fake()->words(3, true),
            'file_path' => 'expo/materials/'.fake()->uuid().'.pdf',
            'file_type' => fake()->randomElement(DigitalMaterialType::cases()),
            'language' => 'de',
            'uploaded_by' => User::factory(),
            'file_size_kb' => fake()->numberBetween(100, 10000),
        ];
    }
}
