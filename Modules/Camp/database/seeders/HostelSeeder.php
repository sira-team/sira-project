<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Camp\Models\Hostel;
use Modules\Camp\Models\HostelRoom;

final class HostelSeeder extends Seeder
{
    public function run(): void
    {
        $hostel = Hostel::firstOrCreate(
            ['name' => 'Jugendherberge Altenberg'],
            [
                'address' => 'Jugendherberge 1',
                'city' => 'Odenthal',
                'phone' => '+49 2202 92330',
                'email' => 'altenberg@jugendherberge.de',
                'website' => 'https://www.jugendherberge.de/jugendherbergen/altenberg/',
                'notes' => 'Großes Gelände mit Außenbereich. Hallenbad vorhanden. Küche kann für Eigenversorgung genutzt werden.',
            ]
        );

        $this->seedRooms($hostel, [
            ['name' => 'Raum Rhein', 'floor' => 1, 'capacity' => 10],
            ['name' => 'Raum Mosel', 'floor' => 1, 'capacity' => 10],
            ['name' => 'Raum Lahn', 'floor' => 2, 'capacity' => 8],
            ['name' => 'Raum Sieg', 'floor' => 2, 'capacity' => 8],
            ['name' => 'Raum Ruhr', 'floor' => 3, 'capacity' => 12],
            ['name' => 'Raum Ahr', 'floor' => 3, 'capacity' => 6],
        ]);

        // Second hostel for variety
        $hostel2 = Hostel::firstOrCreate(
            ['name' => 'Jugendherberge Bonn Venusberg'],
            [
                'address' => 'Haager Weg 42',
                'city' => 'Bonn',
                'phone' => '+49 228 2897970',
                'email' => 'bonn-venusberg@jugendherberge.de',
                'website' => null,
                'notes' => 'Ruhige Lage am Stadtrand. Gut erreichbar mit ÖPNV.',
            ]
        );

        $this->seedRooms($hostel2, [
            ['name' => 'Raum Beethoven', 'floor' => 1, 'capacity' => 8],
            ['name' => 'Raum Schumann', 'floor' => 1, 'capacity' => 8],
            ['name' => 'Raum Brahms', 'floor' => 2, 'capacity' => 10],
            ['name' => 'Raum Schubert', 'floor' => 2, 'capacity' => 10],
        ]);
    }

    /** @param array<int, array<string, mixed>> $rooms */
    private function seedRooms(Hostel $hostel, array $rooms): void
    {
        foreach ($rooms as $room) {
            HostelRoom::firstOrCreate(
                ['hostel_id' => $hostel->id, 'name' => $room['name']],
                [
                    'floor' => $room['floor'],
                    'capacity' => $room['capacity'],
                ]
            );
        }
    }
}
