<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampExpenses\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Camp\Models\CampExpense;

final class CampExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('category')
                    ->label(__('Category'))
                    ->badge(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('Amount (EUR)'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->summarize(Sum::make()->numeric(decimalPlaces: 2)),
                IconColumn::make('is_paid_by_camp')
                    ->label(__('Paid by'))
                    ->icon(fn (bool $state) => $state ? Heroicon::OutlinedBuildingLibrary : Heroicon::OutlinedUser)
                    ->color(fn (bool $state) => $state ? 'success' : 'warning')
                    ->tooltip(fn (CampExpense $record, bool $state) => $state ? __('Camp') : $record->user->name),
            ])
            ->recordActions([
                Action::make('download_receipt')
                    ->label(__('Download'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->url(fn (CampExpense $record) => route('camp-expense.download-receipt', $record))
                    ->visible(fn (CampExpense $record) => $record->receipt !== null)
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
