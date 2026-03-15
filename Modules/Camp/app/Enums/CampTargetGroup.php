<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampTargetGroup: string
{
    case Juniors = 'juniors';
    case Adults = 'adults';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::Juniors => 'Children',
            self::Adults => 'Adults',
            self::Mixed => 'Mixed',
        };
    }
}
