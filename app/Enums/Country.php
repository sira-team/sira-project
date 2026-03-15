<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Country: string implements HasLabel
{
    case Germany = 'DE';
    case Austria = 'AT';
    case Switzerland = 'CH';

    public function getLabel(): string|Htmlable|null
    {
        return trans('countries.'.$this->value);
    }
}
