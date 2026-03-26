<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampGenderPolicy: string
{
    case All = 'all';
    case Male = 'male';
    case Female = 'female';

    public function label(): string
    {
        return match ($this) {
            self::All => 'all',
            self::Male => 'male',
            self::Female => 'female',
        };
    }
}
