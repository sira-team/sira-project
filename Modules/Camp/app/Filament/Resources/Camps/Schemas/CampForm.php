<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;

final class CampForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('price')
                        ->numeric()
                        ->required()
                        ->label('Price per Participant (EUR)'),
                    TextInput::make('capacity')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->label('Total Capacity'),
                ]),

            Section::make('Dates & Duration')
                ->schema([
                    DatePicker::make('starts_at')
                        ->required(),
                    DatePicker::make('ends_at')
                        ->required()
                        ->afterOrEqual('starts_at'),
                ]),

            Section::make('Target Group & Gender')
                ->schema([
                    Select::make('target_group')
                        ->options(CampTargetGroup::class)
                        ->required()
                        ->live(),
                    TextInput::make('age_min')
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn (string $operation) => $operation !== 'view'),
                    TextInput::make('age_max')
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn (string $operation) => $operation !== 'view'),
                    Select::make('gender_policy')
                        ->options(CampGenderPolicy::class)
                        ->required(),
                ]),

            Section::make('Food & Accommodations')
                ->schema([
                    Toggle::make('food_provided')
                        ->label('Camp provides food'),
                    Toggle::make('participants_bring_food')
                        ->label('Participants bring food'),
                ]),

            Section::make('Registration & Planning')
                ->schema([
                    Toggle::make('registration_open')
                        ->label('Open for registration'),
                    DateTimePicker::make('registration_opens_at'),
                    DateTimePicker::make('registration_deadline'),
                    TextInput::make('predicted_participants')
                        ->numeric()
                        ->minValue(0)
                        ->label('Predicted Participants'),
                    TextInput::make('predicted_supporters')
                        ->numeric()
                        ->minValue(0)
                        ->label('Predicted Supporters'),
                ]),

            Section::make('Banking & Notes')
                ->schema([
                    TextInput::make('iban')
                        ->required()
                        ->maxLength(34),
                    TextInput::make('bank_recipient')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('notes')
                        ->rows(3),
                ]),
        ]);
    }
}
