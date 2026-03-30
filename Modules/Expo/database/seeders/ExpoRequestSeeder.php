<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Modules\Expo\Enums\ExpoRequestStatus;
use Modules\Expo\Models\ExpoRequest;

final class ExpoRequestSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();

        $requests = [
            [
                'contact_name' => 'Ibrahim Al-Farouqi',
                'organisation_name' => 'Masjid Al-Rahman Köln',
                'email' => 'info@masjid-alrahman-koeln.de',
                'phone' => '+49 221 98765432',
                'city' => 'Köln',
                'preferred_date_from' => now()->addMonths(2)->format('Y-m-d'),
                'preferred_date_to' => now()->addMonths(2)->addDays(1)->format('Y-m-d'),
                'message' => 'Wir planen eine islamische Ausstellung für unser Gemeindefest und würden uns sehr über eine Zusammenarbeit freuen.',
                'status' => ExpoRequestStatus::New,
            ],
            [
                'contact_name' => 'Fatima Benali',
                'organisation_name' => 'Islamisches Bildungszentrum Bonn',
                'email' => 'veranstaltungen@ibz-bonn.de',
                'phone' => '+49 228 11223344',
                'city' => 'Bonn',
                'preferred_date_from' => now()->addMonths(3)->format('Y-m-d'),
                'preferred_date_to' => now()->addMonths(3)->addDays(2)->format('Y-m-d'),
                'message' => 'Tag der offenen Moschee – wir erwarten rund 500 Besucher und benötigen interaktive Stationen für alle Altersgruppen.',
                'status' => ExpoRequestStatus::InReview,
            ],
            [
                'contact_name' => 'Yusuf Özdemir',
                'organisation_name' => 'Türkisch-Islamische Union Düsseldorf',
                'email' => 'y.ozdemir@ditib-duesseldorf.de',
                'phone' => '+49 211 55667788',
                'city' => 'Düsseldorf',
                'preferred_date_from' => now()->addMonths(1)->format('Y-m-d'),
                'preferred_date_to' => now()->addMonths(1)->addDays(1)->format('Y-m-d'),
                'message' => 'Schulprojekt in Zusammenarbeit mit einer Gesamtschule. Zielgruppe: Schülerinnen und Schüler der Klassen 7–10.',
                'status' => ExpoRequestStatus::Accepted,
            ],
            [
                'contact_name' => 'Amira Khalil',
                'organisation_name' => 'Interkulturelles Zentrum Aachen',
                'email' => 'kontakt@ikz-aachen.de',
                'phone' => null,
                'city' => 'Aachen',
                'preferred_date_from' => now()->subMonths(2)->format('Y-m-d'),
                'preferred_date_to' => now()->subMonths(2)->addDays(1)->format('Y-m-d'),
                'message' => 'Interreligiöser Dialog – abgeschlossene Veranstaltung.',
                'status' => ExpoRequestStatus::Completed,
            ],
        ];

        foreach ($requests as $data) {
            ExpoRequest::firstOrCreate(
                ['email' => $data['email'], 'tenant_id' => $tenant->id],
                array_merge($data, ['tenant_id' => $tenant->id])
            );
        }
    }
}
