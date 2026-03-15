<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Academy\Models\AcademyLevel;

class AcademySessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academy_level_id')
                    ->label('Level')
                    ->options(AcademyLevel::query()->orderBy('sort_order')->pluck('title', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
