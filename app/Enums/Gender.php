<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Gender: string implements HasColor, HasLabel
{
    case Male = 'male';
    case Female = 'female';

    public function getLabel(): string
    {
        return match ($this) {
            self::Male => __('Male'),
            self::Female => __('Female'),
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::Male => Color::Cyan,
            self::Female => Color::Pink,
        };
    }
}
