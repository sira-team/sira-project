<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampEmailTemplates\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Camp\Enums\CampNotificationType;

final class CampEmailTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label(__('Type'))
                    ->formatStateUsing(fn (CampNotificationType $state): string => $state->label())
                    ->sortable(),

                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label(__('Last Updated'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('key');
    }
}
