<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Camp\Enums\CampRegistrationStatus;

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
                    ->label('Hostel')
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
                    ->counts('registrations', function ($query) {
                        return $query->where('status', CampRegistrationStatus::Confirmed);
                    }),
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
