<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class HostelTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rooms_count')
                    ->label(__('Rooms'))
                    ->counts('rooms'),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
