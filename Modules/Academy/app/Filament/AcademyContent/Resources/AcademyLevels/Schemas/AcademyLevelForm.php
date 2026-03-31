<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class AcademyLevelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label(__('Sort Order'))
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
