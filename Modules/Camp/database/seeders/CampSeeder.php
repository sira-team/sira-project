<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Seeders;

use App\Enums\Gender;
use App\Models\Participant;
use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Modules\Camp\Enums\CampExpenseCategory;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampPaymentStatus;
use Modules\Camp\Enums\CampRegistrationStatus;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampExpense;
use Modules\Camp\Models\CampRegistration;
use Modules\Camp\Models\Hostel;
use Modules\Camp\Models\HostelContract;

final class CampSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::default();
        $altenberg = Hostel::firstWhere('name', 'Jugendherberge Altenberg');
        $bonn = Hostel::firstWhere('name', 'Jugendherberge Bonn Venusberg');

        // Upcoming camp — registration open
        $upcomingCamp = Camp::firstOrCreate(
            ['name' => 'Sommercamp 2026', 'tenant_id' => $tenant->id],
            [
                'starts_at' => now()->addMonths(2)->next('Friday')->format('Y-m-d'),
                'ends_at' => now()->addMonths(2)->next('Friday')->addDays(4)->format('Y-m-d'),
                'capacity' => 60,
                'price' => 120.00,
                'target_group' => CampTargetGroup::Juniors,
                'gender_policy' => CampGenderPolicy::Separated,
                'food_provided' => true,
                'participants_bring_food' => false,
                'predicted_participants' => 50,
                'predicted_supporters' => 10,
                'registration_open' => true,
                'iban' => 'DE89370400440532013000',
                'bank_recipient' => 'Sira e.V. Bonn',
                'notes' => 'Schwerpunkt: Sira des Propheten ﷺ. Programm durch Jugendteam.',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($altenberg && ! HostelContract::where('camp_id', $upcomingCamp->id)->exists()) {
            HostelContract::create([
                'hostel_id' => $altenberg->id,
                'camp_id' => $upcomingCamp->id,
                'price_per_person_per_night' => 38.50,
                'contracted_participants' => 55,
                'contracted_supporters' => 10,
                'contract_date' => now()->subWeeks(3)->format('Y-m-d'),
                'notes' => 'Stornierung bis 4 Wochen vor Beginn kostenfrei.',
            ]);
        }

        $this->seedExpenses($upcomingCamp);
        $this->seedRegistrations($upcomingCamp, $tenant);

        // Past camp — completed
        $pastCamp = Camp::firstOrCreate(
            ['name' => 'Herbstcamp 2025', 'tenant_id' => $tenant->id],
            [
                'starts_at' => now()->subMonths(5)->next('Friday')->format('Y-m-d'),
                'ends_at' => now()->subMonths(5)->next('Friday')->addDays(3)->format('Y-m-d'),
                'capacity' => 40,
                'price' => 95.00,
                'target_group' => CampTargetGroup::Mixed,
                'gender_policy' => CampGenderPolicy::Mixed,
                'food_provided' => true,
                'participants_bring_food' => false,
                'predicted_participants' => 35,
                'predicted_supporters' => 8,
                'registration_open' => false,
                'iban' => 'DE89370400440532013000',
                'bank_recipient' => 'Sira e.V. Bonn',
                'notes' => 'Sehr gut verlaufen. Unterlagen archiviert.',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($bonn && ! HostelContract::where('camp_id', $pastCamp->id)->exists()) {
            HostelContract::create([
                'hostel_id' => $bonn->id,
                'camp_id' => $pastCamp->id,
                'price_per_person_per_night' => 35.00,
                'contracted_participants' => 40,
                'contracted_supporters' => 8,
                'contract_date' => now()->subMonths(7)->format('Y-m-d'),
                'notes' => 'Abgerechnet und abgeschlossen.',
            ]);
        }

        $this->seedExpenses($pastCamp);
    }

    private function seedExpenses(Camp $camp): void
    {
        $expenses = [
            [
                'category' => CampExpenseCategory::Transport,
                'title' => 'Mietwagen 9-Sitzer (×2)',
                'description' => '2 Fahrzeuge × 3 Tage × €70/Tag',
                'amount' => 420.00,
            ],
            [
                'category' => CampExpenseCategory::Material,
                'title' => 'Bastelmaterial und Druckkosten',
                'description' => 'Papier, Stifte, Drucken Programmhefte',
                'amount' => 85.00,
            ],
            [
                'category' => CampExpenseCategory::Aktivitaeten,
                'title' => 'Lagerfeuer-Set (Holz + Grillgut)',
                'description' => null,
                'amount' => 60.00,
            ],
            [
                'category' => CampExpenseCategory::Sonstiges,
                'title' => 'Erste-Hilfe-Set Nachfüllung',
                'description' => null,
                'amount' => 25.00,
            ],
        ];

        foreach ($expenses as $expense) {
            CampExpense::firstOrCreate(
                ['camp_id' => $camp->id, 'title' => $expense['title']],
                [
                    'category' => $expense['category'],
                    'description' => $expense['description'],
                    'amount' => $expense['amount'],
                ]
            );
        }
    }

    private function seedRegistrations(Camp $camp, Tenant $tenant): void
    {
        if (CampRegistration::where('camp_id', $camp->id)->exists()) {
            return;
        }

        $participants = [
            ['name' => 'Ahmad Al-Hassan', 'status' => CampRegistrationStatus::Confirmed, 'paid' => true],
            ['name' => 'Maryam Yilmaz', 'status' => CampRegistrationStatus::Confirmed, 'paid' => true],
            ['name' => 'Omar Benali', 'status' => CampRegistrationStatus::Confirmed, 'paid' => false],
            ['name' => 'Safiya Öztürk', 'status' => CampRegistrationStatus::Confirmed, 'paid' => false],
            ['name' => 'Hamza Khalil', 'status' => CampRegistrationStatus::Pending, 'paid' => false],
            ['name' => 'Aisha Rahman', 'status' => CampRegistrationStatus::Pending, 'paid' => false],
            ['name' => 'Yusuf Demir', 'status' => CampRegistrationStatus::Waitlisted, 'paid' => false],
            ['name' => 'Nour Al-Din', 'status' => CampRegistrationStatus::Waitlisted, 'paid' => false],
        ];

        $waitlistPosition = 1;

        foreach ($participants as $data) {
            $email = mb_strtolower(str_replace(' ', '.', $data['name'])).'@example.com';

            $visitor = Visitor::firstOrCreate(
                ['email' => $email],
                ['name' => $data['name'], 'phone' => null, 'email_verified_at' => now()]
            );

            $participant = Participant::firstOrCreate(
                ['visitor_id' => $visitor->id, 'name' => $data['name']],
                [
                    'date_of_birth' => fake()->dateTimeBetween('-18 years', '-10 years')->format('Y-m-d'),
                    'gender' => fake()->randomElement(Gender::cases())->value,
                    'is_self' => false,
                ]
            );

            $isWaitlisted = $data['status'] === CampRegistrationStatus::Waitlisted;

            CampRegistration::create([
                'camp_id' => $camp->id,
                'visitor_id' => $visitor->id,
                'participant_id' => $participant->id,
                'status' => $data['status'],
                'payment_status' => $data['paid'] ? CampPaymentStatus::Paid : CampPaymentStatus::Pending,
                'registered_at' => now(),
                'confirmed_at' => $data['status'] === CampRegistrationStatus::Confirmed ? now() : null,
                'waitlist_position' => $isWaitlisted ? $waitlistPosition++ : null,
            ]);
        }
    }
}
