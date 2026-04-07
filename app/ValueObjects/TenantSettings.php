<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final class TenantSettings implements Arrayable, JsonSerializable
{
    public function __construct(
        public ?int $default_role_id = null,
        public string $locale = 'en',
        public string $timezone = 'UTC',
        public ?string $instagram = null,
    ) {}

    /**
     * Create an instance from an array (useful for the cast's get method)
     */
    public static function fromArray(array $attributes): static
    {
        return new self(
            default_role_id: $attributes['default_role_id'] ?? null,
            locale: $attributes['locale'] ?? 'en',
            timezone: $attributes['timezone'] ?? 'UTC',
            instagram: $attributes['instagram'] ?? null,
        );
    }

    /**
     * Convert the object to an array for storage
     */
    public function toArray(): array
    {
        return [
            'default_role_id' => $this->default_role_id,
            'locale' => $this->locale,
            'timezone' => $this->timezone,
            'instagram' => $this->instagram,
        ];
    }

    /**
     * Specify data for JSON serialization
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
