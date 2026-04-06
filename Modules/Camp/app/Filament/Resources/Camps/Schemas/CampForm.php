<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Models\Camp;
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
                        ->label(__('Name'))
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),
                    TextInput::make('price_per_participant')
                        ->numeric()
                        ->columnSpanFull()
                        ->required()
                        ->label(__('Price per Participant (EUR)')),
                    DatePicker::make('starts_at')
                        ->label(__('Starts at'))
                        ->required(),
                    DatePicker::make('ends_at')
                        ->label(__('Ends at'))
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
                                ->columnSpanFull()
                                ->searchable(),
                            TextInput::make('price_per_person_per_night')
                                ->label(__('Price per Person per Night (EUR)'))
                                ->numeric()
                                ->required()
                                ->label(__('Price per Person per Night (EUR)')),
                            TextInput::make('contracted_beds')
                                ->label(__('Contracted Participants'))
                                ->hintIcon(Heroicon::OutlinedInformationCircle)
                                ->hintColor(Color::Gray)
                                ->hintIconTooltip(trans('camps.form.contracted_beds_hint'))
                                ->numeric()
                                ->required()
                                ->minValue(1),
                            DatePicker::make('contract_date')
                                ->label(__('Contract Date')),
                            DatePicker::make('cancellation_deadline_at')
                                ->label(__('Cancellation Deadline')),
                            Textarea::make('notes')
                                ->label(__('Notes'))
                                ->columnSpanFull()
                                ->rows(3),
                        ]),
                ]),
            Section::make(__('Target Group & Gender'))
                ->columns(2)
                ->schema([
                    Select::make('target_group')
                        ->label(__('Target group'))
                        ->options(CampTargetGroup::class)
                        ->reactive()
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
                    DatePicker::make('registration_opens_at')
                        ->label(__('Registration opens at')),
                    DatePicker::make('registration_ends_at')
                        ->label(__('Registration ends at')),
                    TextInput::make('max_visitors_all')
                        ->label(__('Visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(fn (Camp $record) => $record->contract->hostel->total_capacity)
                        ->columnSpanFull()
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') === CampTargetGroup::Family),
                    TextInput::make('max_visitors_male')
                        ->label(__('Male visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        // ->maxValue(fn (Camp $record, Get $get) => $record->contract->hostel->total_capacity - $get('max_visitors_female'))
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Female),
                    TextInput::make('max_visitors_female')
                        ->label(__('Female visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        // ->maxValue(fn (Camp $record, Get $get) => $record->contract->hostel->total_capacity - $get('max_visitors_male'))
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Male),
                    // TextEntry::make('info')
                    //    ->label(__('Info'))
                    //    ->columnSpanFull()
                    //    ->live()
                    //    ->reactive()
                    // ->state(fn (Camp $record, Get $get) => $get('target_group') === CampTargetGroup::Family ?
                    //    trans('camps.form.capacity_all', ['total' => $record->contract->hostel->total_capacity]) :
                    //    trans('camps.form.capacity_gendered', ['total' => $record->contract->hostel->total_capacity, 'male' => $get('max_visitors_male'), 'female' => $get('max_visitors_female')])
                    // ),
                    // TextEntry::make('capacity_left')
                    //    ->label(__('Capacity left'))
                    //    ->live()
                    //    ->reactive()
                    // ->state(fn (Camp $record, Get $get) => $get('target_group') === CampTargetGroup::Family ?
                    //    $record->contract->hostel->total_capacity - $get('max_visitors_all') :
                    //    $record->contract->hostel->total_capacity - $get('max_visitors_male') - $get('max_visitors_female')
                    // ),
                ])->columns(2),
        ]);
    }
}
