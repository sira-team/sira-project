<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Camp\Enums\CampExpenseCategory;

final class CampExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (CampExpenseCategory $state) => match ($state) {
                        CampExpenseCategory::Accommodation => 'info',
                        CampExpenseCategory::Catering => 'success',
                        CampExpenseCategory::Materials => 'warning',
                        CampExpenseCategory::Activities => 'warning',
                        CampExpenseCategory::Transport => 'danger',
                        CampExpenseCategory::Investment => 'primary',
                        CampExpenseCategory::Other => 'secondary',
                    }),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->numeric(decimalPlaces: 2)),
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
