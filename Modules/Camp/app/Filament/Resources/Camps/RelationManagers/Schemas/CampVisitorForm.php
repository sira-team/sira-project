<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\VisitorStatus;

final class CampVisitorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Select::make('status')
                        ->options(VisitorStatus::class)
                        ->required(),
                    Select::make('payment_status')
                        ->options(VisitorStatus::class)
                        ->required(),
                ]),
        ]);
    }
}
