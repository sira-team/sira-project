<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Expos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Expo\Enums\ExpoStatus;

class ExpoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Expo Name')
                    ->required()
                    ->maxLength(255),
                Select::make('expo_request_id')
                    ->label('Linked Expo Request')
                    ->relationship('expoRequest', 'organisation_name')
                    ->searchable()
                    ->nullable()
                    ->preload(),
                TextInput::make('location_name')
                    ->label('Location Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('location_address')
                    ->label('Location Address')
                    ->rows(2),
                DatePicker::make('date')
                    ->label('Date')
                    ->required(),
                Select::make('status')
                    ->options(ExpoStatus::class)
                    ->required(),
                Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3),
            ]);
    }
}
