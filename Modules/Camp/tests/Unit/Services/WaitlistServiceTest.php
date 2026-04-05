<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Models\Visitor;
use Illuminate\Support\Facades\Notification;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Services\WaitlistService;

function makeWaitlisted(Camp $camp, Gender $gender, int $position): CampVisitor
{
    return CampVisitor::factory()->create([
        'camp_id' => $camp->id,
        'visitor_id' => Visitor::factory()->create(['gender' => $gender])->id,
        'status' => VisitorStatus::Waitlisted,
        'waitlist_position' => $position,
    ]);
}

function makeConfirmed(Camp $camp, Gender $gender): CampVisitor
{
    return CampVisitor::factory()->create([
        'camp_id' => $camp->id,
        'visitor_id' => Visitor::factory()->create(['gender' => $gender])->id,
        'status' => VisitorStatus::Confirmed,
    ]);
}

describe('WaitlistService::promote()', function () {
    beforeEach(function () {
        Notification::fake();
        $this->service = app(WaitlistService::class);
        $this->tenant = createTenant();
    });

    describe('family camp', function () {
        it('promotes no one when camp is at full capacity', function () {
            $camp = Camp::factory()->create([
                'tenant_id' => $this->tenant->id,
                'target_group' => CampTargetGroup::Family,
                'max_visitors_all' => 2,
            ]);
            makeConfirmed($camp, Gender::Male);
            makeConfirmed($camp, Gender::Female);

            $w1 = makeWaitlisted($camp, Gender::Male, 1);
            $w2 = makeWaitlisted($camp, Gender::Female, 2);

            $this->service->promote($camp);

            expect($w1->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                ->and($w2->fresh()->status)->toBe(VisitorStatus::Waitlisted);
        });

        it('promotes only up to available capacity when list is larger', function () {
            $camp = Camp::factory()->create([
                'tenant_id' => $this->tenant->id,
                'target_group' => CampTargetGroup::Family,
                'max_visitors_all' => 4,
            ]);
            makeConfirmed($camp, Gender::Male);
            makeConfirmed($camp, Gender::Female); // 2 spots remaining

            $w1 = makeWaitlisted($camp, Gender::Male, 1);
            $w2 = makeWaitlisted($camp, Gender::Female, 2);
            $w3 = makeWaitlisted($camp, Gender::Male, 3);
            $w4 = makeWaitlisted($camp, Gender::Female, 4);

            $this->service->promote($camp);

            expect($w1->fresh()->status)->toBe(VisitorStatus::Pending)
                ->and($w2->fresh()->status)->toBe(VisitorStatus::Pending)
                ->and($w3->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                ->and($w4->fresh()->status)->toBe(VisitorStatus::Waitlisted);
        });

        it('promotes all waiting visitors when list is smaller than available capacity', function () {
            $camp = Camp::factory()->create([
                'tenant_id' => $this->tenant->id,
                'target_group' => CampTargetGroup::Family,
                'max_visitors_all' => 5,
            ]);
            makeConfirmed($camp, Gender::Male); // 4 spots remaining

            $w1 = makeWaitlisted($camp, Gender::Male, 1);
            $w2 = makeWaitlisted($camp, Gender::Female, 2);

            $this->service->promote($camp);

            expect($w1->fresh()->status)->toBe(VisitorStatus::Pending)
                ->and($w2->fresh()->status)->toBe(VisitorStatus::Pending);
        });
    });

    describe('non-family camp', function () {
        describe('both genders full', function () {
            it('promotes no one when the waitlist is larger than the zero remaining capacity', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 2,
                    'max_visitors_female' => 2,
                ]);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Female);
                makeConfirmed($camp, Gender::Female);

                $w1 = makeWaitlisted($camp, Gender::Male, 1);
                $w2 = makeWaitlisted($camp, Gender::Female, 2);
                $w3 = makeWaitlisted($camp, Gender::Male, 3);

                $this->service->promote($camp);

                expect($w1->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($w2->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($w3->fresh()->status)->toBe(VisitorStatus::Waitlisted);
            });

            it('promotes no one when the waitlist is smaller than the zero remaining capacity', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 2,
                    'max_visitors_female' => 2,
                ]);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Female);
                makeConfirmed($camp, Gender::Female);

                $w1 = makeWaitlisted($camp, Gender::Male, 1);
                $w2 = makeWaitlisted($camp, Gender::Female, 2);

                $this->service->promote($camp);

                expect($w1->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($w2->fresh()->status)->toBe(VisitorStatus::Waitlisted);
            });
        });

        describe('neither gender full', function () {
            it('promotes up to capacity per gender when the waitlist exceeds capacity', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 3,
                    'max_visitors_female' => 3,
                ]);
                makeConfirmed($camp, Gender::Male);   // 2 male spots remaining
                makeConfirmed($camp, Gender::Female); // 2 female spots remaining

                $m1 = makeWaitlisted($camp, Gender::Male, 1);
                $m2 = makeWaitlisted($camp, Gender::Male, 2);
                $m3 = makeWaitlisted($camp, Gender::Male, 3);
                $f1 = makeWaitlisted($camp, Gender::Female, 4);
                $f2 = makeWaitlisted($camp, Gender::Female, 5);
                $f3 = makeWaitlisted($camp, Gender::Female, 6);

                $this->service->promote($camp);

                expect($m1->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($m2->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($m3->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($f1->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($f2->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($f3->fresh()->status)->toBe(VisitorStatus::Waitlisted);
            });

            it('promotes all waiting visitors when the waitlist is smaller than capacity', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 3,
                    'max_visitors_female' => 3,
                ]);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Female);

                $m1 = makeWaitlisted($camp, Gender::Male, 1);
                $f1 = makeWaitlisted($camp, Gender::Female, 2);

                $this->service->promote($camp);

                expect($m1->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($f1->fresh()->status)->toBe(VisitorStatus::Pending);
            });
        });

        describe('female full, male not full', function () {
            it('promotes only males up to male capacity when the waitlist exceeds it', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 3,
                    'max_visitors_female' => 3,
                ]);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Female);
                makeConfirmed($camp, Gender::Female);
                makeConfirmed($camp, Gender::Female); // female full, 2 male spots remaining

                $m1 = makeWaitlisted($camp, Gender::Male, 1);
                $m2 = makeWaitlisted($camp, Gender::Male, 2);
                $m3 = makeWaitlisted($camp, Gender::Male, 3);
                $f1 = makeWaitlisted($camp, Gender::Female, 4);
                $f2 = makeWaitlisted($camp, Gender::Female, 5);

                $this->service->promote($camp);

                expect($m1->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($m2->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($m3->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($f1->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($f2->fresh()->status)->toBe(VisitorStatus::Waitlisted);
            });

            it('promotes only males when the waitlist is smaller than male capacity', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 3,
                    'max_visitors_female' => 3,
                ]);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Female);
                makeConfirmed($camp, Gender::Female);
                makeConfirmed($camp, Gender::Female); // female full, 2 male spots remaining

                $m1 = makeWaitlisted($camp, Gender::Male, 1);
                $f1 = makeWaitlisted($camp, Gender::Female, 2);

                $this->service->promote($camp);

                expect($m1->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($f1->fresh()->status)->toBe(VisitorStatus::Waitlisted);
            });
        });

        describe('male full, female not full', function () {
            it('promotes only females up to female capacity when the waitlist exceeds it', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 3,
                    'max_visitors_female' => 3,
                ]);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Male); // male full, 2 female spots remaining
                makeConfirmed($camp, Gender::Female);

                $m1 = makeWaitlisted($camp, Gender::Male, 1);
                $m2 = makeWaitlisted($camp, Gender::Male, 2);
                $f1 = makeWaitlisted($camp, Gender::Female, 3);
                $f2 = makeWaitlisted($camp, Gender::Female, 4);
                $f3 = makeWaitlisted($camp, Gender::Female, 5);

                $this->service->promote($camp);

                expect($m1->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($m2->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($f1->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($f2->fresh()->status)->toBe(VisitorStatus::Pending)
                    ->and($f3->fresh()->status)->toBe(VisitorStatus::Waitlisted);
            });

            it('promotes only females when the waitlist is smaller than female capacity', function () {
                $camp = Camp::factory()->create([
                    'tenant_id' => $this->tenant->id,
                    'target_group' => CampTargetGroup::Children,
                    'max_visitors_male' => 3,
                    'max_visitors_female' => 3,
                ]);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Male);
                makeConfirmed($camp, Gender::Male); // male full, 2 female spots remaining
                makeConfirmed($camp, Gender::Female);

                $m1 = makeWaitlisted($camp, Gender::Male, 1);
                $f1 = makeWaitlisted($camp, Gender::Female, 2);

                $this->service->promote($camp);

                expect($m1->fresh()->status)->toBe(VisitorStatus::Waitlisted)
                    ->and($f1->fresh()->status)->toBe(VisitorStatus::Pending);
            });
        });
    });
});

describe('WaitlistService::expire()', function () {
    beforeEach(function () {
        Notification::fake();
        $this->service = app(WaitlistService::class);
        $this->tenant = createTenant();
    });

    it('expires pending registrations older than 7 days', function () {
        $camp = Camp::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $v1 = CampVisitor::factory()->create([
            'camp_id' => $camp->id,
            'status' => VisitorStatus::Pending,
            'registered_at' => today()->subDays(9),
        ]);

        $v2 = CampVisitor::factory()->create([
            'camp_id' => $camp->id,
            'status' => VisitorStatus::Pending,
            'registered_at' => today()->subDays(6),
        ]);

        $count = $this->service->expire($camp);

        expect($count)->toBe(1)
            ->and($v1->refresh()->status)->toBe(VisitorStatus::Waitlisted)
            ->and($v2->refresh()->status)->toBe(VisitorStatus::Pending);
    });
});
