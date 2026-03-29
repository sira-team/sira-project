<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Expo\Enums\PhysicalMaterialType;

final class StationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Station Name'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(3),
                Section::make(__('Physical Materials'))
                    ->collapsible()
                    ->schema([
                        Repeater::make('physicalMaterials')
                            ->label(__('Materials'))
                            ->relationship('physicalMaterials')
                            ->schema([
                                FileUpload::make('image')
                                    ->image()
                                    ->label(__('Image'))
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(5 * 1024), // 5MB
                                Select::make('type')
                                    ->options(PhysicalMaterialType::class)
                                    ->required(),
                                TextInput::make('name')
                                    ->required(),
                                Textarea::make('notes')
                                    ->rows(2),
                            ])
                            ->columnSpan('full'),
                    ]),
                Section::make(__('Digital Materials'))
                    ->collapsible()
                    ->schema([
                        Repeater::make('digitalMaterials')
                            ->label(__('Materials'))
                            ->relationship('digitalMaterials')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                FileUpload::make('file_path')
                                    ->label(__('File'))
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
                            ])
                            ->columnSpan('full'),
                    ]),
            ]);
    }
}
