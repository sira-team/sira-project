<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\Tenants\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('name')->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->sortable(),
                TextColumn::make('city')
                    ->label(__('City'))
                    ->sortable(),
                TextColumn::make('country')
                    ->label(__('Country'))
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label(__('Members')),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
