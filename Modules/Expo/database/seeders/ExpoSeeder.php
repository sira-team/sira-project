<?php

declare(strict_types=1);

namespace Modules\Expo\Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Expo\Enums\ExpoRequestStatus;
use Modules\Expo\Enums\ExpoStatus;
use Modules\Expo\Models\Expo;
use Modules\Expo\Models\ExpoRequest;
use Modules\Expo\Models\Station;

final class ExpoSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();
        $expoManager = User::firstWhere('email', 'expo@example.com');

        $stations = Station::where('tenant_id', $tenant->id)
            ->orderBy('sort_order')
            ->get();

        // Planned expo linked to the accepted request
        $acceptedRequest = ExpoRequest::where('tenant_id', $tenant->id)
            ->where('status', ExpoRequestStatus::Accepted)
            ->first();

        if ($acceptedRequest) {
            $plannedExpo = Expo::firstOrCreate(
                ['expo_request_id' => $acceptedRequest->id, 'tenant_id' => $tenant->id],
                [
                    'name' => 'Schulprojekt Düsseldorf '.now()->addMonths(1)->format('Y'),
                    'location_name' => 'Gesamtschule Düsseldorf-Mitte',
                    'location_address' => 'Schulstraße 12, 40210 Düsseldorf',
                    'date' => now()->addMonths(1)->format('Y-m-d'),
                    'status' => ExpoStatus::Planned,
                    'notes' => 'Klassenzimmer stehen ab 13:00 Uhr zur Verfügung. Aufbau ab 12:00.',
                    'tenant_id' => $tenant->id,
                ]
            );

            // Assign first 4 stations to this expo
            $assignedPlanned = DB::table('expo_stations')->where('expo_id', $plannedExpo->id)->pluck('station_id');

            foreach ($stations->take(4) as $index => $station) {
                if (! $assignedPlanned->contains($station->id)) {
                    $plannedExpo->stations()->attach($station->id, [
                        'sort_order' => $index + 1,
                        'responsible_user_id' => $expoManager?->id,
                    ]);
                }
            }
        }

        // Completed expo from the past (standalone, no request)
        $completedExpo = Expo::firstOrCreate(
            ['name' => 'Tag der offenen Moschee Aachen 2025', 'tenant_id' => $tenant->id],
            [
                'expo_request_id' => null,
                'location_name' => 'Bilal-Moschee Aachen',
                'location_address' => 'Adalbertsteinweg 50, 52070 Aachen',
                'date' => now()->subMonths(3)->format('Y-m-d'),
                'status' => ExpoStatus::Completed,
                'notes' => 'Ca. 180 Besucher. Sehr positives Feedback. Stationen 1–5 wurden aufgebaut.',
                'tenant_id' => $tenant->id,
            ]
        );

        $assignedCompleted = DB::table('expo_stations')->where('expo_id', $completedExpo->id)->pluck('station_id');

        foreach ($stations as $index => $station) {
            if (! $assignedCompleted->contains($station->id)) {
                $completedExpo->stations()->attach($station->id, [
                    'sort_order' => $index + 1,
                    'responsible_user_id' => $expoManager?->id,
                ]);
            }
        }
    }
}
