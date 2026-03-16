<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Modules\Expo\Enums\PhysicalMaterialType;
use Modules\Expo\Models\Station;
use Modules\Expo\Models\StationPhysicalMaterial;

final class StationSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();

        foreach (self::stations() as $definition) {
            $station = Station::firstOrCreate(
                ['name' => $definition['name'], 'tenant_id' => $tenant->id],
                [
                    'description' => $definition['description'],
                    'sort_order' => $definition['sort_order'],
                ]
            );

            foreach ($definition['materials'] as $material) {
                StationPhysicalMaterial::firstOrCreate(
                    ['station_id' => $station->id, 'name' => $material['name']],
                    [
                        'type' => $material['type'],
                        'notes' => $material['notes'],
                    ]
                );
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function stations(): array
    {
        return [
            [
                'name' => 'Station 1 – Die Offenbarung',
                'description' => 'Der Beginn der Offenbarung an den Propheten Muhammad ﷺ in der Höhle Hira.',
                'sort_order' => 1,
                'materials' => [
                    ['type' => PhysicalMaterialType::Poster, 'name' => 'Karte der arabischen Halbinsel', 'notes' => 'Laminiert, A1'],
                    ['type' => PhysicalMaterialType::Miniature, 'name' => 'Modell der Höhle Hira', 'notes' => null],
                ],
            ],
            [
                'name' => 'Station 2 – Das Leben des Propheten ﷺ',
                'description' => 'Überblick über die Biographie des Propheten Muhammad ﷺ von der Geburt bis zum Tod.',
                'sort_order' => 2,
                'materials' => [
                    ['type' => PhysicalMaterialType::Poster, 'name' => 'Zeitstrahl Sira', 'notes' => 'Beidseitig bedruckt'],
                    ['type' => PhysicalMaterialType::VideoScreen, 'name' => 'Monitor 32"', 'notes' => 'Mit Standfuß'],
                ],
            ],
            [
                'name' => 'Station 3 – Die Hidschra',
                'description' => 'Die Auswanderung des Propheten ﷺ von Mekka nach Madinah und ihre Bedeutung.',
                'sort_order' => 3,
                'materials' => [
                    ['type' => PhysicalMaterialType::Poster, 'name' => 'Karte der Hidschra-Route', 'notes' => null],
                    ['type' => PhysicalMaterialType::Poster, 'name' => 'Infotafel Madinah', 'notes' => null],
                ],
            ],
            [
                'name' => 'Station 4 – Die fünf Säulen des Islam',
                'description' => 'Erklärung der fünf Pflichten eines Muslims: Schahada, Salat, Zakat, Sawm, Hadsch.',
                'sort_order' => 4,
                'materials' => [
                    ['type' => PhysicalMaterialType::Poster, 'name' => 'Infografik 5 Säulen', 'notes' => 'Für Kinder geeignet'],
                    ['type' => PhysicalMaterialType::Miniature, 'name' => 'Gebetsmatte + Kompass', 'notes' => null],
                    ['type' => PhysicalMaterialType::Other, 'name' => 'Auslegware für Gebetsdemo', 'notes' => '2×3m'],
                ],
            ],
            [
                'name' => 'Station 5 – Islamische Wissenschaft und Kunst',
                'description' => 'Beiträge muslimischer Gelehrter zu Mathematik, Astronomie, Medizin und Architektur.',
                'sort_order' => 5,
                'materials' => [
                    ['type' => PhysicalMaterialType::Poster, 'name' => 'Gelehrte der islamischen Welt', 'notes' => null],
                    ['type' => PhysicalMaterialType::VideoScreen, 'name' => 'Tablet mit Slideshows', 'notes' => 'iPad + Ständer'],
                ],
            ],
            [
                'name' => 'Station 6 – Kinder-Quiz',
                'description' => 'Interaktive Quiz-Station für Kinder und Jugendliche.',
                'sort_order' => 6,
                'materials' => [
                    ['type' => PhysicalMaterialType::Other, 'name' => 'Fragekarten-Set', 'notes' => '60 Karten, laminiert'],
                    ['type' => PhysicalMaterialType::Other, 'name' => 'Stempelkarten', 'notes' => 'Für Kinder, 200 Stück'],
                ],
            ],
        ];
    }
}
