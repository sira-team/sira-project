<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Models\Hostel;

final class CampForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Basic Information'))
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),
                    TextInput::make('price_per_participant')
                        ->numeric()
                        ->columnSpanFull()
                        ->required()
                        ->label(__('Price per Participant (EUR)')),
                    DatePicker::make('starts_at')
                        ->required(),
                    DatePicker::make('ends_at')
                        ->required()
                        ->afterOrEqual('starts_at'),
                ]),
            Section::make(__('Contract'))
                ->schema([
                    Fieldset::make(__('Contract'))
                        ->hiddenLabel()
                        ->contained(false)
                        ->relationship('contract')
                        ->schema([
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
                                ->numeric()
                                ->required()
                                ->minValue(1),
                            DatePicker::make('contract_date'),
                            Textarea::make('notes')
                                ->columnSpanFull()
                                ->rows(3)
                                ->placeholder(__('e.g. cancellation terms, special conditions')),
                        ]),
                ]),
            Section::make(__('Target Group & Gender'))
                ->columns(2)
                ->schema([
                    Select::make('target_group')
                        ->label(__('Target group'))
                        ->options(CampTargetGroup::class)
                        ->live(),
                    Select::make('gender_policy')
                        ->reactive()
                        ->label(__('Gender'))
                        ->options(CampGenderPolicy::class),
                    TextInput::make('age_min')
                        ->label(__('Min Age'))
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn (string $operation) => $operation !== 'view'),
                    TextInput::make('age_max')
                        ->label(__('Max Age'))
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn (string $operation) => $operation !== 'view'),
                ]),
            Section::make(__('Registration & Planning'))
                ->schema([
                    DateTimePicker::make('registration_opens_at')
                        ->label(__('Registration opens at')),
                    DateTimePicker::make('registration_ends_at')
                        ->label(__('Registration ends at')),
                    TextInput::make('max_visitors_all')
                        ->label(__('Visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') === CampTargetGroup::Family),
                    TextInput::make('max_visitors_male')
                        ->label(__('Male visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Female),
                    TextInput::make('max_visitors_female')
                        ->label(__('Female visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Male),
                ])->columns(2),
        ]);
    }
}
