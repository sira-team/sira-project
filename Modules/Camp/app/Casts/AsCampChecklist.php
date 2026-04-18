<?php

declare(strict_types=1);

namespace Modules\Camp\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Modules\Camp\ValueObjects\CampChecklist;

final class AsCampChecklist implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): CampChecklist
    {
        $data = $value ? json_decode($value, true, 512, JSON_THROW_ON_ERROR) : [];

        return CampChecklist::fromArray($data);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (! $value instanceof CampChecklist) {
            throw new InvalidArgumentException('The given value is not a CampChecklist instance.');
        }

        return json_encode($value->toArray());
    }
}
