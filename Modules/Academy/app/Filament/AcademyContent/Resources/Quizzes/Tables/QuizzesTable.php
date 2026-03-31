<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class QuizzesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                TextColumn::make('questions_count')
                    ->label(__('Questions'))
                    ->counts('questions')
                    ->numeric(),
                TextColumn::make('max_attempts')
                    ->label(__('Max Attempts'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('passing_score_percent')
                    ->label(__('Passing Score (%)'))
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
