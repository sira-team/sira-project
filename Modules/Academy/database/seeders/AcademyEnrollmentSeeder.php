<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Academy\Models\AcademyEnrollment;
use Modules\Academy\Models\AcademyLevel;
use Modules\Academy\Models\AcademySession;
use Modules\Academy\Models\AcademySessionTicket;

final class AcademyEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();
        $academyManager = User::firstWhere('email', 'academy@example.com');

        $level1 = AcademyLevel::where('sort_order', 1)->first();

        if (! $level1 || ! $academyManager) {
            return;
        }

        // Enroll the academy manager themselves
        $enrollment = AcademyEnrollment::firstOrCreate(
            ['user_id' => $academyManager->id, 'tenant_id' => $tenant->id],
            [
                'academy_level_id' => $level1->id,
                'started_at' => now()->subMonths(4)->format('Y-m-d'),
                'tenant_id' => $tenant->id,
            ]
        );

        // Issue tickets for the first 2 sessions (attended)
        $sessions = AcademySession::where('academy_level_id', $level1->id)
            ->orderBy('sort_order')
            ->take(2)
            ->get();

        foreach ($sessions as $index => $session) {
            AcademySessionTicket::firstOrCreate(
                ['academy_enrollment_id' => $enrollment->id, 'academy_session_id' => $session->id],
                [
                    'issued_by' => $academyManager->id,
                    'issued_at' => now()->subMonths(3 - $index)->format('Y-m-d'),
                    'code' => Str::uuid()->toString(),
                ]
            );
        }
    }
}
