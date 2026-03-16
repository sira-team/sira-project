<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Seeders;

use Illuminate\Database\Seeder;

final class ExpoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            StationSeeder::class,
            ExpoRequestSeeder::class,
            ExpoSeeder::class,
        ]);
    }
}
