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
                ->columns(2)
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('price_per_participant')
                        ->label('Price per Participant')
                        ->money('EUR'),
                    TextEntry::make('starts_at')->date('d.m.Y'),
                    TextEntry::make('ends_at')->date('d.m.Y'),
                ]),

            Section::make('Hostel Contract')
                ->columns(2)
                ->headerActions([
                    Action::make('createContract')
                        ->label('Add Contract')
                        ->icon(Heroicon::OutlinedPlus)
                        ->visible(fn (Camp $record) => $record->contract === null)
                        ->schema(self::contractFormFields())
                        ->action(function (array $data, Camp $record) {
                            $record->contract()->create($data);
                            $record->load('contract.hostel');
                        }),

                    Action::make('editContract')
                        ->label('Edit')
                        ->icon(Heroicon::OutlinedPencilSquare)
                        ->visible(fn (Camp $record) => $record->contract !== null)
                        ->fillForm(fn (Camp $record) => $record->contract->toArray())
                        ->schema(self::contractFormFields())
                        ->action(function (array $data, Camp $record) {
                            $record->contract->update($data);
                            $record->load('contract.hostel');
                        }),

                    Action::make('deleteContract')
                        ->label('Delete')
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
                        ->label('Hostel')
                        ->placeholder('No contract yet'),
                    TextEntry::make('contract.price_per_person_per_night')
                        ->label('Price / Person / Night')
                        ->money('EUR')
                        ->placeholder('—'),
                    TextEntry::make('contract.contracted_beds')
                        ->label('Contracted Participants')
                        ->numeric()
                        ->placeholder('—'),
                    TextEntry::make('contract.contract_date')
                        ->label('Contract Date')
                        ->date()
                        ->placeholder('—'),
                    TextEntry::make('contract.notes')
                        ->label('Notes')
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),

            Section::make('Target Group & Gender')
                ->columns(2)
                ->schema([
                    TextEntry::make('target_group')->badge(),
                    TextEntry::make('gender_policy')->badge(),
                    TextEntry::make('age_min')->label('Min Age')->placeholder('—'),
                    TextEntry::make('age_max')->label('Max Age')->placeholder('—'),
                ]),

            Section::make('Registration & Planning')
                ->columns(2)
                ->schema([
                    TextEntry::make('registration_opens_at')->dateTime()->placeholder('—'),
                    TextEntry::make('registration_ends_at')->dateTime()->placeholder('—'),
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
            TextInput::make('contracted_beds')
                ->label('Contracted Participants')
                ->numeric()
                ->required()
                ->minValue(1),
            DatePicker::make('contract_date'),
            Textarea::make('notes')
                ->rows(3)
                ->placeholder('e.g. cancellation terms, special conditions'),
        ];
    }
}
