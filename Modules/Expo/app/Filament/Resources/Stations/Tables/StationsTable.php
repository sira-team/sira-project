<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class StationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('name')
                    ->label(__('Station Name'))
                    ->searchable(),
                TextColumn::make('physical_materials_count')
                    ->label(__('Physical Materials'))
                    ->counts('physicalMaterials'),
                TextColumn::make('digital_materials_count')
                    ->label(__('Digital Materials'))
                    ->counts('digitalMaterials'),
                TextColumn::make('sort_order')
                    ->label(__('Order')),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
