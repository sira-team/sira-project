<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Seeders;

use App\Enums\Gender;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Modules\Camp\Enums\CampExpenseCategory;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampContract;
use Modules\Camp\Models\CampExpense;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Models\Hostel;

final class TeenageCampSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();
        $user = User::where('tenant_id', $tenant->id)->firstOrFail();
        $altenberg = Hostel::firstWhere('name', 'Jugendherberge Altenberg');

        // Teenager camp — registration open
        $teenageCamp = Camp::firstOrCreate(
            ['name' => 'Jugendcamp 2026', 'tenant_id' => $tenant->id],
            [
                'starts_at' => now()->addMonths(2)->next('Friday')->format('Y-m-d'),
                'ends_at' => now()->addMonths(2)->next('Friday')->addDays(4)->format('Y-m-d'),
                'price_per_participant' => 110.00,
                'target_group' => CampTargetGroup::Teenagers,
                'gender_policy' => CampGenderPolicy::All,
                'description' => 'Sommercamp für Jugendliche mit Fokus auf Aktivitäten und Austausch.',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($altenberg && ! CampContract::where('camp_id', $teenageCamp->id)->exists()) {
            CampContract::create([
                'camp_id' => $teenageCamp->id,
                'hostel_id' => $altenberg->id,
                'price_per_person_per_night' => 36.00,
                'has_catering' => true,
                'contracted_beds' => 50,
                'contract_date' => now()->subWeeks(3)->format('Y-m-d'),
                'notes' => 'Stornierung bis 4 Wochen vor Beginn kostenfrei.',
            ]);
        }

        $this->seedExpenses($teenageCamp, $user);
        $this->seedVisitors($teenageCamp);
    }

    private function seedExpenses(Camp $camp, User $user): void
    {
        $expenses = [
            [
                'category' => CampExpenseCategory::Transport,
                'title' => 'Mietwagen 9-Sitzer (×2)',
                'description' => '2 Fahrzeuge × 3 Tage × €70/Tag',
                'amount' => 420.00,
            ],
            [
                'category' => CampExpenseCategory::Materials,
                'title' => 'Bastelmaterial und Druckkosten',
                'description' => 'Papier, Stifte, Drucken Programmhefte',
                'amount' => 85.00,
            ],
            [
                'category' => CampExpenseCategory::Activities,
                'title' => 'Lagerfeuer-Set (Holz + Grillgut)',
                'description' => null,
                'amount' => 60.00,
            ],
            [
                'category' => CampExpenseCategory::Other,
                'title' => 'Erste-Hilfe-Set Nachfüllung',
                'description' => null,
                'amount' => 25.00,
            ],
        ];

        foreach ($expenses as $expense) {
            CampExpense::firstOrCreate(
                ['camp_id' => $camp->id, 'title' => $expense['title']],
                [
                    'user_id' => $user->id,
                    'category' => $expense['category'],
                    'description' => $expense['description'],
                    'amount' => $expense['amount'],
                ]
            );
        }
    }

    private function seedVisitors(Camp $camp): void
    {
        if (CampVisitor::where('camp_id', $camp->id)->exists()) {
            return;
        }

        // Parents
        $parent1 = Visitor::factory()->create([
            'name' => 'Zahra Benali',
            'email' => 'zahra.benali@example.com',
            'phone' => fake()->phoneNumber(),
            'gender' => Gender::Female->value,
            'date_of_birth' => '1980-05-12',
        ]);

        $parent2 = Visitor::factory()->create([
            'name' => 'Ahmed Khalil',
            'email' => 'ahmed.khalil@example.com',
            'phone' => fake()->phoneNumber(),
            'gender' => Gender::Male->value,
            'date_of_birth' => '1978-08-25',
        ]);

        // Teenagers with parents
        $teenagers = [
            ['name' => 'Ahmad Al-Hassan', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '2008-01-01', 'parent' => $parent1],
            ['name' => 'Maryam Yilmaz', 'gender' => Gender::Female->value, 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '2009-05-15', 'parent' => $parent1],
            ['name' => 'Omar Benali', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '2010-08-01', 'parent' => $parent2],
            ['name' => 'Safiya Öztürk', 'gender' => Gender::Female->value, 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '2011-03-15', 'parent' => $parent2],
            ['name' => 'Hamza Khalil', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Pending, 'date_of_birth' => '2012-09-20', 'parent' => $parent2],
            ['name' => 'Aisha Rahman', 'gender' => Gender::Female->value, 'status' => VisitorStatus::Pending, 'date_of_birth' => '2010-11-05', 'parent' => $parent1],
            ['name' => 'Yusuf Demir', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Waitlisted, 'date_of_birth' => '2009-07-10', 'parent' => $parent1],
            ['name' => 'Nour Al-Din', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Waitlisted, 'date_of_birth' => '2011-02-20', 'parent' => $parent2],
        ];

        $waitlistPosition = 1;

        // Create teenagers with parent relationships
        foreach ($teenagers as $data) {
            $parent = $data['parent'];
            unset($data['parent']);

            $visitor = Visitor::factory()
                ->child()
                ->withParent($parent)
                ->create([
                    'name' => $data['name'],
                    'gender' => $data['gender'],
                    'date_of_birth' => $data['date_of_birth'],
                ]);

            $isWaitlisted = $data['status'] === VisitorStatus::Waitlisted;

            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => $data['status'],
                'registered_at' => now(),
                'waitlist_position' => $isWaitlisted ? $waitlistPosition++ : null,
            ]);
        }

        // Also register parents as observers
        foreach ([$parent1, $parent2] as $parent) {
            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $parent->id,
                'status' => VisitorStatus::Confirmed,
                'registered_at' => now(),
            ]);
        }
    }
}
