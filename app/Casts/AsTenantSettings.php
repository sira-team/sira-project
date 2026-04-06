<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\TenantSettings;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class AsTenantSettings implements CastsAttributes
{
    /**
     * Cast the stored JSON to your TenantSettings object
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): TenantSettings
    {
        $data = $value ? json_decode($value, true) : [];

        return TenantSettings::fromArray($data);
    }

    /**
     * Prepare the TenantSettings object for storage as JSON
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (! $value instanceof TenantSettings) {
            throw new InvalidArgumentException('The given value is not a TenantSettings instance.');
        }

        return json_encode($value->toArray());
    }
}
