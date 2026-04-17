<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\FormTemplates\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Camp\Models\FormTemplate;

final class FormTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('tenant.name')
                    ->label(__('Tenant'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fields_count')
                    ->counts('fields')
                    ->label(__('Fields'))
                    ->sortable(),
                TextColumn::make('camps_count')
                    ->counts('camps')
                    ->label(__('Used in Camps'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->disabled(fn (FormTemplate $record): bool => $record->camps()->exists())
                    ->tooltip(fn (FormTemplate $record): ?string => $record->camps()->exists()
                        ? __('Cannot delete a template that is linked to one or more camps.')
                        : null
                    ),
            ]);
    }
}
