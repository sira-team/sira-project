<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\ExpoRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Expo\Enums\ExpoRequestStatus;

final class ExpoRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('contact_name')
                    ->label(__('Contact Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('organisation_name')
                    ->label(__('Organisation Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel()
                    ->maxLength(255),
                TextInput::make('city')
                    ->label(__('City'))
                    ->maxLength(255),
                DatePicker::make('preferred_date_from')
                    ->label(__('Preferred Date From')),
                DatePicker::make('preferred_date_to')
                    ->label(__('Preferred Date To'))
                    ->afterOrEqual('preferred_date_from'),
                Select::make('status')
                    ->label(__('Status'))
                    ->options(ExpoRequestStatus::class)
                    ->required(),
                Textarea::make('message')
                    ->label(__('Message / Additional Info'))
                    ->rows(3)
                    ->maxLength(1000),
                Textarea::make('internal_notes')
                    ->label(__('Internal Notes'))
                    ->rows(3),
            ]);
    }
}
