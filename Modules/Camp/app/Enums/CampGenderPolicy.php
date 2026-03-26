<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use App\Enums\Gender;

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

    public function getGenders(): array
    {
        return match ($this) {
            self::All => [Gender::Male, Gender::Female],
            self::Male => [Gender::Male],
            self::Female => [Gender::Female],
        };
    }
}
