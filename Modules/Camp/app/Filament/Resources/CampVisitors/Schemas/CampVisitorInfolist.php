<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Camp\Models\CampRegistrationAnswer;
use Modules\Camp\Models\CampVisitor;

final class CampVisitorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Registration'))
                ->columns(2)
                ->schema([
                    TextEntry::make('visitor.name')
                        ->label(__('Name')),
                    TextEntry::make('status')
                        ->label(__('Status'))
                        ->badge(),
                    TextEntry::make('registered_at')
                        ->label(__('Registered at'))
                        ->dateTime('d.m.Y H:i'),
                    TextEntry::make('waitlist_position')
                        ->label(__('Waitlist Position'))
                        ->placeholder('—')
                        ->visible(fn (CampVisitor $record) => $record->waitlist_position !== null),
                    TextEntry::make('room.name')
                        ->label(__('Room'))
                        ->placeholder('—'),
                    TextEntry::make('wishes')
                        ->label(__('Wishes'))
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),

            Section::make(__('Form Answers'))
                ->visible(fn (CampVisitor $record) => $record->answers()->exists())
                ->schema([
                    RepeatableEntry::make('answers')
                        ->hiddenLabel()
                        ->columns(3)
                        ->schema([
                            TextEntry::make('field_label')
                                ->label(__('Field'))
                                ->state(fn (CampRegistrationAnswer $answer): string => $answer->getDisplayLabel())
                                ->weight('medium'),
                            TextEntry::make('field_type')
                                ->label(__('Type'))
                                ->state(fn (CampRegistrationAnswer $answer): string => $answer->field?->type?->getLabel()
                                    ?? ($answer->field_type ? __($answer->field_type) : '—'))
                                ->badge(),
                            TextEntry::make('answer')
                                ->label(__('Answer'))
                                ->state(function (CampRegistrationAnswer $answer): string {
                                    $decoded = $answer->getDecodedValue();

                                    return match (true) {
                                        is_array($decoded) => implode(', ', array_filter((array) $decoded, 'strlen')),
                                        is_bool($decoded) => $decoded ? __('Yes') : __('No'),
                                        $decoded === null => '—',
                                        default => (string) $decoded,
                                    };
                                }),
                        ]),
                ]),
        ]);
    }
}
