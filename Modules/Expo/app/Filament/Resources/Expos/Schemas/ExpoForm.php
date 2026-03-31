<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Expos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Expo\Enums\ExpoStatus;

final class ExpoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Expo Name'))
                    ->required()
                    ->maxLength(255),
                Select::make('expo_request_id')
                    ->label(__('Linked Expo Request'))
                    ->relationship('expoRequest', 'organisation_name')
                    ->searchable()
                    ->nullable()
                    ->preload(),
                TextInput::make('location_name')
                    ->label(__('Location Name'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('location_address')
                    ->label(__('Location Address'))
                    ->rows(2),
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required(),
                Select::make('status')
                    ->label(__('Status'))
                    ->options(ExpoStatus::class)
                    ->required(),
                Textarea::make('notes')
                    ->label(__('Notes'))
                    ->rows(3),
            ]);
    }
}
