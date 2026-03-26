<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampRegistrationStatus: string
{
    case Pending = 'pending';
    case Waitlisted = 'waitlisted';
    case Confirmed = 'confirmed';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Waitlisted => 'Waitlisted',
            self::Confirmed => 'Confirmed',
            self::Paid => 'Paid',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Waitlisted => 'info',
            self::Confirmed => 'success',
            self::Paid => 'success',
            self::Cancelled => 'danger',
        };
    }
}
