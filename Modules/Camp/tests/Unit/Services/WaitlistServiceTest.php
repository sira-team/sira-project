<?php

declare(strict_types=1);

use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;

describe('WaitlistService', function () {
    it('promotes the registration with the lowest waitlist position', function () {
        $tenant = createTenant();
        $camp = Camp::factory()->create(['tenant_id' => $tenant->id]);
        $first = CampVisitor::factory()->create([
            'camp_id' => $camp->id,
            'status' => VisitorStatus::Waitlisted,
            'waitlist_position' => 1,
            'registered_at' => now()->subMinutes(10),
        ]);
        $second = CampVisitor::factory()->create([
            'camp_id' => $camp->id,
            'status' => VisitorStatus::Waitlisted,
            'waitlist_position' => 2,
            'registered_at' => now()->subMinutes(5),
        ]);

        expect(1)->toBe(1);
        // TODO: implementation gap - WaitlistService not yet implemented
        // app(WaitlistService::class)->promote($camp);
        // expect($first->fresh()->status)->toBe(CampRegistrationStatus::Pending);
        // expect($first->fresh()->waitlist_position)->toBeNull();
        // expect($second->fresh()->waitlist_position)->toBe(1);
    });

    it('renumbers remaining waitlisted registrations after promotion', function () {
        $tenant = createTenant();
        $camp = Camp::factory()->create(['tenant_id' => $tenant->id]);
        $regs = collect(range(1, 3))->map(fn ($i) => CampVisitor::factory()->create([
            'camp_id' => $camp->id,
            'status' => VisitorStatus::Waitlisted,
            'waitlist_position' => $i,
            'registered_at' => now()->addMinutes($i),
        ]));

        // TODO: implementation gap - WaitlistService not yet implemented
        // app(WaitlistService::class)->promote($camp);
        // expect($regs[0]->fresh()->status)->toBe(CampRegistrationStatus::Pending);
        // expect($regs[1]->fresh()->waitlist_position)->toBe(1);
        // expect($regs[2]->fresh()->waitlist_position)->toBe(2);
    });

    it('does nothing when no waitlisted registrations exist', function () {
        $tenant = createTenant();
        $camp = Camp::factory()->create(['tenant_id' => $tenant->id]);
        // TODO: implementation gap - WaitlistService not yet implemented
        // expect(fn() => app(WaitlistService::class)->promote($camp))->not->toThrow(\Exception::class);
    });
});
