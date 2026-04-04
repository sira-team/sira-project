<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ExpenseCategory: string implements HasColor, HasLabel
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
            ExpenseCategory::Accommodation => Color::Teal,
            ExpenseCategory::Catering => Color::Amber,
            ExpenseCategory::Materials => Color::Emerald,
            ExpenseCategory::Activities => Color::Indigo,
            ExpenseCategory::Transport => Color::Slate,
            ExpenseCategory::Investment => Color::Rose,
            ExpenseCategory::Other => Color::Gray,
        };
    }
}
