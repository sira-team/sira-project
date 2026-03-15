<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

enum CampExpenseCategory: string
{
    case Uebernachtung = 'uebernachtung';
    case Verpflegung = 'verpflegung';
    case Material = 'material';
    case Aktivitaeten = 'aktivitaeten';
    case Transport = 'transport';
    case Investition = 'investition';
    case Sonstiges = 'sonstiges';

    public function label(): string
    {
        return match ($this) {
            self::Uebernachtung => 'Übernachtung (Accommodation)',
            self::Verpflegung => 'Verpflegung (Food)',
            self::Material => 'Material (Consumables)',
            self::Aktivitaeten => 'Aktivitäten (Activities)',
            self::Transport => 'Transport',
            self::Investition => 'Investition (Equipment)',
            self::Sonstiges => 'Sonstiges (Other)',
        };
    }
}
