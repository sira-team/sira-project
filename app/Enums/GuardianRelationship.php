<?php

declare(strict_types=1);

namespace App\Enums;

enum GuardianRelationship: string
{
    case Parent = 'parent';
    case Member = 'member';

    public function label(): string
    {
        return match ($this) {
            self::Parent => 'Parent',
            self::Member => 'Family Member',
        };
    }
}
