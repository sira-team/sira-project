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
                TextColumn::make('hostelContract.hostel.name')
                    ->badge()
                    ->color(Color::Blue)
                    ->label('Hostel')
                    ->url(fn (Camp $record) => ViewHostel::getUrl(['record' => $record->hostelContract->hostel]))
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label('Dates')
                    ->formatStateUsing(fn ($record) => $record->starts_at->format('d.m.Y').' – '.$record->ends_at->format('d.m.Y'))
                    ->sortable(),
                TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('confirmedRegistrationsCount')
                    ->label('Confirmed')
                    ->counts('registrations'),
                TextColumn::make('registration_open')
                    ->label('Open')
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
