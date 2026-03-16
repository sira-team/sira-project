<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\RelationManagers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Camp\Models\HostelRoom;

final class HostelRoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('capacity')
                ->required()
                ->numeric()
                ->minValue(1),
            TextInput::make('floor')
                ->datalist(HostelRoom::query()->distinct()->pluck('floor')->toArray())
                ->required()
                ->maxLength(255),
        ]);
    }
}
