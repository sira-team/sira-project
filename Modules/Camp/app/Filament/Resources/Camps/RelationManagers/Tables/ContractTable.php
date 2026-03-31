<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ContractTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('hostel.name')
                    ->label(__('Hostel'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price_per_person_per_night')
                    ->label(__('Price/Person/Night'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('contracted_beds')
                    ->label(__('Contracted Participants'))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
