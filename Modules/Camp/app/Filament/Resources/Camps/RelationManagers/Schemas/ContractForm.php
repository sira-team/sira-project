<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Camp\Models\Hostel;

final class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('hostel_id')
                ->label(__('Hostel'))
                ->options(Hostel::query()->pluck('name', 'id'))
                ->required()
                ->searchable(),
            TextInput::make('price_per_person_per_night')
                ->numeric()
                ->required()
                ->label(__('Price per Person per Night (EUR)')),
            TextInput::make('contracted_beds')
                ->label(__('Contracted Participants'))
                ->numeric()
                ->required()
                ->minValue(1),
            DatePicker::make('contract_date')
                ->label(__('Contract Date')),
            DatePicker::make('cancellation_deadline_at')
                ->label(__('Cancellation Deadline')),
            Textarea::make('notes')
                ->rows(3)
                ->placeholder(__('e.g. cancellation terms, special conditions')),
        ]);
    }
}
