<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final class HostelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    TextInput::make('name')
                        ->label(__('Hostel'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('address')
                        ->label(__('Street and number'))
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('city')
                        ->label(__('City'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('postcode')
                        ->required()
                        ->regex('/^[0-9]{5}$/')
                        ->label(__('Postcode')),
                    TextInput::make('phone')
                        ->label(__('Phone'))
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->maxLength(255),
                    TextInput::make('website')
                        ->label(__('Website'))
                        ->url()
                        ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                        ->default('https://')
                        ->columnSpanFull()
                        ->maxLength(255),
                    Textarea::make('notes')
                        ->columnSpanFull()
                        ->label(__('Notes'))
                        ->rows(3),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }
}
