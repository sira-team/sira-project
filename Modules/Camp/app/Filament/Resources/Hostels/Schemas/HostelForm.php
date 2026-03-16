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
                        ->required()
                        ->maxLength(255),
                    Textarea::make('address')
                        ->required()
                        ->rows(3),
                    TextInput::make('city')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                    TextInput::make('website')
                        ->url()
                        ->maxLength(255),
                    Textarea::make('notes')
                        ->rows(3),
                ])->columnSpanFull(),
        ]);
    }
}
