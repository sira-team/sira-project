<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\Hostel;

final class CampInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Basic Information'))
                ->columns(2)
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('price_per_participant')
                        ->label(__('Price per Participant'))
                        ->money('EUR'),
                    TextEntry::make('starts_at')->date('d.m.Y'),
                    TextEntry::make('ends_at')->date('d.m.Y'),
                ]),

            Section::make(__('Hostel Contract'))
                ->columns(2)
                ->headerActions([
                    Action::make('createContract')
                        ->label(__('Add Contract'))
                        ->icon(Heroicon::OutlinedPlus)
                        ->visible(fn (Camp $record) => $record->contract === null)
                        ->schema(self::contractFormFields())
                        ->action(function (array $data, Camp $record) {
                            $record->contract()->create($data);
                            $record->load('contract.hostel');
                        }),

                    Action::make('editContract')
                        ->label(__('Edit'))
                        ->icon(Heroicon::OutlinedPencilSquare)
                        ->visible(fn (Camp $record) => $record->contract !== null)
                        ->fillForm(fn (Camp $record) => $record->contract->toArray())
                        ->schema(self::contractFormFields())
                        ->action(function (array $data, Camp $record) {
                            $record->contract->update($data);
                            $record->load('contract.hostel');
                        }),

                    Action::make('deleteContract')
                        ->label(__('Delete'))
                        ->icon(Heroicon::OutlinedTrash)
                        ->color('danger')
                        ->visible(fn (Camp $record) => $record->contract !== null)
                        ->requiresConfirmation()
                        ->action(function (Camp $record) {
                            $record->contract->delete();
                            $record->unsetRelation('contract');
                        }),
                ])
                ->schema([
                    TextEntry::make('contract.hostel.name')
                        ->label(__('Hostel'))
                        ->placeholder(__('No contract yet')),
                    TextEntry::make('contract.price_per_person_per_night')
                        ->label(__('Price / Person / Night'))
                        ->money('EUR')
                        ->placeholder('—'),
                    TextEntry::make('contract.contracted_beds')
                        ->label(__('Contracted Participants'))
                        ->numeric()
                        ->placeholder('—'),
                    TextEntry::make('contract.contract_date')
                        ->label(__('Contract Date'))
                        ->date()
                        ->placeholder('—'),
                    TextEntry::make('contract.notes')
                        ->label(__('Notes'))
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),

            Section::make(__('Target Group & Gender'))
                ->columns(2)
                ->schema([
                    TextEntry::make('target_group')->badge(),
                    TextEntry::make('gender_policy')->badge(),
                    TextEntry::make('age_min')->label(__('Min Age'))->placeholder('—'),
                    TextEntry::make('age_max')->label(__('Max Age'))->placeholder('—'),
                ]),

            Section::make(__('Registration & Planning'))
                ->columns(2)
                ->schema([
                    TextEntry::make('registration_opens_at')
                        ->dateTime('d.m.Y h:i')
                        ->placeholder('—'),
                    TextEntry::make('registration_ends_at')
                        ->dateTime('d.m.Y h:i')
                        ->placeholder('—'),
                    TextEntry::make('max_visitors_all')
                        ->label(__('Visitor capacity'))
                        ->visible(fn (Get $get): bool => $get('target_group') === CampTargetGroup::Family),
                    TextEntry::make('max_visitors_male')
                        ->label(__('Male visitor capacity'))
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Female),
                    TextEntry::make('max_visitors_female')
                        ->label(__('Female visitor capacity'))
                        ->visible(fn (Get $get): bool => $get('target_group') !== CampTargetGroup::Family)
                        ->hidden(fn (Get $get): bool => $get('gender_policy') === CampGenderPolicy::Male),
                ]),

        ]);
    }

    /** @return array<int, mixed> */
    private static function contractFormFields(): array
    {
        return [
            Select::make('hostel_id')
                ->label(__('Hostel'))
                ->options(Hostel::query()->pluck('name', 'id'))
                ->required()
                ->searchable(),
            TextInput::make('price_per_person_per_night')
                ->label(__('Price per Person per Night (EUR)'))
                ->numeric()
                ->required(),
            TextInput::make('contracted_beds')
                ->label(__('Contracted Participants'))
                ->numeric()
                ->required()
                ->minValue(1),
            DatePicker::make('contract_date'),
            Textarea::make('notes')
                ->rows(3)
                ->placeholder(__('e.g. cancellation terms, special conditions')),
        ];
    }
}
