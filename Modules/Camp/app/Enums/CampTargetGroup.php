<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use Filament\Support\Contracts\HasLabel;

enum CampTargetGroup: string implements HasLabel
{
    case Children = 'children';
    case Teenagers = 'teenagers';
    case Adults = 'adults';
    case Family = 'family';

    public function getLabel(): string
    {
        return match ($this) {
            self::Children => __('Children'),
            self::Adults => __('Adults'),
            self::Teenagers => __('Teenagers'),
            self::Family => __('Family'),
        };
    }
}
