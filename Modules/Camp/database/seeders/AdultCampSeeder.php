<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampContract;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Models\Hostel;

final class AdultCampSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();
        $user = User::where('tenant_id', $tenant->id)->firstOrFail();
        $altenberg = Hostel::firstWhere('name', 'Jugendherberge Altenberg');

        // Adult camp — registration open
        $adultCamp = Camp::firstOrCreate(
            ['name' => 'Erwachsenenseminar 2026', 'tenant_id' => $tenant->id],
            [
                'starts_at' => now()->addMonths(3)->next('Friday')->format('Y-m-d'),
                'ends_at' => now()->addMonths(3)->next('Friday')->addDays(2)->format('Y-m-d'),
                'price_per_participant' => 150.00,
                'target_group' => CampTargetGroup::Adults,
                'gender_policy' => CampGenderPolicy::All,
                'description' => 'Fortbildungsseminar für Erwachsene. Thema: Moderne Pädagogik.',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($altenberg && ! CampContract::where('camp_id', $adultCamp->id)->exists()) {
            CampContract::create([
                'camp_id' => $adultCamp->id,
                'hostel_id' => $altenberg->id,
                'price_per_person_per_night' => 42.00,
                'has_catering' => true,
                'contracted_beds' => 30,
                'contract_date' => now()->subWeeks(2)->format('Y-m-d'),
                'notes' => 'Stornierung bis 2 Wochen vor Beginn kostenfrei.',
            ]);
        }

        $this->seedAdults($adultCamp);
    }

    private function seedAdults(Camp $camp): void
    {
        if (CampVisitor::where('camp_id', $camp->id)->exists()) {
            return;
        }

        $entries = [
            ['name' => 'Dr. Ibrahim Nawaz', 'gender' => 'male', 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '1978-03-12'],
            ['name' => 'Fatima Al-Qadi', 'gender' => 'female', 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '1982-07-25'],
            ['name' => 'Hassan Mahmud', 'gender' => 'male', 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '1975-11-08'],
            ['name' => 'Layla Amr', 'gender' => 'female', 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '1985-01-15'],
            ['name' => 'Tariq Salim', 'gender' => 'male', 'status' => VisitorStatus::Pending, 'date_of_birth' => '1980-06-20'],
            ['name' => 'Amina Hassan', 'gender' => 'female', 'status' => VisitorStatus::Pending, 'date_of_birth' => '1983-09-10'],
            ['name' => 'Samir Khalil', 'gender' => 'male', 'status' => VisitorStatus::Waitlisted, 'date_of_birth' => '1976-04-30'],
        ];

        $waitlistPosition = 1;

        foreach ($entries as $data) {
            $email = mb_strtolower(str_replace(' ', '.', $data['name'])).'@example.com';

            $visitor = Visitor::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'phone' => fake()->phoneNumber(),
                    'gender' => $data['gender'],
                    'date_of_birth' => $data['date_of_birth'],
                    'allergies' => fake()->randomElement([null, 'Peanuts', 'Gluten', 'Dairy']),
                    'medications' => fake()->randomElement([null, 'Aspirin', 'Diabetes medication']),
                ],
            );

            $isWaitlisted = $data['status'] === VisitorStatus::Waitlisted;

            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => $data['status'],
                'registered_at' => now(),
                'waitlist_position' => $isWaitlisted ? $waitlistPosition++ : null,
            ]);
        }
    }
}
