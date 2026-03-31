<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;

final class CampInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Basic Information'))
                ->columns(2)
                ->schema([
                    TextEntry::make('name')
                        ->label(__('Name')),
                    TextEntry::make('price_per_participant')
                        ->label(__('Price per Participant'))
                        ->money('EUR'),
                    TextEntry::make('starts_at')
                        ->label(__('Starts at'))
                        ->date('d.m.Y'),
                    TextEntry::make('ends_at')
                        ->label(__('Ends at'))
                        ->date('d.m.Y'),
                ]),

            Section::make(__('Hostel Contract'))
                ->columns(2)
                ->schema([
                    TextEntry::make('contract.hostel.name')
                        ->label(__('Hostel'))
                        ->columnSpanFull()
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
                        ->date('d.m.Y')
                        ->placeholder('—'),
                    TextEntry::make('contract.cancellation_deadline_at')
                        ->label(__('Cancellation Deadline'))
                        ->date('d.m.Y')
                        ->placeholder('—'),
                    TextEntry::make('contract.notes')
                        ->label(__('Notes'))
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),

            Section::make(__('Target Group & Gender'))
                ->columns(2)
                ->schema([
                    TextEntry::make('target_group')->label(__('Target group'))->badge(),
                    TextEntry::make('gender_policy')->label(__('Gender policy'))->badge(),
                    TextEntry::make('age_min')->label(__('Min Age'))->placeholder('—'),
                    TextEntry::make('age_max')->label(__('Max Age'))->placeholder('—'),
                ]),

            Section::make(__('Registration & Planning'))
                ->columns(2)
                ->schema([
                    TextEntry::make('registration_opens_at')
                        ->label(__('Registration opens at'))
                        ->dateTime('d.m.Y h:i')
                        ->placeholder('—'),
                    TextEntry::make('registration_ends_at')
                        ->label(__('Registration ends at'))
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
}
