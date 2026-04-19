<?php

declare(strict_types=1);

namespace Modules\Camp\Services;

use App\Models\Visitor;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;

final class CampRegistrationService
{
    public function __construct(
        public WaitlistService $waitlistService
    ) {}

    public function registerVisitor(Camp $camp, Visitor $visitor): CampVisitor
    {
        return CampVisitor::create([
            'camp_id' => $camp->id,
            'visitor_id' => $visitor->id,
            'status' => $status = $this->determineStatus($camp, $visitor),
            'waitlist_position' => $status === VisitorStatus::Pending ? null : $this->waitlistService->assignPosition($camp, $visitor->gender),
            'registered_at' => now(),
        ]);
    }

    private function determineStatus(Camp $camp, Visitor $visitor): VisitorStatus
    {
        return $this->waitlistService->capacityReached($camp, $visitor) ? VisitorStatus::Waitlisted : VisitorStatus::Pending;
    }
}
