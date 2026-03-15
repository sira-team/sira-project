<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\ExpoRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Expo\Enums\ExpoRequestStatus;

class ExpoRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('contact_name')
                    ->label('Contact Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('organisation_name')
                    ->label('Organisation Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('city')
                    ->label('City')
                    ->maxLength(255),
                DatePicker::make('preferred_date_from')
                    ->label('Preferred Date From'),
                DatePicker::make('preferred_date_to')
                    ->label('Preferred Date To')
                    ->afterOrEqual('preferred_date_from'),
                TextInput::make('expected_visitors')
                    ->label('Expected Visitors')
                    ->numeric()
                    ->minValue(1),
                Select::make('status')
                    ->options(ExpoRequestStatus::class)
                    ->required(),
                Textarea::make('message')
                    ->label('Message / Additional Info')
                    ->rows(3)
                    ->maxLength(1000),
                Textarea::make('internal_notes')
                    ->label('Internal Notes')
                    ->rows(3),
            ]);
    }
}
