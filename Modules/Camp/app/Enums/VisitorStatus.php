<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VisitorStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';
    case Waitlisted = 'waitlisted';
    case Confirmed = 'confirmed';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Waitlisted => 'Waitlisted',
            self::Confirmed => 'Confirmed',
            self::Paid => 'Paid',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Waitlisted => 'info',
            self::Confirmed => 'primary',
            self::Paid => 'success',
            self::Cancelled => 'danger',
        };
    }
}
