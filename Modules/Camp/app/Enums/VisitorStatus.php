<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use BackedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum VisitorStatus: string implements HasColor, HasIcon, HasLabel
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

    public function getColor(): array
    {
        return match ($this) {
            self::Pending => Color::Amber,
            self::Waitlisted => Color::Purple,
            self::Confirmed => Color::Green,
            self::Cancelled => Color::Red,
        };
    }

    public function getIcon(): BackedEnum|Htmlable
    {
        return match ($this) {
            self::Pending => Heroicon::OutlinedUsers,
            self::Waitlisted => Heroicon::OutlinedQueueList,
            self::Confirmed => Heroicon::OutlinedCheckCircle,
            self::Cancelled => Heroicon::OutlinedXCircle,
        };
    }
}
