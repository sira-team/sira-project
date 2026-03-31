<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class HostelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    Textarea::make('address')
                        ->label(__('Address'))
                        ->required()
                        ->rows(3),
                    TextInput::make('city')
                        ->label(__('City'))
                        ->required()
                        ->maxLength(255),
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
                        ->maxLength(255),
                    Textarea::make('notes')
                        ->label(__('Notes'))
                        ->rows(3),
                ])->columnSpanFull(),
        ]);
    }
}
