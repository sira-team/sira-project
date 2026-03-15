<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampRegistrationStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Waitlisted = 'waitlisted';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Waitlisted => 'Waitlisted',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'success',
            self::Waitlisted => 'info',
            self::Cancelled => 'danger',
        };
    }
}
