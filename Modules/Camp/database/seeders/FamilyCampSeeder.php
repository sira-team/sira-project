<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Seeders;

use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampContract;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Models\Hostel;

final class FamilyCampSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();
        $bonn = Hostel::firstWhere('name', 'Jugendherberge Bonn Venusberg');

        // Family camp — registration open
        $familyCamp = Camp::firstOrCreate(
            ['name' => 'Familiencamp 2026', 'tenant_id' => $tenant->id],
            [
                'starts_at' => now()->addMonths(4)->next('Saturday')->format('Y-m-d'),
                'ends_at' => now()->addMonths(4)->next('Saturday')->addDays(6)->format('Y-m-d'),
                'price_per_participant' => 85.00,
                'target_group' => CampTargetGroup::Family,
                'gender_policy' => CampGenderPolicy::All,
                'description' => 'Ein Lager für Familien mit Kindern. Gemeinsame Aktivitäten und Entspannung.',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($bonn && ! CampContract::where('camp_id', $familyCamp->id)->exists()) {
            CampContract::create([
                'camp_id' => $familyCamp->id,
                'hostel_id' => $bonn->id,
                'price_per_person_per_night' => 32.00,
                'has_catering' => true,
                'contracted_beds' => 60,
                'contract_date' => now()->subWeeks(1)->format('Y-m-d'),
                'notes' => 'Familienfreundliche Ausstattung. Kinderbetten verfügbar.',
            ]);
        }

        $this->seedFamilies($familyCamp);
    }

    private function seedFamilies(Camp $camp): void
    {
        if (CampVisitor::where('camp_id', $camp->id)->exists()) {
            return;
        }

        // Family 1: Mohammed & Zainab with 2 children
        $parent1 = Visitor::factory()->create([
            'name' => 'Mohammed Samir',
            'email' => 'mohammed.samir@example.com',
            'phone' => fake()->phoneNumber(),
            'gender' => 'male',
            'date_of_birth' => '1980-05-10',
        ]);

        $child1a = Visitor::factory()->child()->withParent($parent1)->create([
            'name' => 'Layla Samir',
            'gender' => 'female',
            'date_of_birth' => '2012-08-15',
        ]);

        $child1b = Visitor::factory()->child()->withParent($parent1)->create([
            'name' => 'Karim Samir',
            'gender' => 'male',
            'date_of_birth' => '2015-03-20',
        ]);

        foreach ([$parent1, $child1a, $child1b] as $visitor) {
            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => VisitorStatus::Confirmed,
                'registered_at' => now()->subDays(5),
            ]);
        }

        // Family 2: Fatima (single parent) with 1 child
        $parent2 = Visitor::factory()->create([
            'name' => 'Fatima El-Sayed',
            'email' => 'fatima.elsayed@example.com',
            'phone' => fake()->phoneNumber(),
            'gender' => 'female',
            'date_of_birth' => '1985-12-03',
        ]);

        $child2 = Visitor::factory()->child()->withParent($parent2)->create([
            'name' => 'Ahmed El-Sayed',
            'gender' => 'male',
            'date_of_birth' => '2016-11-10',
        ]);

        foreach ([$parent2, $child2] as $visitor) {
            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => VisitorStatus::Confirmed,
                'registered_at' => now()->subDays(4),
            ]);
        }

        // Family 3: Hassan & Aisha with 3 children
        $parent3 = Visitor::factory()->create([
            'name' => 'Hassan Rashid',
            'email' => 'hassan.rashid@example.com',
            'phone' => fake()->phoneNumber(),
            'gender' => 'male',
            'date_of_birth' => '1978-07-22',
        ]);

        $child3a = Visitor::factory()->child()->withParent($parent3)->create([
            'name' => 'Noor Rashid',
            'gender' => 'female',
            'date_of_birth' => '2010-02-14',
        ]);

        $child3b = Visitor::factory()->child()->withParent($parent3)->create([
            'name' => 'Ibrahim Rashid',
            'gender' => 'male',
            'date_of_birth' => '2013-06-25',
        ]);

        $child3c = Visitor::factory()->child()->withParent($parent3)->create([
            'name' => 'Maryam Rashid',
            'gender' => 'female',
            'date_of_birth' => '2018-01-08',
        ]);

        foreach ([$parent3, $child3a, $child3b, $child3c] as $visitor) {
            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => VisitorStatus::Confirmed,
                'registered_at' => now()->subDays(3),
            ]);
        }

        // Family 4: Nur with 2 children — pending
        $parent4 = Visitor::factory()->create([
            'name' => 'Nur Khan',
            'email' => 'nur.khan@example.com',
            'phone' => fake()->phoneNumber(),
            'gender' => 'female',
            'date_of_birth' => '1988-09-17',
        ]);

        $child4a = Visitor::factory()->child()->withParent($parent4)->create([
            'name' => 'Amira Khan',
            'gender' => 'female',
            'date_of_birth' => '2014-05-09',
        ]);

        $child4b = Visitor::factory()->child()->withParent($parent4)->create([
            'name' => 'Malik Khan',
            'gender' => 'male',
            'date_of_birth' => '2017-10-12',
        ]);

        foreach ([$parent4, $child4a, $child4b] as $visitor) {
            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => VisitorStatus::Pending,
                'registered_at' => now()->subDays(1),
            ]);
        }

        // Family 5: Youssef & Leila with 1 child — waitlisted
        $parent5 = Visitor::factory()->create([
            'name' => 'Youssef Tahir',
            'email' => 'youssef.tahir@example.com',
            'phone' => fake()->phoneNumber(),
            'gender' => 'male',
            'date_of_birth' => '1982-04-30',
        ]);

        $child5 = Visitor::factory()->child()->withParent($parent5)->create([
            'name' => 'Zainab Tahir',
            'gender' => 'female',
            'date_of_birth' => '2011-12-28',
        ]);

        foreach ([$parent5, $child5] as $index => $visitor) {
            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => VisitorStatus::Waitlisted,
                'waitlist_position' => $index === 0 ? 1 : 2,
                'registered_at' => now(),
            ]);
        }
    }
}
