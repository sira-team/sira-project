<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CampExpenseCategory: string implements HasColor, HasLabel
{
    case Accommodation = 'accommodation';
    case Catering = 'catering';
    case Materials = 'materials';
    case Activities = 'activities';
    case Transport = 'transport';
    case Investment = 'investment';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Accommodation => __('Accommodation'),
            self::Catering => __('Catering'),
            self::Materials => __('Materials'),
            self::Activities => __('Activities'),
            self::Transport => __('Transport'),
            self::Investment => __('Investment'),
            self::Other => __('Other'),
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            CampExpenseCategory::Accommodation => Color::Teal,
            CampExpenseCategory::Catering => Color::Amber,
            CampExpenseCategory::Materials => Color::Emerald,
            CampExpenseCategory::Activities => Color::Indigo,
            CampExpenseCategory::Transport => Color::Slate,
            CampExpenseCategory::Investment => Color::Rose,
            CampExpenseCategory::Other => Color::Gray,
        };
    }
}
