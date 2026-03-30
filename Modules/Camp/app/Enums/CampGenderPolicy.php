<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use App\Enums\Gender;
use Filament\Support\Contracts\HasLabel;

enum CampGenderPolicy: string implements HasLabel
{
    case All = 'all';
    case Male = 'male';
    case Female = 'female';

    public function getLabel(): string
    {
        return match ($this) {
            self::All => __('All'),
            self::Male => __('Male'),
            self::Female => __('Female'),
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
