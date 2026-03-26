<?php

declare(strict_types=1);

namespace App\Enums;

enum GuardianRelationship: string
{
    case Father = 'father';
    case Mother = 'mother';
    case Uncle = 'uncle';
    case Aunt = 'aunt';

    public function label(): string
    {
        return match ($this) {
            self::Father => 'Father',
            self::Mother => 'Mother',
            self::Uncle => 'Uncle',
            self::Aunt => 'Aunt',
        };
    }
}
