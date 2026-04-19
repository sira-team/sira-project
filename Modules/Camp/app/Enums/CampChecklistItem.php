<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;
use Modules\Camp\Models\Camp;

enum CampChecklistItem: string implements HasDescription, HasLabel
{
    // Computed — derived from Camp data, never stored
    case PriceSet = 'price_set';
    case RegistrationDatesSet = 'registration_dates_set';
    case ContractSigned = 'contract_signed';

    // Toggle — stored in the checklist column
    case CateringArranged = 'catering_arranged';
    case TransportationArranged = 'transportation_arranged';
    case MaterialsPrepared = 'materials_prepared';
    case StaffBriefed = 'staff_briefed';
    case EmergencyContactsCollected = 'emergency_contacts_collected';

    /** @return self[] */
    public static function toggleableItems(): array
    {
        return array_values(array_filter(self::cases(), fn (self $item) => ! $item->isComputed()));
    }

    public function getLabel(): string
    {
        return trans("camps.checklist.{$this->value}.label");
    }

    public function getDescription(): string
    {
        return trans("camps.checklist.{$this->value}.description");
    }

    public function isComputed(): bool
    {
        return match ($this) {
            self::PriceSet,
            self::RegistrationDatesSet,
            self::ContractSigned => true,
            default => false,
        };
    }

    public function check(Camp $camp): bool
    {
        return match ($this) {
            self::PriceSet => $camp->price_per_participant > 0,
            self::RegistrationDatesSet => $camp->registration_opens_at !== null && $camp->registration_ends_at !== null,
            self::ContractSigned => $camp->contract !== null,
            default => false,
        };
    }
}
