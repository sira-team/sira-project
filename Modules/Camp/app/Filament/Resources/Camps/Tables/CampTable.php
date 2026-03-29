<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Hostels\Pages\ViewHostel;
use Modules\Camp\Models\Camp;

final class CampTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contract.hostel.name')
                    ->badge()
                    ->color(Color::Blue)
                    ->label(__('Hostel'))
                    ->url(fn (Camp $record) => ViewHostel::getUrl(['record' => $record->contract->hostel]))
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label(__('Dates'))
                    ->formatStateUsing(fn ($record) => $record->starts_at->format('d.m.Y').' – '.$record->ends_at->format('d.m.Y'))
                    ->sortable(),
                TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('confirmedRegistrationsCount')
                    ->label(__('Confirmed'))
                    ->counts('visitors'),
                TextColumn::make('registration_is_open')
                    ->label(__('Open'))
                    ->formatStateUsing(fn (bool $state) => $state ? 'Yes' : 'No'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
