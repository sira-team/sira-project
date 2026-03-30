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
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Waitlisted => __('Waitlisted'),
            self::Confirmed => __('Confirmed'),
            self::Cancelled => __('Cancelled'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Waitlisted => 'info',
            self::Confirmed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
