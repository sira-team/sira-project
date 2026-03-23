<?php

declare(strict_types=1);

namespace Modules\Camp\Services;

use Illuminate\Support\Facades\Mail;
use Modules\Camp\Enums\CampRegistrationStatus;
use Modules\Camp\Mails\CampWaitlistPromotedMail;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampRegistration;

final class WaitlistService
{
    public function promote(Camp $camp): void
    {
        /** @var CampRegistration $registration */
        $registration = $camp->registrations()
            ->where('status', CampRegistrationStatus::Waitlisted)
            ->orderBy('waitlist_position')
            ->firstOrFail();

        $registration->update([
            'status' => CampRegistrationStatus::Pending,
            'waitlist_position' => null,
        ]);

        Mail::queue(new CampWaitlistPromotedMail($registration));

        $this->reorder($camp);
    }

    public function reorder(Camp $camp): void
    {
        $position = 1;

        $camp->registrations()
            ->where('status', CampRegistrationStatus::Waitlisted)
            ->orderBy('registered_at')
            ->each(function ($registration) use (&$position) {
                $registration->update(['waitlist_position' => $position++]);
            });
    }

    public function assignPosition(Camp $camp): int
    {
        return ($camp->registrations()
            ->where('status', CampRegistrationStatus::Waitlisted)
            ->max('waitlist_position') ?? 0) + 1;
    }
}
