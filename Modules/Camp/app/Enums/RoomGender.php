<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum RoomGender: string
{
    case Male = 'male';
    case Female = 'female';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Male',
            self::Female => 'Female',
            self::Mixed => 'Mixed',
        };
    }
}
