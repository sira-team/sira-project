<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampNotificationType: string
{
    case Received = 'received';
    case Waitlisted = 'waitlisted';
    case WaitlistPromoted = 'waitlist_promoted';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Registration Received',
            self::Waitlisted => 'Waitlisted',
            self::WaitlistPromoted => 'Promoted from Waitlist',
            self::Confirmed => 'Registration Confirmed',
            self::Cancelled => 'Registration Cancelled',
        };
    }

    /**
     * Returns the merge tags available for this notification type.
     * Tags are replaced in the template subject and body at send time.
     *
     * @return list<string>
     */
    public function mergeTags(): array
    {
        $common = ['visitor_name', 'camp_name', 'tenant_name', 'room_name'];
        $bankDetails = ['iban', 'bank_recipient', 'bank_name', 'bic'];

        return match ($this) {
            self::Received, self::WaitlistPromoted => [...$common, 'price', ...$bankDetails],
            self::Confirmed => [...$common, 'payment_due_date'],
            self::Waitlisted => [...$common, 'waitlist_position'],
            self::Cancelled => $common,
        };
    }
}
