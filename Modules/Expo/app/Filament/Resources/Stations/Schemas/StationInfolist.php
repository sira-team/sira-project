<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Expo\Enums\DigitalMaterialType;
use Modules\Expo\Enums\PhysicalMaterialType;

final class StationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(['sm' => 1])
                    ->columns(1)
                    ->schema([
                        Section::make(__('Station Details'))
                            ->schema([
                                TextEntry::make('name')
                                    ->label(__('Station Name')),
                                TextEntry::make('description')
                                    ->label(__('Description')),
                            ]),
                        Section::make(__('Digital Materials'))
                            ->description(__('Digital files available for download.'))
                            ->schema([
                                RepeatableEntry::make('digitalMaterials')
                                    ->schema([
                                        TextEntry::make('title')
                                            ->label(__('Title'))
                                            ->url(fn ($record) => url("expo/stations/{$record->station_id}/materials/{$record->id}/download"))
                                            ->openUrlInNewTab(),
                                        TextEntry::make('file_type')
                                            ->label(__('File Type'))
                                            ->formatStateUsing(fn (string $state): string => DigitalMaterialType::from($state)->name),
                                        TextEntry::make('language')
                                            ->label(__('Language')),
                                        TextEntry::make('file_size_kb')
                                            ->label(__('File Size (KB)')),
                                        TextEntry::make('uploadedByUser.name')
                                            ->label(__('Uploaded By')),
                                    ])
                                    ->columns(5),
                            ]),
                    ]),
                Section::make(__('Physical Materials'))
                    ->description(__('Focus on the physical media used for the station.'))
                    ->schema([
                        RepeatableEntry::make('physicalMaterials')
                            ->schema([
                                TextEntry::make('type')
                                    ->label(__('Type'))
                                    ->formatStateUsing(fn (PhysicalMaterialType $state): string => $state->name),
                                TextEntry::make('name')
                                    ->label(__('Name')),
                                TextEntry::make('notes')
                                    ->label(__('Notes'))
                                    ->placeholder(__('No notes')),
                                ImageEntry::make('image')
                                    ->imageWidth(200)
                                    ->imageHeight('auto')
                                    ->openUrlInNewTab()
                                    ->label(__('Image')),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
