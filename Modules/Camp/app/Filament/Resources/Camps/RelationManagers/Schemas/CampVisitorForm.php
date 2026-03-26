<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\CampRegistrationStatus;

final class CampVisitorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Select::make('status')
                        ->options(CampRegistrationStatus::class)
                        ->required(),
                    Select::make('payment_status')
                        ->options(CampRegistrationStatus::class)
                        ->required(),
                ]),
        ]);
    }
}
