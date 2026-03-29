<?php

declare(strict_types=1);

namespace Modules\Camp\Services;

use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;

final class WaitlistService
{
    public function promote(Camp $camp): void
    {
        /** @var CampVisitor|null $registration */
        $registration = $camp->campVisitors()
            ->where('status', VisitorStatus::Waitlisted)
            ->orderBy('waitlist_position')
            ->first();

        if ($registration === null) {
            return;
        }

        $registration->update([
            'status' => VisitorStatus::Pending,
            'waitlist_position' => null,
        ]);

        $registration->notify(CampNotificationType::WaitlistPromoted);

        $this->reorder($camp);
    }

    public function reorder(Camp $camp): void
    {
        $position = 1;

        $camp->campVisitors()
            ->where('status', VisitorStatus::Waitlisted)
            ->orderBy('registered_at')
            ->each(function ($registration) use (&$position) {
                $registration->update(['waitlist_position' => $position++]);
            });
    }

    public function assignPosition(Camp $camp): int
    {
        return ($camp->campVisitors()
            ->where('status', VisitorStatus::Waitlisted)
            ->max('waitlist_position') ?? 0) + 1;
    }
}
