<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Filament\Resources\Hostels\Schemas\HostelForm;
use Modules\Camp\Models\FormTemplate;
use Modules\Camp\Models\Hostel;

final class CampWizard
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make()->schema([
                Step::make(__('Overview'))->schema([
                    TextInput::make('name')
                        ->label(__('Name of the camp'))
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),
                    DatePicker::make('starts_at')
                        ->label(__('Starts at'))
                        ->minDate(today())
                        ->required(),
                    DatePicker::make('ends_at')
                        ->label(__('Ends at'))
                        ->required()
                        ->minDate(fn (Get $get) => $get('starts_at') ?? today())
                        ->afterOrEqual('starts_at'),
                ])->columns(2),
                Step::make(__('Contract'))->schema([
                    Fieldset::make(__('Contract'))
                        ->hiddenLabel()
                        ->contained(false)
                        ->relationship('contract')
                        ->schema([
                            Select::make('hostel_id')
                                ->live()
                                ->label(__('Hostel'))
                                ->options(Hostel::query()->pluck('name', 'id'))
                                ->required()
                                ->columnSpan(2)
                                ->createOptionForm(fn (Schema $form): Schema => HostelForm::configure($form))
                                ->createOptionUsing(fn (array $data): int => Hostel::create($data)->id)
                                ->searchable(),
                            TextInput::make('price_per_person_per_night')
                                ->label(__('Price per Person per Night (EUR)'))
                                ->numeric()
                                ->required()
                                ->label(__('Price per Person per Night (EUR)')),
                            TextInput::make('contracted_beds')
                                ->reactive()
                                ->label(__('Contracted Beds'))
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
                Step::make(__('Target group'))->schema([
                    Select::make('target_group')
                        ->label(__('Target group'))
                        ->options(CampTargetGroup::class)
                        ->required()
                        ->reactive()
                        ->live(),
                    Select::make('gender_policy')
                        ->reactive()
                        ->required()
                        ->label(__('Gender'))
                        ->options(CampGenderPolicy::class),
                    TextInput::make('age_min')
                        ->visible(fn (Get $get) => $get('target_group') === CampTargetGroup::Children)
                        ->reactive()
                        ->required()
                        ->label(__('Min Age'))
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('age_max')
                        ->visible(fn (Get $get) => $get('target_group') === CampTargetGroup::Children)
                        ->reactive()
                        ->required()
                        ->label(__('Max Age'))
                        ->numeric()
                        ->minValue(0),
                ])->columns(2),
                Step::make(__('Planning'))->schema([
                    TextInput::make('max_visitors_all')
                        ->label(__('Visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(fn (Get $get) => $get('contract.contracted_beds'))
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') === CampTargetGroup::Family),
                    TextInput::make('max_visitors_male')
                        ->label(__('Male visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(fn (Get $get) => $get('contract.contracted_beds') - (int) $get('max_visitors_female'))
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Female),
                    TextInput::make('max_visitors_female')
                        ->label(__('Female visitor capacity'))
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(fn (Get $get) => $get('contract.contracted_beds') - (int) $get('max_visitors_male'))
                        ->required()
                        ->live()
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Male),
                    TextInput::make('price_per_participant')
                        ->numeric()
                        ->required()
                        ->label(__('Price per Participant (EUR)')),
                ])->columns(2),
                Step::make(__('Registration & Form'))->schema([
                    DatePicker::make('registration_opens_at')
                        ->suffix('00:00:00')
                        ->minDate(today())
                        ->maxDate(fn (Get $get) => $get('starts_at'))
                        ->label(__('Registration opens at')),
                    DatePicker::make('registration_ends_at')
                        ->suffix('23:59:59')
                        ->minDate(fn (Get $get) => $get('registration_opens_at') ?? today())
                        ->maxDate(fn (Get $get) => $get('starts_at'))
                        ->label(__('Registration ends at')),
                    Select::make('form_template_id')
                        ->label(__('Form Template'))
                        ->options(fn (): array => FormTemplate::query()->pluck('name', 'id')->toArray())
                        ->placeholder(__('Optional registration form'))
                        ->nullable()
                        ->searchable()
                        ->columnSpanFull(),
                ])->columns(2),
            ])->columnSpanFull(),
        ]);
    }
}
