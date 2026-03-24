<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\Hostel;

final class CampInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Basic Information')
                ->columns(3)
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('price')
                        ->label('Price per Participant')
                        ->money('EUR'),
                    TextEntry::make('capacity')
                        ->label('Total Capacity')
                        ->numeric(),
                ]),

            Section::make('Dates')
                ->columns(2)
                ->schema([
                    TextEntry::make('starts_at')->date('d.m.Y'),
                    TextEntry::make('ends_at')->date('d.m.Y'),
                ]),

            Section::make('Target Group & Gender')
                ->columns(2)
                ->schema([
                    TextEntry::make('target_group')->badge(),
                    TextEntry::make('gender_policy')->badge(),
                    TextEntry::make('age_min')->label('Min Age')->placeholder('—'),
                    TextEntry::make('age_max')->label('Max Age')->placeholder('—'),
                ]),

            Section::make('Food & Accommodations')
                ->columns(2)
                ->schema([
                    IconEntry::make('food_provided')->label('Camp provides food')->boolean(),
                    IconEntry::make('participants_bring_food')->label('Participants bring food')->boolean(),
                ]),

            Section::make('Registration & Planning')
                ->columns(2)
                ->schema([
                    IconEntry::make('registration_open')->label('Open for registration')->boolean(),
                    TextEntry::make('registration_opens_at')->dateTime()->placeholder('—'),
                    TextEntry::make('registration_deadline')->dateTime()->placeholder('—'),
                    TextEntry::make('predicted_participants')->label('Predicted Participants')->numeric()->placeholder('—'),
                    TextEntry::make('predicted_supporters')->label('Predicted Supporters')->numeric()->placeholder('—'),
                ]),

            Section::make('Banking')
                ->columns(2)
                ->schema([
                    TextEntry::make('iban')->label('IBAN'),
                    TextEntry::make('bank_recipient')->label('Bank Recipient'),
                ]),

            Section::make('Notes')
                ->schema([
                    TextEntry::make('notes')->placeholder('—')->columnSpanFull(),
                ]),

            Section::make('Hostel Contract')
                ->columns(2)
                ->headerActions([
                    Action::make('createContract')
                        ->label('Add Contract')
                        ->icon(Heroicon::OutlinedPlus)
                        ->visible(fn (Camp $record) => $record->hostelContract === null)
                        ->schema(self::contractFormFields())
                        ->action(function (array $data, Camp $record) {
                            $record->hostelContract()->create($data);
                            $record->load('hostelContract.hostel');
                        }),

                    Action::make('editContract')
                        ->label('Edit')
                        ->icon(Heroicon::OutlinedPencilSquare)
                        ->visible(fn (Camp $record) => $record->hostelContract !== null)
                        ->fillForm(fn (Camp $record) => $record->hostelContract->toArray())
                        ->schema(self::contractFormFields())
                        ->action(function (array $data, Camp $record) {
                            $record->hostelContract->update($data);
                            $record->load('hostelContract.hostel');
                        }),

                    Action::make('deleteContract')
                        ->label('Delete')
                        ->icon(Heroicon::OutlinedTrash)
                        ->color('danger')
                        ->visible(fn (Camp $record) => $record->hostelContract !== null)
                        ->requiresConfirmation()
                        ->action(function (Camp $record) {
                            $record->hostelContract->delete();
                            $record->unsetRelation('hostelContract');
                        }),
                ])
                ->schema([
                    TextEntry::make('hostelContract.hostel.name')
                        ->label('Hostel')
                        ->placeholder('No contract yet'),
                    TextEntry::make('hostelContract.price_per_person_per_night')
                        ->label('Price / Person / Night')
                        ->money('EUR')
                        ->placeholder('—'),
                    TextEntry::make('hostelContract.contracted_participants')
                        ->label('Contracted Participants')
                        ->numeric()
                        ->placeholder('—'),
                    TextEntry::make('hostelContract.contracted_supporters')
                        ->label('Contracted Supporters')
                        ->numeric()
                        ->placeholder('—'),
                    TextEntry::make('hostelContract.contract_date')
                        ->label('Contract Date')
                        ->date()
                        ->placeholder('—'),
                    TextEntry::make('hostelContract.notes')
                        ->label('Notes')
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /** @return array<int, mixed> */
    private static function contractFormFields(): array
    {
        return [
            Select::make('hostel_id')
                ->label('Hostel')
                ->options(Hostel::query()->pluck('name', 'id'))
                ->required()
                ->searchable(),
            TextInput::make('price_per_person_per_night')
                ->label('Price per Person per Night (EUR)')
                ->numeric()
                ->required(),
            TextInput::make('contracted_participants')
                ->label('Contracted Participants')
                ->numeric()
                ->required()
                ->minValue(1),
            TextInput::make('contracted_supporters')
                ->label('Contracted Supporters')
                ->numeric()
                ->required()
                ->minValue(0),
            DatePicker::make('contract_date'),
            Textarea::make('notes')
                ->rows(3)
                ->placeholder('e.g. cancellation terms, special conditions'),
        ];
    }
}
