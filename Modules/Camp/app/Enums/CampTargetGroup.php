<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampTargetGroup: string
{
    case Children = 'children';
    case Teenagers = 'teenagers';
    case Adults = 'adults';
    case Family = 'family';

    public function label(): string
    {
        return match ($this) {
            self::Children => 'Children',
            self::Adults => 'Adults',
            self::Teenagers => 'Teenagers',
            self::Family => 'Family'
        };
    }
}
