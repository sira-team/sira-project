<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Expo\Enums\PhysicalMaterialType;

final class StationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Station Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
                Section::make('Physical Materials')
                    ->collapsible()
                    ->schema([
                        Repeater::make('physicalMaterials')
                            ->label('Materials')
                            ->relationship('physicalMaterials')
                            ->schema([
                                Select::make('type')
                                    ->options(PhysicalMaterialType::class)
                                    ->required(),
                                TextInput::make('name')
                                    ->required(),
                                Textarea::make('notes')
                                    ->rows(2),
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columnSpan('full'),
                    ]),
                Section::make('Digital Materials')
                    ->collapsible()
                    ->schema([
                        Repeater::make('digitalMaterials')
                            ->label('Materials')
                            ->relationship('digitalMaterials')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                FileUpload::make('file_path')
                                    ->label('File')
                                    ->disk('private')
                                    ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(20 * 1024) // 20MB
                                    ->required(),
                                Select::make('language')
                                    ->options([
                                        'de' => 'Deutsch',
                                        'en' => 'English',
                                        'ar' => 'العربية',
                                    ])
                                    ->default('de')
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columnSpan('full'),
                    ]),
            ]);
    }
}
