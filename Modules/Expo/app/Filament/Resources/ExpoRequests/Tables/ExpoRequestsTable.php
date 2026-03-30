<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\ExpoRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class ExpoRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('contact_name')
                    ->label(__('Contact'))
                    ->searchable(),
                TextColumn::make('organisation_name')
                    ->label(__('Organisation'))
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('preferred_date_from')
                    ->label(__('From'))
                    ->date(),
                TextColumn::make('preferred_date_to')
                    ->label(__('To'))
                    ->date(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('Submitted'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
