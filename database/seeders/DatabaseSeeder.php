<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Academy\Database\Seeders\AcademyDatabaseSeeder;
use Modules\Camp\Database\Seeders\CampDatabaseSeeder;
use Modules\Expo\Database\Seeders\ExpoDatabaseSeeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            CampDatabaseSeeder::class,
            ExpoDatabaseSeeder::class,
            AcademyDatabaseSeeder::class,
        ]);
    }
}
