<?php

declare(strict_types=1);

namespace Modules\Camp\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Modules\Camp\Enums\CampChecklistItem;

final class CampChecklist implements Arrayable, JsonSerializable
{
    /** @param array<string, bool> $toggles */
    public function __construct(
        private array $toggles = [],
    ) {}

    /** @param array<string, bool> $data */
    public static function fromArray(array $data): static
    {
        return new self($data);
    }

    public function isChecked(CampChecklistItem $item): bool
    {
        return $this->toggles[$item->value] ?? false;
    }

    public function withToggle(CampChecklistItem $item, bool $value): static
    {
        $new = clone $this;
        $new->toggles[$item->value] = $value;

        return $new;
    }

    /** @return array<string, bool> */
    public function toArray(): array
    {
        return $this->toggles;
    }

    /** @return array<string, bool> */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
