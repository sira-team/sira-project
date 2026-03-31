<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Expos\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class StationsRelationManager extends RelationManager
{
    protected static string $relationship = 'stations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('responsible_user_id')
                    ->label(__('Responsible Person'))
                    ->relationship('responsible_user', 'name')
                    ->nullable()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Station Name'))
                    ->searchable(),
                TextColumn::make('responsible_user.name')
                    ->label(__('Responsible Person'))
                    ->searchable(),
                TextColumn::make('pivot.sort_order')
                    ->label(__('Sort Order'))
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->with('responsible_user')
            );
    }
}
