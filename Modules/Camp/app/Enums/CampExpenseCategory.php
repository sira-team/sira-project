<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampExpenseCategory: string
{
    case Accommodation = 'accommodation';
    case Catering = 'catering';
    case Materials = 'materials';
    case Activities = 'activities';
    case Transport = 'transport';
    case Investment = 'investment';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Accommodation => 'Accommodation',
            self::Catering => 'Catering',
            self::Materials => 'Materials',
            self::Activities => 'Activities',
            self::Transport => 'Transport',
            self::Investment => 'Investment',
            self::Other => 'Other',
        };
    }
}
