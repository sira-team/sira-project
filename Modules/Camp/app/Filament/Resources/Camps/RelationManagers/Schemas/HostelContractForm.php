<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Camp\Models\Hostel;

final class HostelContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('hostel_id')
                ->label('Hostel')
                ->options(Hostel::query()->pluck('name', 'id'))
                ->required()
                ->searchable(),
            TextInput::make('price_per_person_per_night')
                ->numeric()
                ->required()
                ->label('Price per Person per Night (EUR)'),
            TextInput::make('contracted_participants')
                ->numeric()
                ->required()
                ->minValue(1),
            TextInput::make('contracted_supporters')
                ->numeric()
                ->required()
                ->minValue(0),
            DatePicker::make('contract_date'),
            Textarea::make('notes')
                ->rows(3)
                ->placeholder('e.g. cancellation terms, special conditions'),
        ]);
    }
}
