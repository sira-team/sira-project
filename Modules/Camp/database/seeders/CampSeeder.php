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

final class CampSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();
        $user = User::where('tenant_id', $tenant->id)->firstOrFail();
        $altenberg = Hostel::firstWhere('name', 'Jugendherberge Altenberg');
        $bonn = Hostel::firstWhere('name', 'Jugendherberge Bonn Venusberg');

        // Upcoming camp — registration open
        $upcomingCamp = Camp::firstOrCreate(
            ['name' => 'Sommercamp 2026', 'tenant_id' => $tenant->id],
            [
                'starts_at' => now()->addMonths(2)->next('Friday')->format('Y-m-d'),
                'ends_at' => now()->addMonths(2)->next('Friday')->addDays(4)->format('Y-m-d'),
                'price_per_participant' => 120.00,
                'target_group' => CampTargetGroup::Children,
                'gender_policy' => CampGenderPolicy::All,
                'description' => 'Schwerpunkt: Sira des Propheten ﷺ. Programm durch Jugendteam.',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($altenberg && ! CampContract::where('camp_id', $upcomingCamp->id)->exists()) {
            CampContract::create([
                'camp_id' => $upcomingCamp->id,
                'hostel_id' => $altenberg->id,
                'price_per_person_per_night' => 38.50,
                'includes_catering' => true,
                'contracted_beds' => 55,
                'contract_date' => now()->subWeeks(3)->format('Y-m-d'),
                'notes' => 'Stornierung bis 4 Wochen vor Beginn kostenfrei.',
            ]);
        }

        $this->seedExpenses($upcomingCamp, $user);
        $this->seedVisitors($upcomingCamp);

        // Past camp — completed
        $pastCamp = Camp::firstOrCreate(
            ['name' => 'Herbstcamp 2025', 'tenant_id' => $tenant->id],
            [
                'starts_at' => now()->subMonths(5)->next('Friday')->format('Y-m-d'),
                'ends_at' => now()->subMonths(5)->next('Friday')->addDays(3)->format('Y-m-d'),
                'price_per_participant' => 95.00,
                'target_group' => CampTargetGroup::Teenagers,
                'gender_policy' => CampGenderPolicy::All,
                'internal_notes' => 'Sehr gut verlaufen. Unterlagen archiviert.',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($bonn && ! CampContract::where('camp_id', $pastCamp->id)->exists()) {
            CampContract::create([
                'camp_id' => $pastCamp->id,
                'hostel_id' => $bonn->id,
                'price_per_person_per_night' => 35.00,
                'includes_catering' => false,
                'contracted_beds' => 40,
                'contract_date' => now()->subMonths(7)->format('Y-m-d'),
                'notes' => 'Abgerechnet und abgeschlossen.',
            ]);
        }

        $this->seedExpenses($pastCamp, $user);
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

        $entries = [
            ['name' => 'Ahmad Al-Hassan', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '2010-01-01'],
            ['name' => 'Maryam Yilmaz', 'gender' => Gender::Female->value, 'status' => VisitorStatus::Paid, 'date_of_birth' => '2015-05-15'],
            ['name' => 'Omar Benali', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '2012-08-01'],
            ['name' => 'Safiya Öztürk', 'gender' => Gender::Female->value, 'status' => VisitorStatus::Confirmed, 'date_of_birth' => '2018-03-15'],
            ['name' => 'Hamza Khalil', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Pending, 'date_of_birth' => '2018-09-20'],
            ['name' => 'Aisha Rahman', 'gender' => Gender::Female->value, 'status' => VisitorStatus::Pending, 'date_of_birth' => '2013-11-05'],
            ['name' => 'Yusuf Demir', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Waitlisted, 'date_of_birth' => '2017-07-10'],
            ['name' => 'Nour Al-Din', 'gender' => Gender::Male->value, 'status' => VisitorStatus::Waitlisted, 'date_of_birth' => '2019-02-20'],
        ];

        $waitlistPosition = 1;

        foreach ($entries as $data) {
            $email = mb_strtolower(str_replace(' ', '.', $data['name'])).'@example.com';

            $visitor = Visitor::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'phone' => null,
                    'gender' => $data['gender'],
                    'date_of_birth' => $data['date_of_birth'],
                ],
            );

            $isWaitlisted = $data['status'] === VisitorStatus::Waitlisted;

            CampVisitor::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'status' => $data['status'],
                'price' => $camp->price_per_participant,
                'registered_at' => now(),
                'waitlist_position' => $isWaitlisted ? $waitlistPosition++ : null,
            ]);
        }
    }
}
