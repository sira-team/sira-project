<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Seeders;

use Illuminate\Database\Seeder;

final class CampDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            HostelSeeder::class,
            CampSeeder::class,
        ]);
    }
}
