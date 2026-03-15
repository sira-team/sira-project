<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampGenderPolicy: string
{
    case Mixed = 'mixed';
    case Separated = 'separated';
    case BrothersOnly = 'brothers_only';
    case SistersOnly = 'sisters_only';

    public function label(): string
    {
        return match ($this) {
            self::Mixed => 'Mixed',
            self::Separated => 'Separated (gender-specific rooms)',
            self::BrothersOnly => 'Brothers Only',
            self::SistersOnly => 'Sisters Only',
        };
    }
}
